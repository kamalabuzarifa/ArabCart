<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreListingRequest;
use App\Http\Requests\UpdateListingRequest;
use App\Models\Listing;
use App\Models\ListingOrder;
use App\Models\User;
use App\Support\Catalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MarketplaceController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $category = $request->query('category');
        $search = trim((string) $request->query('q', ''));

        $ownListingsQuery = Listing::query()
            ->where('is_active', true)
            ->with('user:id,name');

        if ($user !== null) {
            $ownListingsQuery->where('user_id', $user->id);
        } else {
            $ownListingsQuery->whereRaw('1 = 0');
        }

        if ($category) {
            $ownListingsQuery->where('category_slug', $category);
        }

        if ($search !== '') {
            $ownListingsQuery->where(function ($sub) use ($search) {
                $sub->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $ownListings = $ownListingsQuery->latest()->paginate(9)->withQueryString();

        $ownCount = $user !== null
            ? Listing::query()->where('is_active', true)->where('user_id', $user->id)->count()
            : 0;

        return view('marketplace.index', [
            'ownListings' => $ownListings,
            'ownCount' => $ownCount,
            'categories' => Catalog::categories(),
            'selectedCategory' => $category,
            'search' => $search,
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $user = $request->user();
        $search = trim((string) $request->query('q', ''));

        if (mb_strlen($search) < 2) {
            return response()->json(['items' => []]);
        }

        $categoryNames = collect(Catalog::categories())
            ->mapWithKeys(static fn (array $category): array => [$category['slug'] => $category['name']])
            ->all();

        $query = Listing::query()
            ->where('is_active', true)
            ->where(function ($sub) use ($search) {
                $sub->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->limit(6);

        if ($user !== null) {
            $query->where('user_id', '!=', $user->id);
        }

        $items = $query->get()->map(function (Listing $listing) use ($categoryNames): array {
            $categorySlug = (string) ($listing->category_slug ?? '');
            $displayPrice = $listing->has_offer && $listing->offer_price
                ? (float) $listing->offer_price
                : (float) $listing->price;

            return [
                'title' => $listing->title,
                'category' => $categoryNames[$categorySlug] ?? 'منتجات المستخدمين',
                'price' => number_format($displayPrice, 2) . ' ر.س',
                'image' => $listing->image_url ? route('marketplace.image', $listing) : null,
                'url' => route('marketplace.index', ['q' => $listing->title]),
            ];
        })->values();

        return response()->json(['items' => $items]);
    }

    public function create(): View
    {
        return view('marketplace.create', [
            'categories' => Catalog::categories(),
        ]);
    }

    public function edit(Request $request, Listing $listing): View
    {
        if ($request->user()?->id !== $listing->user_id) {
            abort(403);
        }

        return view('marketplace.edit', [
            'listing' => $listing,
            'categories' => Catalog::categories(),
        ]);
    }

    public function store(StoreListingRequest $request): RedirectResponse
    {
        $resolvedImage = $this->resolveImageReference(
            request: $request,
            currentImage: null,
            keepWhenEmpty: false,
        );

        if ($resolvedImage['error'] !== null) {
            return back()->withErrors(['image_file' => $resolvedImage['error']])->withInput();
        }

        Listing::create([
            'user_id' => $request->user()->id,
            'title' => $request->string('title')->toString(),
            'description' => $request->string('description')->toString(),
            'price' => $request->float('price'),
            'has_offer' => $request->boolean('has_offer'),
            'offer_price' => $request->boolean('has_offer') ? $request->float('offer_price') : null,
            'category_slug' => $request->input('category_slug') ?: null,
            'image_url' => $resolvedImage['value'],
            'is_active' => true,
        ]);

        return redirect()->route('marketplace.index')->with('status', 'تم إضافة المنتج بنجاح وأصبح ظاهرًا للبيع.');
    }

    public function update(UpdateListingRequest $request, Listing $listing): RedirectResponse
    {
        if ($request->user()?->id !== $listing->user_id) {
            abort(403);
        }

        $resolvedImage = $this->resolveImageReference(
            request: $request,
            currentImage: $listing->image_url,
            keepWhenEmpty: true,
        );

        if ($resolvedImage['error'] !== null) {
            return back()->withErrors(['image_file' => $resolvedImage['error']])->withInput();
        }

        $listing->update([
            'title' => $request->string('title')->toString(),
            'description' => $request->string('description')->toString(),
            'price' => $request->float('price'),
            'has_offer' => $request->boolean('has_offer'),
            'offer_price' => $request->boolean('has_offer') ? $request->float('offer_price') : null,
            'category_slug' => $request->input('category_slug') ?: null,
            'image_url' => $resolvedImage['value'],
        ]);

        return redirect()->route('marketplace.index')->with('status', 'تم تحديث المنتج بنجاح.');
    }

    public function destroy(Request $request, Listing $listing): RedirectResponse
    {
        if ($request->user()?->id !== $listing->user_id) {
            abort(403);
        }

        $this->deleteStoredImageIfNeeded($listing->image_url);
        $listing->delete();

        return redirect()->route('marketplace.index')->with('status', 'تم حذف المنتج بنجاح.');
    }

    public function buy(Request $request, Listing $listing): RedirectResponse
    {
        if ($request->user()?->id === $listing->user_id) {
            return back()->with('status', 'لا يمكنك شراء منتجك الخاص.');
        }

        DB::transaction(function () use ($request, $listing): void {
            ListingOrder::create([
                'listing_id' => $listing->id,
                'buyer_id' => $request->user()->id,
                'seller_id' => $listing->user_id,
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            $listing->increment('sales_count');
            User::query()->whereKey($listing->user_id)->increment('seller_sales_count');
        });

        return back()->with('status', 'تم إتمام شراء المنتج: ' . $listing->title . ' ويمكنك تقييمه الآن.');
    }

    public function image(Listing $listing): Response|StreamedResponse
    {
        $imageUrl = (string) ($listing->image_url ?? '');

        if ($imageUrl === '') {
            abort(404);
        }

        if (Str::startsWith($imageUrl, 'uploaded:')) {
            $path = Str::after($imageUrl, 'uploaded:');
            if (! Storage::disk('public')->exists($path)) {
                abort(404);
            }

            $fileStream = Storage::disk('public')->readStream($path);
            if ($fileStream === false) {
                abort(404);
            }

            $fullPath = Storage::disk('public')->path($path);
            $mimeType = mime_content_type($fullPath) ?: 'image/jpeg';

            return response()->stream(static function () use ($fileStream): void {
                fpassthru($fileStream);
                fclose($fileStream);
            }, 200, [
                'Content-Type' => $mimeType,
                'Cache-Control' => 'public, max-age=3600',
            ]);
        }

        abort(404);
    }

    private function resolveImageReference(Request $request, ?string $currentImage, bool $keepWhenEmpty): array
    {
        if ($request->boolean('remove_image')) {
            $this->deleteStoredImageIfNeeded($currentImage);
            return ['value' => null, 'error' => null];
        }

        if ($request->hasFile('image_file')) {
            $this->deleteStoredImageIfNeeded($currentImage);
            $storedPath = $request->file('image_file')->store('listings', 'public');
            return ['value' => 'uploaded:' . $storedPath, 'error' => null];
        }

        return [
            'value' => $keepWhenEmpty ? $currentImage : null,
            'error' => null,
        ];
    }

    private function deleteStoredImageIfNeeded(?string $imageUrl): void
    {
        $image = (string) ($imageUrl ?? '');
        if ($image === '' || ! Str::startsWith($image, 'uploaded:')) {
            return;
        }

        $path = Str::after($image, 'uploaded:');
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
