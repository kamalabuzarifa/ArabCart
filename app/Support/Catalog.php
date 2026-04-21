<?php

namespace App\Support;

use App\Models\Listing;

class Catalog
{
    public static function categoryCoverImage(?string $slug): ?string
    {
        return match ($slug) {
            'electronics' => asset('images/Electronics.jpg'),
            'fashion' => asset('images/Fashion.jpg'),
            'perfumes' => asset('images/perfumes.jpg'),
            'home' => asset('images/house.jpg'),
            default => null,
        };
    }

    /**
     * @return array<int, array{slug: string, icon: string, name: string}>
     */
    public static function categories(): array
    {
        return [
            ['slug' => 'electronics', 'icon' => 'devices', 'name' => 'الإلكترونيات'],
            ['slug' => 'fashion', 'icon' => 'checkroom', 'name' => 'الأزياء'],
            ['slug' => 'home', 'icon' => 'home', 'name' => 'المنزل'],
            ['slug' => 'perfumes', 'icon' => 'self_care', 'name' => 'العطور'],
            ['slug' => 'gifts', 'icon' => 'featured_seasonal_and_gifts', 'name' => 'الهدايا'],
        ];
    }

    /**
        * @return array<int, array{listing_id: int, user_id: int, category_slug: string, category_name: string, title: string, description: string, price: int, price_value: int, old_price: int|null, image: string|null}>
     */
    public static function products(): array
    {
        $categoryNames = [];
        foreach (self::categories() as $category) {
            $categoryNames[$category['slug']] = $category['name'];
        }

        $marketplaceProducts = Listing::query()
            ->where('is_active', true)
            ->whereNotNull('category_slug')
            ->whereNotNull('image_url')
            ->latest()
            ->get()
            ->map(static function (Listing $listing) use ($categoryNames): array {
                $categorySlug = (string) $listing->category_slug;
                $basePrice = (float) $listing->price;
                $effectivePrice = $listing->has_offer && $listing->offer_price
                    ? (float) $listing->offer_price
                    : $basePrice;

                return [
                    'listing_id' => (int) $listing->id,
                    'user_id' => (int) $listing->user_id,
                    'category_slug' => $categorySlug,
                    'category_name' => $categoryNames[$categorySlug] ?? 'منتجات المستخدمين',
                    'title' => $listing->title,
                    'description' => $listing->description,
                    'price' => (int) round($effectivePrice),
                    'price_value' => (int) round($effectivePrice),
                    'old_price' => $listing->has_offer && $listing->offer_price
                        ? (int) round($basePrice)
                        : null,
                    'image' => $listing->image_url ? route('marketplace.image', $listing) : null,
                ];
            })
            ->all();

        return $marketplaceProducts;
    }

    public static function findCategory(string $slug): ?array
    {
        foreach (self::categories() as $category) {
            if ($category['slug'] === $slug) {
                return $category;
            }
        }

        return null;
    }

    /**
        * @return array<int, array{listing_id: int, user_id: int, category_slug: string, category_name: string, title: string, description: string, price: int, price_value: int, old_price: int|null, image: string|null}>
     */
    public static function filteredProducts(?string $categorySlug = null, int $maxPrice = 5000): array
    {
        $maxPrice = max(0, min(5000, $maxPrice));

        return array_values(array_filter(self::products(), static function (array $product) use ($categorySlug, $maxPrice): bool {
            if ($categorySlug !== null && $product['category_slug'] !== $categorySlug) {
                return false;
            }

            return $product['price'] <= $maxPrice;
        }));
    }
}
