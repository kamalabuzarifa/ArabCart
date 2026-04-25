@extends('layouts.main')

@section('title', config('app.name', 'ArabCart') . ' | العروض')
@section('nav-active', 'offers')

@php
    use App\Support\Catalog;
    
    $categories = Catalog::categories();
    $categoryNames = collect($categories)->pluck('name', 'slug')->all();
    $categoryHeroImages = collect($categories)
        ->mapWithKeys(static fn (array $category): array => [$category['slug'] => Catalog::categoryCoverImage($category['slug'])])
        ->all();
    $reviewableOrders = [];

    if (auth()->check()) {
        $reviewableOrders = \App\Models\ListingOrder::query()
            ->where('buyer_id', auth()->id())
            ->where('status', 'completed')
            ->whereDoesntHave('review')
            ->latest()
            ->get()
            ->mapWithKeys(static fn (\App\Models\ListingOrder $order): array => [(int) $order->listing_id => (int) $order->id])
            ->all();
    }

    $offersCollection = \App\Models\Listing::query()
        ->where('is_active', true)
        ->where('has_offer', true)
        ->whereNotNull('offer_price')
        ->whereNotNull('category_slug')
        ->whereNotNull('image_url')
        ->latest()
        ->get()
        ->map(static function (\App\Models\Listing $listing) use ($categoryNames): array {
            $oldPrice = (int) round((float) $listing->price);
            $price = (int) round((float) $listing->offer_price);
            $discountValue = $oldPrice > 0 ? max(1, (int) round((($oldPrice - $price) / $oldPrice) * 100)) : 0;
            $categorySlug = (string) $listing->category_slug;

            return [
                'listing_id' => (int) $listing->id,
                'user_id' => (int) $listing->user_id,
                'category_slug' => $categorySlug,
                'category_name' => $categoryNames[$categorySlug] ?? $categorySlug,
                'title' => $listing->title,
                'description' => $listing->description,
                'rating_avg' => (float) ($listing->rating_avg ?? 0),
                'rating_count' => (int) ($listing->rating_count ?? 0),
                'sales_count' => (int) ($listing->sales_count ?? 0),
                'price' => $price,
                'old_price' => $oldPrice,
                'image' => route('marketplace.image', $listing),
                'discount' => '-' . $discountValue . '%',
                'discount_value' => $discountValue,
            ];
        })
        ;

    $offers = $offersCollection->all();
    $maxDiscountByCategory = $offersCollection
        ->groupBy('category_slug')
        ->map(static fn ($items): int => (int) $items->max('discount_value'))
        ->all();

    $defaultHeroCategoryName = 'الموسم';
    $defaultHeroDiscount = $maxDiscountByCategory ? max($maxDiscountByCategory) : 0;
    $defaultHeroImage = asset('images/Offers.jpg');
    $limit = 3;
    $gridColumnsClass = (int) $limit === 3 ? 'lg:grid-cols-3' : 'lg:grid-cols-4';
@endphp

@push('page-styles')
    <style>
        .cubic-transition {
            transition-timing-function: cubic-bezier(0.2, 0, 0, 1);
        }

        .nav-action-icon {
            font-size: 24px;
            line-height: 1;
            flex-shrink: 0;
        }
    </style>
@endpush

@section('sidebar')
    <div class="px-6 pt-6">
        <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined">category</span>
            <span>التصنيفات</span>
        </h3>
        <nav class="flex flex-col gap-1">
            <a class="flex items-center gap-3 p-3 rounded-xl bg-primary text-white font-bold category-filter-offers" href="#" data-category="" data-hero-image="" data-hero-category-label="عروض الموسم" data-hero-description="استكشف مجموعتنا الفاخرة بأسعار حصرية لفترة محدودة جداً." data-hero-discount="{{ $maxDiscountByCategory ? max($maxDiscountByCategory) : $defaultHeroDiscount }}">
                <span class="material-symbols-outlined text-xl">apps</span>
                <span>الكل</span>
            </a>
            @foreach ($categories as $category)
                <a class="flex items-center gap-3 p-3 rounded-xl text-slate-600 hover:bg-surface-container-high hover:text-primary transition-all category-filter-offers" href="#" data-category="{{ $category['slug'] }}" data-hero-image="{{ $categoryHeroImages[$category['slug']] ?? '' }}" data-hero-category-label="عروض {{ $category['name'] }}" data-hero-description="أفضل عروض {{ $category['name'] }} الآن" data-hero-discount="{{ $maxDiscountByCategory[$category['slug']] ?? 0 }}">
                    <span class="material-symbols-outlined text-xl">{{ $category['icon'] }}</span>
                    <span class="font-medium">{{ $category['name'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>
    <div class="px-6 mt-6 pb-6 border-t border-outline-variant/20 pt-6">
        <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined">tune</span>
            <span>نطاق السعر</span>
        </h3>
        <div class="space-y-3">
            <input id="offers-price-range" class="w-full h-1 bg-surface-container-highest rounded-lg appearance-none cursor-pointer accent-primary" type="range" min="0" max="5000" step="50" value="5000" />
            <div class="flex justify-between items-center text-xs font-bold text-on-surface-variant">
                <span>٠ ر.س</span>
                <span>٥٠٠٠ ر.س</span>
            </div>
            <p class="text-sm text-primary font-bold">الحد الاعلى: <span id="offers-price-value">5,000</span> ر.س</p>
            <button id="offers-apply-price" type="button" class="w-full text-sm px-4 py-2 rounded-full bg-primary text-white font-bold hover:bg-primary-container transition-colors">تطبيق</button>
        </div>
    </div>
    <div class="px-6 mt-6 pb-6 border-t border-outline-variant/20 pt-6">
        <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined">percent</span>
            <span>نسبة الخصم</span>
        </h3>
        <div class="space-y-3">
            <button class="w-full text-right py-2 px-3 rounded-lg text-sm bg-surface-container-lowest hover:bg-white cubic-transition shadow-sm discount-filter-offers" data-discount-filter="50-plus" type="button">أكثر من 50%</button>
            <button class="w-full text-right py-2 px-3 rounded-lg text-sm bg-surface-container-lowest hover:bg-white cubic-transition shadow-sm discount-filter-offers" data-discount-filter="30-50" type="button">30% - 50%</button>
            <button class="w-full text-right py-2 px-3 rounded-lg text-sm bg-surface-container-lowest hover:bg-white cubic-transition shadow-sm discount-filter-offers" data-discount-filter="under-30" type="button">أقل من 30%</button>
        </div>
    </div>
@endsection

@section('content')
    <section class="page-container mb-16">
        <div class="relative w-full h-[500px] rounded-3xl overflow-hidden group">
            @if ($defaultHeroImage)
                <img id="offers-hero-image" class="absolute inset-0 w-full h-full object-cover object-center opacity-100" alt="خصومات موسمية" src="{{ $defaultHeroImage }}" />
            @endif
            <div class="absolute inset-0 flex flex-col justify-center items-end px-12 text-right">
                @auth
                    <a href="{{ route('groups.index') }}" class="absolute left-[60px] bottom-[70px] inline-flex items-center justify-center bg-[#FFC107] text-slate-900 px-10 py-4 rounded-full font-bold text-lg hover:opacity-90 transition-all scale-100 active:scale-95 shadow-lg">تسوق الآن</a>
                @else
                    <a href="{{ route('login') }}" class="absolute left-[60px] bottom-[70px] inline-flex items-center justify-center bg-[#FFC107] text-slate-900 px-10 py-4 rounded-full font-bold text-lg hover:opacity-90 transition-all scale-100 active:scale-95 shadow-lg">سجّل وتسوق الآن</a>
                @endauth
            </div>
        </div>
    </section>

    <section class="px-6 max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8 bg-surface-container-low/50 p-4 rounded-xl">
            <p class="text-on-surface-variant font-medium">عرض <span class="text-primary font-bold">{{ count($offers) }}</span> منتجاً</p>
            <div class="flex items-center gap-2">
                <span class="text-sm text-on-surface-variant">ترتيب حسب:</span>
                <select id="offers-sort-order" dir="rtl" class="bg-transparent border-none focus:ring-0 text-sm font-bold text-primary cursor-pointer text-right pr-3" style="direction: rtl; text-align: right; text-align-last: right;">
                    <option>الأكثر خصماً</option>
                    <option>السعر: من الأقل للأعلى</option>
                    <option>السعر: من الأعلى للأقل</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 {{ $gridColumnsClass }} gap-8 rtl-product-grid" id="offers-grid">
            @foreach ($offers as $offer)
                @php
                    $isOwnProduct = auth()->check() && (int) ($offer['user_id'] ?? 0) === auth()->id();
                @endphp
                <x-product-card
                    class="product-card-groups offer-card"
                    data-order="{{ $loop->index }}"
                    data-category="{{ $offer['category_slug'] }}"
                    data-price="{{ $offer['price'] }}"
                    data-discount="{{ $offer['discount_value'] ?? 0 }}"
                    :title="$offer['title']"
                    :image="$offer['image'] ?? null"
                    :category="$offer['category_name']"
                    :description="$offer['description'] ?? ''"
                    :price="number_format($offer['price']) . ' ر.س'"
                    :old-price="number_format($offer['old_price']) . ' ر.س'"
                    :rating="$offer['rating_avg'] ?? 0"
                    :rating-count="$offer['rating_count'] ?? 0"
                    :sales-count="$offer['sales_count'] ?? 0"
                    :show-discount-badge="true"
                    :discount-text="$offer['discount']"
                    discount-badge-class="bg-secondary-container text-on-secondary-container"
                    :favorite-key="'offers-' . $loop->index . '-' . \Illuminate\Support\Str::slug($offer['title'])"
                    :favorite-title="$offer['title']"
                    :favorite-image="$offer['image']"
                    :favorite-price="number_format($offer['price']) . ' ر.س'"
                    :favorite-category="$offer['category_name']"
                    :cart-item-id="'listing-' . $offer['listing_id']"
                    :cart-listing-id="$offer['listing_id']"
                    :cart-title="$offer['title']"
                    :cart-image="$offer['image']"
                    :cart-price="$offer['price']"
                    :cart-price-label="number_format($offer['price']) . ' ر.س'"
                    :cart-category="$offer['category_name']"
                    :cart-buy-url="route('marketplace.buy', ['listing' => $offer['listing_id']])"
                    :is-own-product="$isOwnProduct"
                    heading-tag="h2"
                />
            @endforeach
        </div>

        <div id="offers-empty-state" class="hidden mt-8 rounded-2xl border border-dashed border-outline-variant bg-surface-container-low p-8 text-center">
            <p id="offers-empty-title" class="text-lg font-bold text-primary">لا توجد عروض مطابقة حالياً</p>
            <p id="offers-empty-description" class="text-sm text-on-surface-variant mt-2">جرّب تعديل التصفية للعثور على عروض مناسبة.</p>
        </div>

        <div class="mt-16 flex justify-center items-center gap-4" id="offers-pagination">
            <button id="offers-page-prev" class="w-10 h-10 rounded-full border border-outline-variant flex items-center justify-center hover:bg-surface-container-high cubic-transition disabled:opacity-40 disabled:cursor-not-allowed" type="button"><span class="material-symbols-outlined">chevron_right</span></button>
            <div id="offers-page-numbers" class="flex items-center gap-2"></div>
            <button id="offers-page-next" class="w-10 h-10 rounded-full border border-outline-variant flex items-center justify-center hover:bg-surface-container-high cubic-transition disabled:opacity-40 disabled:cursor-not-allowed" type="button"><span class="material-symbols-outlined">chevron_left</span></button>
        </div>
    </section>
@endsection

@push('page-scripts')
    <script>
        (function () {
            var filters = document.querySelectorAll('.category-filter-offers');
            var offerCards = document.querySelectorAll('.offer-card');
            var offerGrid = document.getElementById('offers-grid');
            var heroImage = document.getElementById('offers-hero-image');
            var heroCategory = document.getElementById('offers-hero-category');
            var heroDiscount = document.getElementById('offers-hero-discount');
            var heroDescription = document.getElementById('offers-hero-description');
            var sortOrder = document.getElementById('offers-sort-order');
            var priceRange = document.getElementById('offers-price-range');
            var priceValue = document.getElementById('offers-price-value');
            var applyButton = document.getElementById('offers-apply-price');
            var discountFilters = document.querySelectorAll('.discount-filter-offers');
            var prevPageButton = document.getElementById('offers-page-prev');
            var nextPageButton = document.getElementById('offers-page-next');
            var pageNumbersContainer = document.getElementById('offers-page-numbers');
            var pagination = document.getElementById('offers-pagination');
            var emptyState = document.getElementById('offers-empty-state');
            var emptyTitle = document.getElementById('offers-empty-title');
            var emptyDescription = document.getElementById('offers-empty-description');
            var currentCategory = '';
            var maxPrice = 5000;
            var currentDiscountFilter = '';
            var currentSort = 'discount-desc';
            var currentPage = 1;
            var pageSize = 3;

            function updatePriceLabel() {
                if (!priceRange || !priceValue) return;
                priceValue.textContent = Number(priceRange.value).toLocaleString('en-US');
            }

            function updateHeroFromFilter(filterButton) {
                return;
            }

            function getFilteredCards() {
                var filteredCards = Array.prototype.filter.call(offerCards, function (card) {
                    var cardCategory = card.getAttribute('data-category') || '';
                    var cardPrice = Number(card.getAttribute('data-price') || '0');
                    var cardDiscount = Number(card.getAttribute('data-discount') || '0');
                    var categoryMatches = currentCategory === '' || cardCategory === currentCategory;
                    var priceMatches = cardPrice <= maxPrice;
                    var discountMatches = true;

                    if (currentDiscountFilter === '50-plus') {
                        discountMatches = cardDiscount > 50;
                    } else if (currentDiscountFilter === '30-50') {
                        discountMatches = cardDiscount >= 30 && cardDiscount <= 50;
                    } else if (currentDiscountFilter === 'under-30') {
                        discountMatches = cardDiscount < 30;
                    }

                    return categoryMatches && priceMatches && discountMatches;
                });

                return filteredCards.sort(function (leftCard, rightCard) {
                    var leftPrice = Number(leftCard.getAttribute('data-price') || '0');
                    var rightPrice = Number(rightCard.getAttribute('data-price') || '0');
                    var leftDiscount = Number(leftCard.getAttribute('data-discount') || '0');
                    var rightDiscount = Number(rightCard.getAttribute('data-discount') || '0');
                    var leftOrder = Number(leftCard.getAttribute('data-order') || '0');
                    var rightOrder = Number(rightCard.getAttribute('data-order') || '0');

                    if (currentSort === 'price-asc') {
                        return leftPrice - rightPrice || leftOrder - rightOrder;
                    }

                    if (currentSort === 'price-desc') {
                        return rightPrice - leftPrice || leftOrder - rightOrder;
                    }

                    return rightDiscount - leftDiscount || leftOrder - rightOrder;
                });
            }

            function updatePagination(filteredCards) {
                var totalPages = Math.max(1, Math.ceil(filteredCards.length / pageSize));
                if (currentPage > totalPages) {
                    currentPage = totalPages;
                }

                if (pageNumbersContainer) {
                    pageNumbersContainer.innerHTML = '';

                    for (var pageNumber = 1; pageNumber <= totalPages; pageNumber += 1) {
                        var pageButton = document.createElement('button');
                        pageButton.type = 'button';
                        pageButton.dataset.page = String(pageNumber);
                        pageButton.textContent = pageNumber.toLocaleString('ar-EG');
                        pageButton.className = 'w-10 h-10 flex items-center justify-center rounded-full transition-colors cubic-transition';

                        if (currentPage === pageNumber) {
                            pageButton.classList.add('bg-primary', 'text-white', 'font-bold');
                        } else {
                            pageButton.classList.add('text-on-surface-variant', 'hover:bg-surface-container-high');
                        }

                        pageNumbersContainer.appendChild(pageButton);
                    }
                }

                if (prevPageButton) {
                    prevPageButton.disabled = currentPage <= 1;
                }

                if (nextPageButton) {
                    nextPageButton.disabled = currentPage >= totalPages;
                }
            }

            function applyFilters() {
                var filteredCards = getFilteredCards();
                updatePagination(filteredCards);

                if (emptyState) {
                    emptyState.classList.toggle('hidden', filteredCards.length > 0);
                }

                if (pagination) {
                    pagination.classList.toggle('hidden', filteredCards.length === 0);
                }

                if (filteredCards.length === 0 && emptyTitle && emptyDescription) {
                    var hasCategoryFilter = currentCategory !== '';
                    var hasPriceFilter = maxPrice < 5000;
                    var hasDiscountFilter = currentDiscountFilter !== '';

                    if (hasPriceFilter && !hasCategoryFilter && !hasDiscountFilter) {
                        emptyTitle.textContent = 'لا توجد عروض ضمن نطاق السعر الحالي';
                        emptyDescription.textContent = 'ارفع الحد الأعلى للسعر ثم اضغط تطبيق لعرض عروض أكثر.';
                    } else if (hasDiscountFilter && !hasCategoryFilter && !hasPriceFilter) {
                        emptyTitle.textContent = 'لا توجد عروض بنسبة الخصم المختارة';
                        emptyDescription.textContent = 'جرّب نسبة خصم مختلفة أو أزل فلتر الخصم.';
                    } else if (hasCategoryFilter && !hasPriceFilter && !hasDiscountFilter) {
                        emptyTitle.textContent = 'لا توجد عروض في هذا التصنيف حالياً';
                        emptyDescription.textContent = 'اختر تصنيفًا آخر أو عد لاحقًا بعد إضافة عروض جديدة.';
                    } else {
                        emptyTitle.textContent = 'لا توجد عروض مطابقة للتصفية الحالية';
                        emptyDescription.textContent = 'عدّل التصنيف أو السعر أو نسبة الخصم للحصول على نتائج.';
                    }
                }

                offerCards.forEach(function (card) {
                    card.style.display = 'none';
                });

                var startIndex = (currentPage - 1) * pageSize;
                var endIndex = startIndex + pageSize;
                filteredCards.slice(startIndex, endIndex).forEach(function (card) {
                    card.style.display = 'flex';

                    if (offerGrid) {
                        offerGrid.appendChild(card);
                    }
                });
            }

            if (sortOrder) {
                sortOrder.addEventListener('change', function () {
                    var selectedIndex = sortOrder.selectedIndex;
                    currentSort = 'discount-desc';

                    if (selectedIndex === 1) {
                        currentSort = 'price-asc';
                    } else if (selectedIndex === 2) {
                        currentSort = 'price-desc';
                    }

                    currentPage = 1;
                    applyFilters();
                });
            }

            filters.forEach(function (filter) {
                filter.addEventListener('click', function (e) {
                    e.preventDefault();
                    currentCategory = this.getAttribute('data-category') || '';
                    
                    filters.forEach(function (f) {
                        f.classList.remove('bg-primary', 'text-white', 'font-bold');
                        f.classList.add('text-slate-600');
                    });
                    
                    this.classList.add('bg-primary', 'text-white', 'font-bold');
                    this.classList.remove('text-slate-600');

                    updateHeroFromFilter(this);

                    currentPage = 1;
                    applyFilters();
                });
            });

            if (priceRange) {
                priceRange.addEventListener('input', updatePriceLabel);
            }

            if (applyButton) {
                applyButton.addEventListener('click', function () {
                    maxPrice = Number(priceRange ? priceRange.value : 5000);
                    currentPage = 1;
                    applyFilters();
                });
            }

            if (pageNumbersContainer) {
                pageNumbersContainer.addEventListener('click', function (event) {
                    var button = event.target.closest('button[data-page]');
                    if (!button) return;

                    currentPage = Number(button.dataset.page || '1');
                    applyFilters();
                });
            }

            discountFilters.forEach(function (button) {
                button.addEventListener('click', function () {
                    currentDiscountFilter = this.getAttribute('data-discount-filter') || '';

                    discountFilters.forEach(function (discountButton) {
                        discountButton.classList.remove('bg-primary', 'text-white', 'font-bold');
                        discountButton.classList.add('bg-surface-container-lowest');
                    });

                    this.classList.add('bg-primary', 'text-white', 'font-bold');
                    this.classList.remove('bg-surface-container-lowest');

                    currentPage = 1;
                    applyFilters();
                });
            });

            if (prevPageButton) {
                prevPageButton.addEventListener('click', function () {
                    if (currentPage > 1) {
                        currentPage -= 1;
                        applyFilters();
                    }
                });
            }

            if (nextPageButton) {
                nextPageButton.addEventListener('click', function () {
                    currentPage += 1;
                    applyFilters();
                });
            }

            updatePriceLabel();
            updateHeroFromFilter(document.querySelector('.category-filter-offers[data-category=""]'));
            applyFilters();
        })();
    </script>
@endpush