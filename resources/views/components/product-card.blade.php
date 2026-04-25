@props([
    'title',
    'image',
    'category' => null,
    'description' => null,
    'price' => null,
    'oldPrice' => null,
    'rating' => null,
    'ratingCount' => null,
    'salesCount' => null,
    'tag' => null,
    'badgeClass' => 'bg-primary/90 text-white',
    'favoriteKey' => null,
    'favoriteTitle' => null,
    'favoriteImage' => null,
    'favoritePrice' => null,
    'favoriteCategory' => null,
    'cartItemId' => null,
    'cartListingId' => null,
    'cartTitle' => null,
    'cartImage' => null,
    'cartPrice' => null,
    'cartPriceLabel' => null,
    'cartCategory' => null,
    'cartDescription' => null,
    'cartBuyUrl' => null,
    'isOwnProduct' => false,
    'ownLabel' => 'هذا المنتج ملكك',
    'ownIcon' => 'shopping_cart',
    'headingTag' => 'h3',
    'showSeller' => false,
    'sellerName' => null,
    'sellerLabel' => 'البائع:',
    'showStats' => true,
    'showFavorite' => true,
    'showCart' => true,
    'discountText' => null,
    'showDiscountBadge' => false,
    'discountBadgeClass' => 'bg-error text-white',
    'discountBadgePosition' => 'top-4 right-4',
])

@php
    $favoriteTitle = $favoriteTitle ?? $title;
    $favoriteImage = $favoriteImage ?? $image;
    $favoritePrice = $favoritePrice ?? $price;
    $favoriteCategory = $favoriteCategory ?? $category;
    $cartItemId = $cartItemId ?? ('listing-' . ($cartListingId ?? ''));
    $cartTitle = $cartTitle ?? $title;
    $cartImage = $cartImage ?? $image;
    $cartPrice = $cartPrice ?? $price;
    $cartPriceLabel = $cartPriceLabel ?? $price;
    $cartCategory = $cartCategory ?? $category;
    $cartDescription = $cartDescription ?? $description;
    $headingTag = in_array($headingTag, ['h2', 'h3', 'h4'], true) ? $headingTag : 'h3';
@endphp

<article {{ $attributes->class(['product-card', 'group bg-surface-container-lowest rounded-xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-transform duration-300 hover:-translate-y-2 flex flex-col h-full']) }}>
    <div class="relative aspect-[4/5] overflow-hidden">
        @if ($showDiscountBadge && $discountText)
            <span class="absolute {{ $discountBadgePosition }} z-10 inline-flex items-center rounded-full {{ $discountBadgeClass }} text-[10px] px-2 py-1 font-bold">{{ $discountText }}</span>
        @endif

        @if ($tag)
            <div class="absolute top-4 right-4 {{ $badgeClass }} px-3 py-1 rounded-full shadow-sm">
                <span class="text-[10px] font-black uppercase tracking-wider">{{ $tag }}</span>
            </div>
        @endif

        @if (!empty($image))
            <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" alt="{{ $title }}" src="{{ $image }}" />
        @else
            <div class="w-full h-full bg-gradient-to-br from-surface-container-high to-surface-container-low flex flex-col items-center justify-center gap-3 text-on-surface-variant">
                <span class="material-symbols-outlined text-4xl">image_not_supported</span>
                <span class="text-xs font-bold">لا توجد صورة</span>
            </div>
        @endif

        @if ($showFavorite)
            <button class="absolute bottom-4 left-4 p-2 bg-white/90 backdrop-blur-sm rounded-full shadow-lg opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-300" type="button" data-favorite-toggle data-favorite-key="{{ $favoriteKey ?? ('product-' . \Illuminate\Support\Str::slug($title)) }}" data-favorite-title="{{ $favoriteTitle }}" data-favorite-image="{{ $favoriteImage }}" data-favorite-price="{{ $favoritePrice }}" data-favorite-category="{{ $favoriteCategory }}" aria-pressed="false" aria-label="إضافة إلى المفضلة">
                <span class="material-symbols-outlined text-primary">favorite_border</span>
            </button>
        @endif
    </div>

    <div class="p-6 flex flex-col flex-grow text-right">
        @if (!empty($category))
            <span class="text-xs font-bold text-on-surface-variant mb-2">{{ $category }}</span>
        @endif

        <{{ $headingTag }} class="text-lg font-bold text-primary mb-4 leading-tight group-hover:text-secondary transition-colors line-clamp-2">{{ $title }}</{{ $headingTag }}>

        @if ($showSeller && $sellerName)
            <p class="text-xs text-on-surface-variant mb-3">{{ $sellerLabel }} {{ $sellerName }}</p>
        @endif

        @if ($showStats && ($rating !== null || $salesCount !== null))
            <div class="mb-3 flex items-center justify-between text-xs text-on-surface-variant">
                <div class="inline-flex items-center gap-1 text-amber-500">
                    @php
                        $roundedRating = (int) round((float) ($rating ?? 0));
                    @endphp
                    @for ($i = 1; $i <= 5; $i++)
                        <span class="material-symbols-outlined text-base" style="font-variation-settings: 'FILL' {{ $i <= $roundedRating ? 1 : 0 }};">star</span>
                    @endfor
                    <span class="text-on-surface text-[11px] font-bold">{{ number_format((float) ($rating ?? 0), 1) }}{{ $ratingCount !== null ? ' (' . (int) $ratingCount . ')' : '' }}</span>
                </div>
                @if ($salesCount !== null)
                    <span>{{ (int) $salesCount }} عملية</span>
                @endif
            </div>
        @endif

        @if (!empty($description))
            <p class="text-sm text-on-surface-variant mb-4 line-clamp-2">{{ $description }}</p>
        @endif

        <div class="mt-auto flex items-center justify-between gap-2">
            <div class="flex flex-col">
                <span class="text-xl font-black text-primary">{{ $price }}</span>
                @if (!empty($oldPrice))
                    <span class="text-xs text-outline line-through">{{ $oldPrice }}</span>
                @endif
            </div>

            <div class="flex items-center gap-2">
                @auth
                    @if ($isOwnProduct)
                        <button class="bg-surface-container-high text-on-surface-variant p-3 rounded-full flex items-center justify-center cursor-not-allowed opacity-70" type="button" disabled aria-disabled="true" aria-label="{{ $ownLabel }}">
                            <span class="material-symbols-outlined">{{ $ownIcon }}</span>
                        </button>
                    @elseif ($showCart && $cartBuyUrl)
                        <button class="bg-primary text-white p-3 rounded-full hover:bg-primary-container transition-colors flex items-center justify-center" type="button" data-cart-add data-cart-item-id="{{ $cartItemId }}" data-cart-listing-id="{{ $cartListingId }}" data-cart-title="{{ $cartTitle }}" data-cart-image="{{ $cartImage }}" data-cart-price="{{ $cartPrice }}" data-cart-price-label="{{ $cartPriceLabel }}" data-cart-category="{{ $cartCategory }}" data-cart-description="{{ $cartDescription }}" data-cart-buy-url="{{ $cartBuyUrl }}" aria-label="إضافة إلى السلة">
                            <span class="material-symbols-outlined">shopping_cart</span>
                        </button>
                    @endif
                @else
                    @if ($showCart)
                        <a href="{{ route('login') }}" class="bg-primary text-white p-3 rounded-full hover:bg-primary-container transition-colors flex items-center justify-center" aria-label="تسجيل الدخول للشراء">
                            <span class="material-symbols-outlined">shopping_cart</span>
                        </a>
                    @endif
                @endauth

                {{ $actions ?? '' }}
            </div>
        </div>
    </div>
</article>
