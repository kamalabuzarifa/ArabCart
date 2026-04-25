@extends('layouts.main')

@section('title', config('app.name', 'ArabCart') . ' | المجموعات')
@section('nav-active', 'groups')

@php
    use App\Support\Catalog;
    
    $categories = Catalog::categories();
    $categoryNames = collect($categories)->pluck('name', 'slug')->all();

    $products = \App\Models\Listing::query()
        ->where('is_active', true)
        ->whereNotNull('category_slug')
        ->whereNotNull('image_url')
        ->latest()
        ->get()
        ->map(static function (\App\Models\Listing $listing) use ($categoryNames): array {
            $basePrice = (int) round((float) $listing->price);
            $offerPrice = $listing->has_offer && $listing->offer_price
                ? (int) round((float) $listing->offer_price)
                : null;
            $price = $offerPrice ?? $basePrice;
            $categorySlug = (string) $listing->category_slug;

            return [
                'listing_id' => (int) $listing->id,
                'user_id' => (int) $listing->user_id,
                'tag' => 'جديد',
                'image' => route('marketplace.image', $listing),
                'category' => $categoryNames[$categorySlug] ?? $categorySlug,
                'category_slug' => $categorySlug,
                'title' => $listing->title,
                'description' => $listing->description,
                'rating_avg' => (float) ($listing->rating_avg ?? 0),
                'rating_count' => (int) ($listing->rating_count ?? 0),
                'sales_count' => (int) ($listing->sales_count ?? 0),
                'price' => number_format($price) . ' ر.س',
                'oldPrice' => $offerPrice !== null ? number_format($basePrice) . ' ر.س' : null,
                'price_value' => $price,
                'badgeClass' => 'bg-primary/90 text-white',
            ];
        })
        ->all();

    $limit = 3;
    $gridColumnsClass = (int) $limit === 3 ? 'lg:grid-cols-3' : 'lg:grid-cols-4';
@endphp

@push('page-styles')
    <style>
        .group-active {
            background: #003345;
            color: #ffffff;
            border-color: #003345;
        }

        #product-grid,
        .rtl-product-grid {
            direction: rtl;
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
            <a class="flex items-center gap-3 p-3 rounded-xl bg-primary text-white font-bold category-filter-groups" href="#" data-category="">
                <span class="material-symbols-outlined text-xl">apps</span>
                <span>الكل</span>
            </a>
            @foreach ($categories as $category)
                <a class="flex items-center gap-3 p-3 rounded-xl text-slate-600 hover:bg-surface-container-high hover:text-primary transition-all category-filter-groups" href="#" data-category="{{ $category['slug'] }}">
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
            <input id="groups-price-range" class="w-full h-1 bg-surface-container-highest rounded-lg appearance-none cursor-pointer accent-primary" type="range" min="0" max="5000" step="50" value="5000" />
            <div class="flex justify-between items-center text-xs font-bold text-on-surface-variant">
                <span>٠ ر.س</span>
                <span>٥٠٠٠ ر.س</span>
            </div>
            <p class="text-sm text-primary font-bold">الحد الاعلى: <span id="groups-price-value">5,000</span> ر.س</p>
            <button id="groups-apply-price" type="button" class="w-full text-sm px-4 py-2 rounded-full bg-primary text-white font-bold hover:bg-primary-container transition-colors">تطبيق</button>
        </div>
    </div>
@endsection

@section('content')
    <section class="px-6 max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8 bg-surface-container-low/50 p-4 rounded-xl">
            <div>
                <h1 id="groups-page-title" class="text-2xl font-black font-headline text-primary">جميع المنتجات حسب التصنيفات</h1>
                <p class="text-on-surface-variant text-sm mt-1">عرض <span id="groups-products-count">{{ count($products) }}</span> منتج</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm text-on-surface-variant">ترتيب حسب:</span>
                <select id="groups-sort-order" dir="rtl" class="bg-white border-none focus:ring-0 text-sm font-bold text-primary cursor-pointer rounded-lg text-right pr-3" style="direction: rtl; text-align: right; text-align-last: right;">
                    <option>الترتيب الافتراضي</option>
                    <option>الأقل سعراً </option>
                    <option>الأعلى سعراً</option>
                    <option>وصلنا حديثاً</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 {{ $gridColumnsClass }} gap-8 rtl-product-grid" id="product-grid">
            @foreach ($products as $product)
                @php
                    $isOwnProduct = auth()->check() && (int) ($product['user_id'] ?? 0) === auth()->id();
                @endphp
                <x-product-card
                    class="product-card-groups"
                    data-product-card
                    data-order="{{ $loop->index }}"
                    data-category="{{ $product['category_slug'] }}"
                    data-price="{{ $product['price_value'] }}"
                    :title="$product['title']"
                    :image="$product['image']"
                    :category="$product['category']"
                    :description="$product['description'] ?? ''"
                    :price="$product['price']"
                    :old-price="$product['oldPrice']"
                    :rating="$product['rating_avg'] ?? 0"
                    :rating-count="$product['rating_count'] ?? 0"
                    :sales-count="$product['sales_count'] ?? 0"
                    tag="جديد"
                    badge-class="bg-primary/90 text-white"
                    :favorite-key="'groups-' . $loop->index . '-' . \Illuminate\Support\Str::slug($product['title'])"
                    :favorite-title="$product['title']"
                    :favorite-image="$product['image']"
                    :favorite-price="$product['price']"
                    :favorite-category="$product['category']"
                    :cart-item-id="'listing-' . $product['listing_id']"
                    :cart-listing-id="$product['listing_id']"
                    :cart-title="$product['title']"
                    :cart-image="$product['image']"
                    :cart-price="$product['price_value']"
                    :cart-price-label="$product['price']"
                    :cart-category="$product['category']"
                    :cart-buy-url="route('marketplace.buy', ['listing' => $product['listing_id']])"
                    :is-own-product="$isOwnProduct"
                    heading-tag="h2"
                />
            @endforeach
        </div>

        <div id="groups-empty-state" class="hidden mt-8 rounded-2xl border border-dashed border-outline-variant bg-surface-container-low p-8 text-center">
            <p id="groups-empty-title" class="text-lg font-bold text-primary">لا توجد منتجات مطابقة حالياً</p>
            <p id="groups-empty-description" class="text-sm text-on-surface-variant mt-2">جرّب تعديل الفلاتر للعثور على نتائج.</p>
        </div>

        <div class="mt-16 flex justify-center items-center gap-2" id="groups-pagination">
            <button id="groups-page-prev" class="w-10 h-10 flex items-center justify-center rounded-full border border-outline-variant hover:bg-surface-container transition-colors disabled:opacity-40 disabled:cursor-not-allowed" type="button"><span class="material-symbols-outlined text-sm">chevron_right</span></button>
            <div id="groups-page-numbers" class="flex items-center gap-2"></div>
            <button id="groups-page-next" class="w-10 h-10 flex items-center justify-center rounded-full border border-outline-variant hover:bg-surface-container transition-colors disabled:opacity-40 disabled:cursor-not-allowed" type="button"><span class="material-symbols-outlined text-sm">chevron_left</span></button>
        </div>
    </section>
@endsection

@push('page-scripts')
    <script>
        (function () {
            var filters = document.querySelectorAll('.category-filter-groups');
            var productCards = document.querySelectorAll('.product-card-groups');
            var productGrid = document.getElementById('product-grid');
            var priceRange = document.getElementById('groups-price-range');
            var priceValue = document.getElementById('groups-price-value');
            var applyButton = document.getElementById('groups-apply-price');
            var sortOrder = document.getElementById('groups-sort-order');
            var prevPageButton = document.getElementById('groups-page-prev');
            var nextPageButton = document.getElementById('groups-page-next');
            var pageNumbersContainer = document.getElementById('groups-page-numbers');
            var pagination = document.getElementById('groups-pagination');
            var pageTitle = document.getElementById('groups-page-title');
            var productsCount = document.getElementById('groups-products-count');
            var emptyState = document.getElementById('groups-empty-state');
            var emptyTitle = document.getElementById('groups-empty-title');
            var emptyDescription = document.getElementById('groups-empty-description');
            var categoryNames = @json($categoryNames);
            var currentCategory = '';
            var maxPrice = 5000;
            var currentSort = 'popular';
            var currentPage = 1;
            var pageSize = 3;

            function updatePageTitle() {
                if (!pageTitle) return;

                if (currentCategory && categoryNames[currentCategory]) {
                    pageTitle.textContent = categoryNames[currentCategory];
                    return;
                }

                pageTitle.textContent = 'جميع المنتجات حسب التصنيفات';
            }

            function updatePriceLabel() {
                if (!priceRange || !priceValue) return;
                priceValue.textContent = Number(priceRange.value).toLocaleString('en-US');
            }

            function getFilteredCards() {
                var filteredCards = Array.prototype.filter.call(productCards, function (card) {
                    var cardCategory = card.getAttribute('data-category') || '';
                    var cardPrice = Number(card.getAttribute('data-price') || '0');
                    var categoryMatches = currentCategory === '' || cardCategory === currentCategory;
                    var priceMatches = cardPrice <= maxPrice;
                    return categoryMatches && priceMatches;
                });

                return filteredCards.sort(function (leftCard, rightCard) {
                    var leftPrice = Number(leftCard.getAttribute('data-price') || '0');
                    var rightPrice = Number(rightCard.getAttribute('data-price') || '0');
                    var leftOrder = Number(leftCard.getAttribute('data-order') || '0');
                    var rightOrder = Number(rightCard.getAttribute('data-order') || '0');

                    if (currentSort === 'price-asc') {
                        return leftPrice - rightPrice;
                    }

                    if (currentSort === 'price-desc') {
                        return rightPrice - leftPrice;
                    }

                    if (currentSort === 'latest') {
                        return rightOrder - leftOrder;
                    }

                    return leftOrder - rightOrder;
                });
            }

            function updatePagination(filteredCards) {
                var totalPages = Math.max(1, Math.ceil(filteredCards.length / pageSize));
                if (currentPage > totalPages) {
                    currentPage = totalPages;
                }

                if (productsCount) {
                    productsCount.textContent = filteredCards.length.toLocaleString('ar-EG');
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
                            pageButton.classList.add('text-on-surface-variant', 'hover:bg-surface-container');
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

                if (filteredCards.length === 0 && emptyTitle && emptyDescription) {
                    var hasCategoryFilter = currentCategory !== '';
                    var hasPriceFilter = maxPrice < 5000;

                    if (hasPriceFilter && !hasCategoryFilter) {
                        emptyTitle.textContent = 'لا توجد منتجات ضمن نطاق السعر الحالي';
                        emptyDescription.textContent = 'ارفع الحد الأعلى للسعر ثم اضغط تطبيق لعرض نتائج أكثر.';
                    } else if (!hasPriceFilter && hasCategoryFilter) {
                        emptyTitle.textContent = 'لا توجد منتجات في هذا التصنيف حالياً';
                        emptyDescription.textContent = 'جرّب تصنيفًا آخر أو عد لاحقًا بعد إضافة منتجات جديدة.';
                    } else if (hasPriceFilter && hasCategoryFilter) {
                        emptyTitle.textContent = 'لا توجد منتجات مطابقة للتصنيف والسعر';
                        emptyDescription.textContent = 'وسّع نطاق السعر أو اختر تصنيفًا آخر للحصول على نتائج.';
                    } else {
                        emptyTitle.textContent = 'لا توجد منتجات مطابقة حالياً';
                        emptyDescription.textContent = 'جرّب تعديل الفلاتر للعثور على نتائج.';
                    }
                }

                if (pagination) {
                    pagination.classList.toggle('hidden', filteredCards.length === 0);
                }

                productCards.forEach(function (card) {
                    card.style.display = 'none';
                });

                var startIndex = (currentPage - 1) * pageSize;
                var endIndex = startIndex + pageSize;
                filteredCards.slice(startIndex, endIndex).forEach(function (card) {
                    card.style.display = 'flex';

                    if (productGrid) {
                        productGrid.appendChild(card);
                    }
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

                    currentPage = 1;
                    updatePageTitle();
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

            if (sortOrder) {
                sortOrder.addEventListener('change', function () {
                    var selectedIndex = sortOrder.selectedIndex;
                    currentSort = 'popular';

                    if (selectedIndex === 1) {
                        currentSort = 'price-asc';
                    } else if (selectedIndex === 2) {
                        currentSort = 'price-desc';
                    } else if (selectedIndex === 3) {
                        currentSort = 'latest';
                    }

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
            updatePageTitle();
            applyFilters();
        })();
    </script>
@endpush