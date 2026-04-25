<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\ListingOrder;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(): View
    {
        return view('checkout.index');
    }

    public function itemsMeta(Request $request): JsonResponse
    {
        $rawIds = $request->query('listing_ids', '');

        $ids = collect(explode(',', (string) $rawIds))
            ->map(static fn (string $id): int => (int) trim($id))
            ->filter(static fn (int $id): bool => $id > 0)
            ->unique()
            ->take(50)
            ->values();

        if ($ids->isEmpty()) {
            return response()->json(['items' => []]);
        }

        $items = Listing::query()
            ->whereIn('id', $ids->all())
            ->get(['id', 'title', 'description'])
            ->mapWithKeys(static function (Listing $listing): array {
                return [
                    (string) $listing->id => [
                        'title' => (string) $listing->title,
                        'description' => (string) ($listing->description ?? ''),
                    ],
                ];
            });

        return response()->json([
            'items' => $items,
        ]);
    }

    public function complete(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'listing_ids' => ['required', 'array', 'min:1'],
            'listing_ids.*' => ['integer', 'distinct', 'exists:listings,id'],
            'payment_method' => ['required', 'in:card,apple,cash'],
        ]);

        $buyer = $request->user();
        $listingIds = array_values(array_map('intval', $validated['listing_ids']));
        $paymentMethod = $validated['payment_method'];

        $listings = Listing::query()
            ->whereIn('id', $listingIds)
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        $purchased = [];
        $skipped = [];

        DB::transaction(function () use ($buyer, $listingIds, $listings, $paymentMethod, &$purchased, &$skipped): void {
            foreach ($listingIds as $listingId) {
                /** @var Listing|null $listing */
                $listing = $listings->get($listingId);

                if ($listing === null) {
                    $skipped[] = ['listing_id' => $listingId, 'reason' => 'not_available'];
                    continue;
                }

                if ((int) $listing->user_id === (int) $buyer->id) {
                    $skipped[] = ['listing_id' => $listingId, 'reason' => 'own_product'];
                    continue;
                }

                $alreadyPurchased = ListingOrder::query()
                    ->where('listing_id', $listing->id)
                    ->where('buyer_id', $buyer->id)
                    ->where('status', 'completed')
                    ->exists();

                if ($alreadyPurchased) {
                    $skipped[] = ['listing_id' => (int) $listing->id, 'reason' => 'already_purchased'];
                    continue;
                }

                ListingOrder::create([
                    'listing_id' => $listing->id,
                    'buyer_id' => $buyer->id,
                    'seller_id' => $listing->user_id,
                    'status' => 'completed',
                    'payment_method' => $paymentMethod,
                    'completed_at' => now(),
                ]);

                $listing->increment('sales_count');
                User::query()->whereKey($listing->user_id)->increment('seller_sales_count');

                $purchased[] = (int) $listing->id;
            }
        });

        return response()->json([
            'message' => 'تمت معالجة الطلب.',
            'purchased_listing_ids' => $purchased,
            'skipped' => $skipped,
            'payment_method' => $paymentMethod,
        ]);
    }
}
