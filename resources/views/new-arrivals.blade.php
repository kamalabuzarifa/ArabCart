@extends('layouts.main')

@section('title', config('app.name', 'ArabCart') . ' | المتجر الرفيع')
@section('nav-active', 'new-arrivals')

@php
    use App\Support\Catalog;
    
    $categories = Catalog::categories();
    $categoryNames = collect($categories)->pluck('name', 'slug')->all();

    $allProducts = \App\Models\Listing::query()
        ->where('is_active', true)
        ->whereNotNull('category_slug')
        ->whereNotNull('image_url')
        ->latest()
        ->take(9)
        ->get()
        ->map(static function (\App\Models\Listing $listing) use ($categoryNames): array {
            $categorySlug = (string) $listing->category_slug;
            $basePrice = (float) $listing->price;
            $displayPrice = $listing->has_offer && $listing->offer_price
                ? (float) $listing->offer_price
                : $basePrice;

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
                'price' => (int) round($displayPrice),
                'old_price' => $listing->has_offer && $listing->offer_price
                    ? (int) round($basePrice)
                    : null,
                'image' => route('marketplace.image', $listing),
            ];
        })
        ->all();

    $heroImage = $allProducts[0]['image'] ?? null;
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
            <a class="flex items-center gap-3 p-3 rounded-xl bg-primary text-white font-bold rounded-xl category-filter" href="#" data-category="">
                <span class="material-symbols-outlined text-xl">apps</span>
                <span>الكل</span>
            </a>
            @foreach ($categories as $category)
                <a class="flex items-center gap-3 p-3 rounded-xl text-slate-600 hover:bg-surface-container-high hover:text-primary transition-all category-filter" href="#" data-category="{{ $category['slug'] }}">
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
            <input id="new-arrivals-price-range" class="w-full h-1 bg-surface-container-highest rounded-lg appearance-none cursor-pointer accent-primary" type="range" min="0" max="5000" step="50" value="5000" />
            <div class="flex justify-between items-center text-xs font-bold text-on-surface-variant">
                <span>٠ ر.س</span>
                <span>٥٠٠٠ ر.س</span>
            </div>
            <p class="text-sm text-primary font-bold">الحد الاعلى: <span id="new-arrivals-price-value">5,000</span> ر.س</p>
            <button id="new-arrivals-apply-price" type="button" class="w-full text-sm px-4 py-2 rounded-full bg-primary text-white font-bold hover:bg-primary-container transition-colors">تطبيق</button>
        </div>
    </div>
@endsection

@section('content')
    <section class="page-container mb-16">
        <div class="relative w-full h-[500px] rounded-3xl overflow-hidden">
            <img alt="New Collection Hero" class="absolute inset-0 w-full h-full object-cover object-center opacity-100" src="{{ asset('images/buycart.jpg') }}" />
            <div class="absolute inset-0 flex flex-col justify-center items-end px-12 text-right">
                @auth
                    <a href="{{ route('groups.index') }}" class="absolute left-[60px] bottom-[70px] inline-flex items-center justify-center bg-[#FFC107] text-slate-900 px-10 py-4 rounded-full font-bold text-lg hover:opacity-90 transition-all scale-100 active:scale-95 shadow-lg">تسوق الآن</a>
                @else
                    <a href="{{ route('login') }}" class="absolute left-[60px] bottom-[70px] inline-flex items-center justify-center bg-[#FFC107] text-slate-900 px-10 py-4 rounded-full font-bold text-lg hover:opacity-90 transition-all scale-100 active:scale-95 shadow-lg">سجّل وتسوق الآن</a>
                @endauth
            </div>
        </div>
    </section>

    <section class="px-6 md:px-10 pb-20" id="featured-products">
        <div class="rounded-[40px] p-8 md:p-10 bg-surface-container-low border border-outline-variant/10 shadow-[0_24px_80px_rgba(15,23,42,0.08)] overflow-hidden relative">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/35 ring-1 ring-white/40 rounded-full -mr-32 -mt-32 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/35 ring-1 ring-white/40 rounded-full -ml-24 -mb-24 pointer-events-none"></div>

            <div class="relative z-10">
                <div class="flex items-end justify-between mb-10 gap-4">
                    <div class="text-right">
                        <h2 class="text-3xl font-black text-primary font-headline mb-2">وصلنا حديثاً</h2>
                        <div class="h-1.5 w-20 bg-secondary rounded-full ml-auto mb-2"></div>
                        <p class="text-sm text-on-surface-variant">منتجات جديدة وصلت الآن</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 {{ $gridColumnsClass }} gap-8 rtl-product-grid" id="products-grid">
                    @foreach ($allProducts as $product)
                        @php
                            $isOwnProduct = auth()->check() && (int) ($product['user_id'] ?? 0) === auth()->id();
                        @endphp
                        <x-product-card
                            class="product-card-groups"
                            data-category="{{ $product['category_slug'] }}"
                            data-price="{{ $product['price'] }}"
                            :title="$product['title']"
                            :image="$product['image']"
                            :category="$product['category_name']"
                            :description="$product['description']"
                            :price="number_format($product['price']) . ' ر.س'"
                            :old-price="!empty($product['old_price']) ? number_format($product['old_price']) . ' ر.س' : null"
                            :rating="$product['rating_avg'] ?? 0"
                            :rating-count="$product['rating_count'] ?? 0"
                            :sales-count="$product['sales_count'] ?? 0"
                            tag="جديد"
                            badge-class="bg-primary/90 text-white"
                            :favorite-key="'new-arrivals-' . $loop->index . '-' . \Illuminate\Support\Str::slug($product['title'])"
                            :favorite-title="$product['title']"
                            :favorite-image="$product['image']"
                            :favorite-price="number_format($product['price']) . ' ر.س'"
                            :favorite-category="$product['category_name']"
                            :cart-item-id="'listing-' . $product['listing_id']"
                            :cart-listing-id="$product['listing_id']"
                            :cart-title="$product['title']"
                            :cart-image="$product['image']"
                            :cart-price="$product['price']"
                            :cart-price-label="number_format($product['price']) . ' ر.س'"
                            :cart-category="$product['category_name']"
                            :cart-buy-url="route('marketplace.buy', ['listing' => $product['listing_id']])"
                            :is-own-product="$isOwnProduct"
                            heading-tag="h2"
                        />
                    @endforeach
                </div>

                <div id="new-arrivals-empty-state" class="hidden mt-8 rounded-2xl border border-dashed border-outline-variant bg-surface-container-low p-8 text-center">
                    <p id="new-arrivals-empty-title" class="text-lg font-bold text-primary">لا توجد منتجات مطابقة حالياً</p>
                    <p id="new-arrivals-empty-description" class="text-sm text-on-surface-variant mt-2">جرّب تعديل التصفية للعثور على نتائج.</p>
                </div>

                <div class="mt-16 flex justify-center items-center gap-4" id="new-arrivals-pagination">
                    <button id="new-arrivals-page-prev" class="w-10 h-10 rounded-full border border-outline-variant flex items-center justify-center hover:bg-surface-container-high cubic-transition disabled:opacity-40 disabled:cursor-not-allowed" type="button"><span class="material-symbols-outlined">chevron_right</span></button>
                    <div id="new-arrivals-page-numbers" class="flex items-center gap-2"></div>
                    <button id="new-arrivals-page-next" class="w-10 h-10 rounded-full border border-outline-variant flex items-center justify-center hover:bg-surface-container-high cubic-transition disabled:opacity-40 disabled:cursor-not-allowed" type="button"><span class="material-symbols-outlined">chevron_left</span></button>
                </div>
            </div>
        </div>
    </section>

    {{-- <section class="px-6 md:px-10 mb-20" id="newsletter">
        <div class="rounded-[40px] p-8 md:p-10 bg-surface-container-low border border-outline-variant/10 shadow-[0_24px_80px_rgba(15,23,42,0.08)] overflow-hidden relative">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/35 ring-1 ring-white/40 rounded-full -mr-32 -mt-32 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/35 ring-1 ring-white/40 rounded-full -ml-24 -mb-24 pointer-events-none"></div>

            <div class="relative z-10 max-w-4xl mx-auto bg-primary rounded-[2.5rem] p-10 md:p-16 text-center text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="relative z-10">
                    <h2 class="text-3xl md:text-4xl font-black mb-4 font-headline">كن أول من يعرف</h2>
                    <p class="text-primary-fixed opacity-80 mb-10 text-lg">اشترك في نشرتنا البريدية لتصلك أحدث المجموعات الحصرية والعروض الخاصة قبل الجميع.</p>
                    <form class="flex flex-col sm:flex-row-reverse gap-4 max-w-lg mx-auto">
                        <input class="flex-1 bg-white/10 border-none rounded-full px-6 py-4 text-white placeholder:text-white/50 focus:ring-2 focus:ring-secondary-container" placeholder="بريدك الإلكتروني" type="email" />
                        <button class="bg-secondary-container text-on-secondary-container px-10 py-4 rounded-full font-bold hover:scale-105 transition-transform" type="submit">اشترك الآن</button>
                    </form>
                </div>
            </div>
        </div>
    </section> --}}
@endsection

@push('page-scripts')
    <script>
        (function () {
            var filters = document.querySelectorAll('.category-filter');
            var productCards = document.querySelectorAll('.product-card');
            var priceRange = document.getElementById('new-arrivals-price-range');
            var priceValue = document.getElementById('new-arrivals-price-value');
            var applyButton = document.getElementById('new-arrivals-apply-price');
            var prevPageButton = document.getElementById('new-arrivals-page-prev');
            var nextPageButton = document.getElementById('new-arrivals-page-next');
            var pageNumbersContainer = document.getElementById('new-arrivals-page-numbers');
            var pagination = document.getElementById('new-arrivals-pagination');
            var emptyState = document.getElementById('new-arrivals-empty-state');
            var emptyTitle = document.getElementById('new-arrivals-empty-title');
            var emptyDescription = document.getElementById('new-arrivals-empty-description');
            var currentCategory = '';
            var maxPrice = 5000;
            var currentPage = 1;
            var pageSize = 3;

            function updatePriceLabel() {
                if (!priceRange || !priceValue) return;
                priceValue.textContent = Number(priceRange.value).toLocaleString('en-US');
            }

            function applyFilters() {
                var filteredCards = [];

                productCards.forEach(function (card) {
                    var cardCategory = card.getAttribute('data-category') || '';
                    var cardPrice = Number(card.getAttribute('data-price') || '0');
                    var categoryMatches = currentCategory === '' || cardCategory === currentCategory;
                    var priceMatches = cardPrice <= maxPrice;
                    if (categoryMatches && priceMatches) {
                        filteredCards.push(card);
                    }
                });

                var totalPages = Math.max(1, Math.ceil(filteredCards.length / pageSize));
                if (currentPage > totalPages) {
                    currentPage = totalPages;
                }

                if (emptyState) {
                    emptyState.classList.toggle('hidden', filteredCards.length > 0);
                }

                if (pagination) {
                    pagination.classList.toggle('hidden', filteredCards.length === 0);
                }

                if (filteredCards.length === 0 && emptyTitle && emptyDescription) {
                    var hasCategoryFilter = currentCategory !== '';
                    var hasPriceFilter = maxPrice < 5000;

                    if (hasPriceFilter && !hasCategoryFilter) {
                        emptyTitle.textContent = 'لا توجد منتجات ضمن نطاق السعر الحالي';
                        emptyDescription.textContent = 'ارفع الحد الأعلى للسعر ثم اضغط تطبيق لعرض نتائج أكثر.';
                    } else if (!hasPriceFilter && hasCategoryFilter) {
                        emptyTitle.textContent = 'لا توجد منتجات في هذا التصنيف حالياً';
                        emptyDescription.textContent = 'اختر تصنيفًا آخر أو عد لاحقًا بعد إضافة منتجات جديدة.';
                    } else if (hasPriceFilter && hasCategoryFilter) {
                        emptyTitle.textContent = 'لا توجد منتجات مطابقة للتصنيف والسعر';
                        emptyDescription.textContent = 'وسّع نطاق السعر أو اختر تصنيفًا آخر للحصول على نتائج.';
                    } else {
                        emptyTitle.textContent = 'لا توجد منتجات مطابقة حالياً';
                        emptyDescription.textContent = 'جرّب تعديل التصفية للعثور على نتائج.';
                    }
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

                productCards.forEach(function (card) {
                    card.style.display = 'none';
                });

                var startIndex = (currentPage - 1) * pageSize;
                var endIndex = startIndex + pageSize;
                filteredCards.slice(startIndex, endIndex).forEach(function (card) {
                    card.style.display = 'flex';
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
            applyFilters();
        })();
    </script>
@endpush
