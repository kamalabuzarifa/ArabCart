<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductReviewRequest;
use App\Models\Listing;
use App\Models\ListingOrder;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class ProductReviewController extends Controller
{
    public function store(StoreProductReviewRequest $request, Listing $listing, ListingOrder $order): RedirectResponse
    {
        $userId = $request->user()?->id;

        if ($userId === null || $order->buyer_id !== $userId || $order->listing_id !== $listing->id || $order->status !== 'completed') {
            abort(403);
        }

        if ($order->review()->exists()) {
            return back()->withErrors(['rating' => 'تم تقييم هذا الطلب مسبقًا.']);
        }

        ProductReview::create([
            'order_id' => $order->id,
            'listing_id' => $listing->id,
            'buyer_id' => $order->buyer_id,
            'seller_id' => $order->seller_id,
            'rating' => (int) $request->input('rating'),
            'comment' => $request->input('comment') ? trim((string) $request->input('comment')) : null,
        ]);

        $this->updateListingAggregate($listing->id);
        $this->updateSellerAggregate($order->seller_id);

        return back()->with('status', 'تم إرسال تقييمك بنجاح. شكرًا لمشاركتك.');
    }

    private function updateListingAggregate(int $listingId): void
    {
        $aggregate = ProductReview::query()
            ->where('listing_id', $listingId)
            ->selectRaw('COALESCE(AVG(rating), 0) as avg_rating, COUNT(*) as ratings_count')
            ->first();

        Listing::query()->whereKey($listingId)->update([
            'rating_avg' => round((float) ($aggregate->avg_rating ?? 0), 2),
            'rating_count' => (int) ($aggregate->ratings_count ?? 0),
        ]);
    }

    private function updateSellerAggregate(int $sellerId): void
    {
        $aggregate = ProductReview::query()
            ->where('seller_id', $sellerId)
            ->selectRaw('COALESCE(AVG(rating), 0) as avg_rating, COUNT(*) as ratings_count')
            ->first();

        User::query()->whereKey($sellerId)->update([
            'seller_rating_avg' => round((float) ($aggregate->avg_rating ?? 0), 2),
            'seller_rating_count' => (int) ($aggregate->ratings_count ?? 0),
        ]);
    }
}
