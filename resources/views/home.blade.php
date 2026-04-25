@extends('layouts.main')

@section('title', config('app.name', 'ArabCart') . ' | وصلنا حديثاً')
@section('nav-active', 'home')


@php
    use App\Support\Catalog;

    $categories = Catalog::categories();

    $categoryNames = collect($categories)->pluck('name', 'slug')->all();

    $listings = \App\Models\Listing::query()
        ->where('is_active', true)
        ->whereNotNull('category_slug')
        ->whereNotNull('image_url')
        ->orderByDesc('sales_count')
        ->orderByDesc('rating_avg')
        ->latest()
        ->get()
        ->groupBy('category_slug');

    $products = collect(array_keys($categoryNames))
        ->flatMap(static function (string $categorySlug) use ($listings, $categoryNames) {
            return $listings->get($categorySlug, collect())
                ->take(3)
                ->map(static function (\App\Models\Listing $listing) use ($categoryNames): array {
                    $basePrice = (float) $listing->price;
                    $displayPrice = $listing->has_offer && $listing->offer_price
                        ? (float) $listing->offer_price
                        : $basePrice;
                    $categorySlug = (string) $listing->category_slug;

                    return [
                        'listing_id' => (int) $listing->id,
                        'user_id' => (int) $listing->user_id,
                        'category' => $categoryNames[$categorySlug] ?? $categorySlug,
                        'title' => $listing->title,
                        'description' => $listing->description,
                        'price' => number_format((float) $displayPrice) . ' ر.س',
                        'old_price' => $listing->has_offer && $listing->offer_price
                            ? number_format($basePrice) . ' ر.س'
                            : null,
                        'image' => route('marketplace.image', $listing),
                        'rating' => number_format((float) ($listing->rating_avg ?? 0), 1),
                        'sales_count' => (int) ($listing->sales_count ?? 0),
                    ];
                });
        })
        ->take(12)
        ->values()
        ->all();

    $productsDisplayLimit = 3;
    $isProductsExpandable = count($products) > $productsDisplayLimit;

    $heroImage = $products[0]['image'] ?? null;
    $featuredProductImages = collect($products)
        ->pluck('image')
        ->filter()
        ->take(4)
        ->values()
        ->all();
    $firstAddedProduct = \App\Models\Listing::query()
        ->where('is_active', true)
        ->whereNotNull('category_slug')
        ->whereNotNull('image_url')
        ->oldest('created_at')
        ->orderBy('id')
        ->first();
    $firstAddedProductImage = asset('images/new.png');

    $electronicsImage = Catalog::categoryCoverImage('electronics');
    $fashionImage = Catalog::categoryCoverImage('fashion');
    $perfumesImage = Catalog::categoryCoverImage('perfumes');
    $shoesImage = Catalog::categoryCoverImage('shoes');

    $sectionShellClass = 'bg-surface-container-low border border-outline-variant/10 shadow-[0_24px_80px_rgba(15,23,42,0.08)]';
    $sectionOrbClass = 'bg-white/35 ring-1 ring-white/40';
    $categoryCardClass = 'relative rounded-3xl overflow-hidden bg-surface-container-low border border-outline-variant/10 shadow-[0_24px_80px_rgba(15,23,42,0.08)] group cursor-pointer block';
    $categoryCaptionClass = 'absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent flex flex-col justify-end text-right';
@endphp

@push('page-styles')
    <style>
        body {
            background: linear-gradient(180deg, #f8f9fb 0%, #eef2f5 100%);
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
            @foreach ($categories as $category)
                <a class="flex items-center gap-3 p-3 rounded-xl text-slate-600 hover:bg-surface-container-high hover:text-primary transition-all group" href="{{ route('categories.show', ['slug' => $category['slug']]) }}">
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
        <form method="GET" action="{{ route('categories.index') }}" class="space-y-3">
            <input id="home-price-range" name="max_price" class="w-full h-1 bg-surface-container-highest rounded-lg appearance-none cursor-pointer accent-primary" type="range" min="0" max="5000" step="50" value="5000" />
            <div class="flex justify-between items-center text-xs font-bold text-on-surface-variant">
                <span>٠ ر.س</span>
                <span>٥٠٠٠ ر.س</span>
            </div>
            <p class="text-sm text-primary font-bold">الحد الاعلى: <span id="home-price-value">5,000</span> ر.س</p>
            <button type="submit" class="w-full text-sm px-4 py-2 rounded-full bg-primary text-white font-bold hover:bg-primary-container transition-colors">تطبيق</button>
        </form>
    </div>
@endsection

@section('content')
    <section class="page-container mb-16">
        <div class="relative w-full h-[500px] rounded-3xl overflow-hidden bg-slate-950">
            <img alt="Hero Offer" class="absolute inset-0 w-full h-full object-contain object-center bg-slate-950 opacity-100" src="{{ asset('images/home.png') }}" />
            <div class="absolute inset-0 bg-gradient-to-l from-blue-950/45 via-blue-900/25 to-transparent flex flex-col justify-center items-end px-12 text-right">
                <span class="bg-secondary text-on-secondary px-4 py-1 rounded-full text-xs font-bold mb-4">مجموعة حصرية</span>
                <h1 class="text-white text-5xl md:text-6xl font-extrabold mb-6 leading-tight font-headline">أناقتك تبدأ من هنا</h1>
                <p class="text-primary-fixed text-lg mb-8 max-w-md">اكتشف أحدث صيحات الموضة العالمية والمنتجات الفاخرة المختارة بعناية لتناسب ذوقك الرفيع.</p>
                @auth
                    <a href="{{ route('groups.index') }}" class="inline-flex items-center justify-center bg-secondary-container text-on-secondary-container px-10 py-4 rounded-full font-bold text-lg hover:opacity-90 transition-all scale-100 active:scale-95 shadow-lg">تسوق الآن</a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center bg-secondary-container text-on-secondary-container px-10 py-4 rounded-full font-bold text-lg hover:opacity-90 transition-all scale-100 active:scale-95 shadow-lg">سجّل وتسوق الآن</a>
                @endauth
            </div>
        </div>
    </section>

    <section class="page-container mb-16">
        <div class="flex justify-between items-end mb-8">
            <div class="text-right">
                <h2 class="section-title">التصنيفات المميزة</h2>
                <p class="text-on-surface-variant mt-2">تصفح مجموعاتنا حسب الفئة</p>
            </div>
            {{-- <a class="text-secondary font-bold hover:underline" href="{{ route('groups.index') }}">عرض الكل</a> --}}
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 h-auto md:h-[450px]">
            <a class="md:col-span-2 md:row-span-2 {{ $categoryCardClass }} min-h-[320px]" href="{{ route('categories.show', ['slug' => 'electronics']) }}">
                @if ($electronicsImage)
                    <img alt="Electronics" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" src="{{ $electronicsImage }}" />
                @else
                    <div class="absolute inset-0 flex items-center justify-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-7xl">devices</span>
                    </div>
                @endif
                <div class="{{ $categoryCaptionClass }} p-8">
                    <div class="inline-flex w-fit rounded-full bg-white/85 px-3 py-1 text-[10px] font-bold text-primary mb-4">التصنيفات المميزة</div>
                    <h3 class="text-white text-2xl font-bold font-headline">الإلكترونيات</h3>
                    <p class="text-white/80 text-sm">أحدث التقنيات بين يديك</p>
                </div>
            </a>
            <a class="{{ $categoryCardClass }} min-h-[200px]" href="{{ route('categories.show', ['slug' => 'fashion']) }}">
                @if ($fashionImage)
                    <img alt="Fashion" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" src="{{ $fashionImage }}" />
                @else
                    <div class="absolute inset-0 flex items-center justify-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-6xl">checkroom</span>
                    </div>
                @endif
                <div class="{{ $categoryCaptionClass }} p-6">
                    <h3 class="text-white text-xl font-bold font-headline">الأزياء</h3>
                </div>
            </a>
            <a class="{{ $categoryCardClass }} min-h-[200px]" href="{{ route('categories.show', ['slug' => 'perfumes']) }}">
                @if ($perfumesImage)
                    <img alt="Perfumes" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" src="{{ $perfumesImage }}" />
                @else
                    <div class="absolute inset-0 flex items-center justify-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-6xl">self_care</span>
                    </div>
                @endif
                <div class="{{ $categoryCaptionClass }} p-6">
                    <h3 class="text-white text-xl font-bold font-headline">العطور</h3>
                </div>
            </a>
            <a class="md:col-span-2 {{ $categoryCardClass }} min-h-[220px]" href="{{ route('categories.show', ['slug' => 'shoes']) }}">
                @if ($shoesImage)
                    <img alt="Shoes" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" src="{{ $shoesImage }}" />
                @else
                    <div class="absolute inset-0 flex items-center justify-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-6xl">footprint</span>
                    </div>
                @endif
                <div class="{{ $categoryCaptionClass }} p-6">
                    <h3 class="text-white text-2xl font-bold font-headline">الأحذية</h3>
                    <p class="text-white/80 text-sm">موديلات يومية وعصرية</p>
                </div>
            </a>
        </div>
    </section>

    <section class="page-container mb-16">
        <div class="rounded-[40px] p-8 md:p-10 {{ $sectionShellClass }} overflow-hidden relative">
            <div class="absolute top-0 right-0 w-64 h-64 {{ $sectionOrbClass }} rounded-full -mr-32 -mt-32 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 {{ $sectionOrbClass }} rounded-full -ml-24 -mb-24 pointer-events-none"></div>

            <div class="relative z-10">
                <div class="flex justify-between items-end mb-8 gap-4">
                    <div class="text-right">
                        <h2 class="section-title">منتجاتنا المختارة</h2>
                        <p class="text-on-surface-variant mt-2">الأفضل مبيعاً لهذا الأسبوع</p>
                    </div>
                    <div class="flex items-center gap-3">
                        @if ($isProductsExpandable)
                            <button id="home-products-toggle" class="text-secondary font-bold hover:underline inline-flex" type="button" data-expandable-listings-toggle data-expandable-listings-target="home-best-sellers-section" data-collapsed-label="عرض الكل" data-expanded-label="إخفاء" aria-expanded="false">عرض الكل</button>
                        @else
                            <a href="{{ route('marketplace.index') }}" class="text-secondary font-bold hover:underline inline-flex">عرض الكل</a>
                        @endif
                        <button id="home-filter-toggle" aria-controls="page-sidebar" aria-label="فتح لوحة التصفية" class="inline-flex lg:hidden items-center gap-2 px-4 py-2 rounded-full bg-surface-container-high text-primary font-bold shadow-sm hover:bg-surface-container transition-colors" type="button">
                            <span class="material-symbols-outlined">filter_list</span>
                            <span>تصفية النتائج</span>
                        </button>
                    </div>
                </div>

                @if ($products)
                    <div id="home-best-sellers-section" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 rtl-product-grid" data-expandable-listings-section data-expandable-listings-mode="best-sellers" data-expandable-listings-threshold="{{ $productsDisplayLimit }}">
                        @foreach ($products as $product)
                            @php
                                $isOwnProduct = auth()->check() && (int) ($product['user_id'] ?? 0) === auth()->id();
                                $isHidden = $isProductsExpandable && $loop->index >= $productsDisplayLimit;
                            @endphp
                            <x-product-card
                                class="product-card-groups {{ $isHidden ? 'hidden' : '' }}"
                                data-expandable-listings-item
                                :title="$product['title']"
                                :image="$product['image'] ?? null"
                                :category="$product['category']"
                                :description="$product['description'] ?? ''"
                                :price="$product['price']"
                                :old-price="!empty($product['old_price']) ? $product['old_price'] : null"
                                :rating="$product['rating'] ?? 0"
                                :sales-count="$product['sales_count'] ?? 0"
                                :favorite-key="'home-' . $loop->index . '-' . \Illuminate\Support\Str::slug($product['title'])"
                                :favorite-title="$product['title']"
                                :favorite-image="$product['image']"
                                :favorite-price="$product['price']"
                                :favorite-category="$product['category']"
                                :cart-item-id="'listing-' . $product['listing_id']"
                                :cart-listing-id="$product['listing_id']"
                                :cart-title="$product['title']"
                                :cart-image="$product['image']"
                                :cart-price="preg_replace('/[^0-9.]/', '', $product['price'])"
                                :cart-price-label="$product['price']"
                                :cart-category="$product['category']"
                                :cart-buy-url="route('marketplace.buy', ['listing' => $product['listing_id']])"
                                :is-own-product="$isOwnProduct"
                            />
                        @endforeach
                    </div>
                @else
                    <div class="flex min-h-[320px] items-center justify-center rounded-[28px] border border-dashed border-outline-variant/20 bg-white/70 px-6 text-center">
                        <div class="max-w-md py-10">
                            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-surface-container-high text-primary">
                                <span class="material-symbols-outlined text-3xl">inventory_2</span>
                            </div>
                            <h3 class="text-2xl font-black text-primary mb-3">لا توجد منتجات مختارة بعد</h3>
                            <p class="text-sm text-on-surface-variant leading-7">أضف أول منتج ليظهر هنا ضمن الاختيارات الأفضل مبيعاً.</p>
                            <a href="{{ route('marketplace.index') }}" class="mt-6 inline-flex items-center justify-center rounded-full bg-primary px-6 py-3 font-bold text-white transition-colors hover:bg-primary-container">
                                تصفح السوق
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </section>
    
    @include('components.marketplace-listings-section', ['mode' => 'best-sellers', 'limit' => 3])
    @include('components.marketplace-listings-section', ['mode' => 'home', 'limit' => 3])
    
    <section class="page-container mb-16">
        <div class="{{ $sectionShellClass }} rounded-[40px] p-8 md:p-16 flex flex-col md:flex-row-reverse items-center justify-between gap-12 overflow-hidden relative">
            <div class="absolute top-0 right-0 w-64 h-64 {{ $sectionOrbClass }} rounded-full -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 {{ $sectionOrbClass }} rounded-full -ml-24 -mb-24"></div>
            <div class="w-full md:w-1/2 text-right relative z-10">
                <h2 class="text-4xl font-extrabold text-primary mb-6 font-headline">منتج جديد لهذا العام</h2>
                <p class="text-on-surface-variant text-lg mb-8 leading-relaxed">اكتشف أول إضافة هذا العام وابدأ البيع أو العرض من صفحة إنشاء منتج بسرعة وسهولة.</p>
                <div class="flex flex-row-reverse gap-4">
                    @auth
                        <a href="{{ route('marketplace.create') }}" class="bg-primary text-white px-8 py-3 rounded-full font-bold hover:bg-primary-container transition-all">أضف منتجك الآن</a>
                    @else
                        <a href="{{ route('login') }}" class="bg-primary text-white px-8 py-3 rounded-full font-bold hover:bg-primary-container transition-all">سجّل وأضف منتجك</a>
                    @endauth
                </div>
            </div>

            <div class="w-full md:w-1/2 relative z-10 bg-white/60 backdrop-blur-md rounded-[32px] p-4 border border-white/60 shadow-xl">
                @if ($firstAddedProductImage)
                    <img alt="First Added Product" class="w-full h-[340px] md:h-[420px] object-cover rounded-[24px] shadow-2xl" src="{{ $firstAddedProductImage }}" />
                @else
                    <div class="w-full h-[340px] md:h-[420px] rounded-[24px] shadow-2xl bg-gradient-to-br from-surface-container-high to-surface-container-low flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-7xl">image_not_supported</span>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="page-container mb-16">
        @if (session('newsletter_subscribed'))
            <div class="bg-primary p-12 rounded-[40px] text-center text-white">
                <div class="max-w-2xl mx-auto">
                    <h2 class="text-3xl font-bold mb-4 font-headline">تم تسجيل اشتراكك بنجاح</h2>
                    <p class="text-primary-fixed opacity-90 text-lg">ستصلك أحدث العروض والمجموعات الحصرية قريبًا.</p>
                </div>
            </div>
        @else
            <div class="{{ $sectionShellClass }} rounded-[40px] p-8 md:p-16 overflow-hidden relative">
                <div class="absolute top-0 right-0 w-64 h-64 {{ $sectionOrbClass }} rounded-full -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 {{ $sectionOrbClass }} rounded-full -ml-24 -mb-24"></div>
                <div class="relative z-10 max-w-4xl mx-auto bg-primary rounded-[2.5rem] p-10 md:p-16 text-center text-white overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                    <div class="relative z-10">
                        <h2 class="text-3xl md:text-4xl font-black mb-4 font-headline">كن أول من يعرف</h2>
                        <p class="text-primary-fixed opacity-80 mb-10 text-lg">اشترك في نشرتنا البريدية لتصلك أحدث المجموعات الحصرية والعروض الخاصة قبل الجميع.</p>
                        <form class="flex flex-col sm:flex-row-reverse gap-4 max-w-lg mx-auto" method="POST" action="{{ route('newsletter.subscribe') }}">
                            @csrf
                            <input class="flex-1 bg-white/10 border-none rounded-full px-6 py-4 text-white placeholder:text-white/50 focus:ring-2 focus:ring-secondary-container" name="email" placeholder="بريدك الإلكتروني" type="email" required />
                            @auth
                                <button class="bg-secondary-container text-on-secondary-container px-10 py-4 rounded-full font-bold hover:scale-105 transition-transform" type="submit">اشترك الآن</button>
                            @else
                                <a href="{{ route('login') }}" class="bg-secondary-container text-on-secondary-container px-10 py-4 rounded-full font-bold hover:scale-105 transition-transform">سجّل واشترك الآن</a>
                            @endauth
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </section>
@endsection

@push('page-scripts')
    <script>
        (function () {
            var range = document.getElementById('home-price-range');
            var output = document.getElementById('home-price-value');
            var filterToggle = document.getElementById('home-filter-toggle');
            var sidebar = document.getElementById('page-sidebar');
            var filterBackdrop = null;

            function isMobileViewport() {
                return window.matchMedia('(max-width: 1023px)').matches;
            }

            function ensureBackdrop() {
                if (filterBackdrop) {
                    return filterBackdrop;
                }

                filterBackdrop = document.createElement('div');
                filterBackdrop.id = 'home-filter-backdrop';
                filterBackdrop.className = 'fixed inset-0 bg-black/40 z-40 hidden lg:hidden';
                document.body.appendChild(filterBackdrop);
                filterBackdrop.addEventListener('click', closeFilterDrawer);

                return filterBackdrop;
            }

            function openFilterDrawer() {
                if (!sidebar) return;

                var backdrop = ensureBackdrop();
                sidebar.dataset.originalClass = sidebar.dataset.originalClass || sidebar.className;
                sidebar.className = 'fixed top-24 bottom-4 inset-x-4 z-50 flex flex-col rounded-2xl border border-outline-variant/20 bg-surface-container-low shadow-2xl overflow-y-auto';
                sidebar.style.display = 'flex';
                backdrop.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeFilterDrawer() {
                if (!sidebar || !sidebar.dataset.originalClass) return;

                sidebar.className = sidebar.dataset.originalClass;
                sidebar.style.display = '';

                if (filterBackdrop) {
                    filterBackdrop.classList.add('hidden');
                }

                document.body.classList.remove('overflow-hidden');
            }

            function toggleFilterDrawer() {
                if (!sidebar || !filterToggle) return;

                if (isMobileViewport()) {
                    if (filterBackdrop && !filterBackdrop.classList.contains('hidden')) {
                        closeFilterDrawer();
                    } else {
                        openFilterDrawer();
                    }
                    return;
                }

                sidebar.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }

            if (filterToggle) {
                filterToggle.addEventListener('click', function () {
                    toggleFilterDrawer();
                });
            }

            if (sidebar) {
                sidebar.addEventListener('click', function (event) {
                    if (event.target.closest('.category-filter') || event.target.closest('button[type="submit"]')) {
                        if (isMobileViewport()) {
                            closeFilterDrawer();
                        }
                    }
                });
            }

            window.addEventListener('resize', function () {
                if (!isMobileViewport()) {
                    closeFilterDrawer();
                }
            });

            window.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeFilterDrawer();
                }
            });

            function syncExpandableButtons(section, expanded) {
                var buttons = getExpandableButtonsForSection(section);

                buttons.forEach(function (button) {
                    var collapsedLabel = button.getAttribute('data-collapsed-label') || 'عرض الكل';
                    var expandedLabel = button.getAttribute('data-expanded-label') || 'إخفاء';

                    button.setAttribute('aria-expanded', expanded ? 'true' : 'false');
                    button.textContent = expanded ? expandedLabel : collapsedLabel;
                });
            }

            function getExpandableButtonsForSection(section) {
                var localButtons = Array.prototype.slice.call(section.querySelectorAll('[data-expandable-listings-toggle]'));
                var sectionId = section.getAttribute('id');

                if (!sectionId) {
                    return localButtons;
                }

                var targetedButtons = Array.prototype.slice.call(document.querySelectorAll('[data-expandable-listings-toggle][data-expandable-listings-target="' + sectionId + '"]'));

                return localButtons.concat(targetedButtons.filter(function (button) {
                    return localButtons.indexOf(button) === -1;
                }));
            }

            function resolveExpandableSectionForButton(button) {
                var targetId = button.getAttribute('data-expandable-listings-target');

                if (targetId) {
                    return document.getElementById(targetId);
                }

                return button.closest('[data-expandable-listings-section]');
            }

            function setExpandableSectionState(section, expanded) {
                var threshold = Number(section.getAttribute('data-expandable-listings-threshold') || '4');
                var items = section.querySelectorAll('[data-expandable-listings-item]');
                var canExpand = items.length > threshold;

                if (!canExpand) {
                    expanded = false;
                }

                items.forEach(function (item, index) {
                    item.classList.toggle('hidden', canExpand && !expanded && index >= threshold);
                });

                section.dataset.expanded = expanded ? 'true' : 'false';
                syncExpandableButtons(section, expanded);
            }

            function syncExpandableButtonVisibility(section) {
                var buttons = getExpandableButtonsForSection(section);

                buttons.forEach(function (button) {
                    button.classList.remove('hidden');
                    button.disabled = false;
                });
            }

            function initExpandableSections() {
                var sections = document.querySelectorAll('[data-expandable-listings-section]');

                sections.forEach(function (section) {
                    var buttons = getExpandableButtonsForSection(section);

                    if (!buttons.length) {
                        return;
                    }

                    setExpandableSectionState(section, false);
                    syncExpandableButtonVisibility(section);
                });
            }

            initExpandableSections();

            document.addEventListener('click', function (event) {
                var toggleButton = event.target.closest('[data-expandable-listings-toggle]');
                if (!toggleButton) {
                    return;
                }

                var section = resolveExpandableSectionForButton(toggleButton);
                if (!section) {
                    return;
                }

                event.preventDefault();

                if (toggleButton.disabled) {
                    return;
                }

                var threshold = Number(section.getAttribute('data-expandable-listings-threshold') || '4');
                var items = section.querySelectorAll('[data-expandable-listings-item]');

                if (items.length <= threshold) {
                    return;
                }

                var isExpanded = section.dataset.expanded === 'true';
                setExpandableSectionState(section, !isExpanded);
            });

            if (range && output) {
                output.textContent = Number(range.value).toLocaleString('en-US');
                range.addEventListener('input', function () {
                    output.textContent = Number(range.value).toLocaleString('en-US');
                });
            }
        })();
    </script>

    <button class="fixed bottom-8 left-8 w-14 h-14 bg-secondary-container text-on-secondary-container rounded-full shadow-2xl flex items-center justify-center z-50 md:hidden hover:scale-110 active:scale-95 transition-all" type="button">
        <span class="material-symbols-outlined text-3xl">chat_bubble</span>
    </button>
@endpush
