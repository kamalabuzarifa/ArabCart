@props([
    'mode' => 'latest',
    'limit' => 4,
])

@php
    use App\Models\Listing;
    use App\Support\Catalog;

    $title = 'الأحدث';
    $subtitle = 'منتجات أضيفت حديثًا ';
    $emptyTitle = 'لا توجد منتجات بعد';
    $emptyMessage = 'لا توجد منتجات متاحة في هذا القسم الآن.';
    $sectionClass = 'bg-surface-container-low border border-outline-variant/10 shadow-[0_24px_80px_rgba(15,23,42,0.08)]';
    $categoryNames = collect(Catalog::categories())
        ->mapWithKeys(static fn (array $category): array => [$category['slug'] => $category['name']])
        ->all();

    $query = Listing::query()
        ->where('is_active', true)
        ->whereNotNull('image_url')
        ->with('user:id,name');

    if ($mode === 'offers') {
        $title = 'منتجات مناسبة للعروض';
        $subtitle = 'منتجات مختارة بسعر مناسب للشراء السريع';
        $emptyTitle = 'لا توجد عروض حالياً';
        $emptyMessage = 'لا توجد منتجات مطابقة للعروض الآن.';
        $sectionClass = 'bg-surface-container-low border border-outline-variant/10 shadow-[0_24px_80px_rgba(15,23,42,0.08)]';
        $query->where(function ($sub): void {
            $sub->where(function ($q): void {
                $q->where('has_offer', true)
                    ->whereNotNull('offer_price')
                    ->where('offer_price', '<=', 1500);
            })->orWhere(function ($q): void {
                $q->where(function ($base): void {
                    $base->where('has_offer', false)
                        ->orWhereNull('offer_price');
                })->where('price', '<=', 1500);
            });
        })->orderByRaw('CASE WHEN has_offer = 1 AND offer_price IS NOT NULL THEN offer_price ELSE price END ASC');
    } elseif ($mode === 'top-rated') {
        $title = 'الأعلى تقييمًا';
        $subtitle = 'منتجات موصى بها حسب التقييم وعدد عمليات الشراء';
        $emptyTitle = 'لا توجد منتجات الأعلى تقييماً بعد';
        $emptyMessage = 'لا توجد منتجات لديها تقييمات كافية لعرضها هنا الآن.';
        $sectionClass = 'bg-surface-container-low border border-outline-variant/10 shadow-[0_24px_80px_rgba(15,23,42,0.08)]';
        $query->where('rating_count', '>', 0)
            ->orderByDesc('rating_avg')
            ->orderByDesc('sales_count');
    } elseif ($mode === 'best-sellers') {
        $title = 'الأكثر مبيعًا';
        $subtitle = 'منتجات يفضّلها المشترون حسب عدد عمليات الشراء';
        $emptyTitle = 'لا توجد منتجات الأكثر مبيعًا بعد';
        $emptyMessage = 'لا توجد منتجات لديها مبيعات كافية لعرضها في هذا القسم الآن.';
        $sectionClass = 'bg-surface-container-low border border-outline-variant/10 shadow-[0_24px_80px_rgba(15,23,42,0.08)]';
        $query->where('sales_count', '>', 0)
            ->orderByDesc('sales_count')
            ->orderByDesc('rating_avg')
            ->latest();
    } elseif ($mode === 'groups') {
        $title = 'منتجات ضمن التصنيفات';
        $subtitle = 'منتجات منشورة ومصنفة لتصفح أسهل';
        $emptyTitle = 'لا توجد منتجات مصنفة حالياً';
        $emptyMessage = 'لم يتم العثور على منتجات داخل التصنيفات المتاحة الآن.';
        $sectionClass = 'bg-surface-container-low border border-outline-variant/10 shadow-[0_24px_80px_rgba(15,23,42,0.08)]';
        $query->whereNotNull('category_slug')->latest();
    } elseif ($mode === 'new-arrivals') {
        $title = 'وصل حديثًا من المستخدمين';
        $subtitle = 'أحدث المنتجات المنشورة في السوق';
        $emptyTitle = 'لا توجد منتجات حديثة بعد';
        $emptyMessage = 'لا توجد منتجات جديدة منشورة حتى الآن.';
        $sectionClass = 'bg-surface-container-low border border-outline-variant/10 shadow-[0_24px_80px_rgba(15,23,42,0.08)]';
        $query->latest()->limit(12);
    } elseif ($mode === 'home') {
        $query->latest()->limit(9);
    } else {
        $query->latest();
    }

    $userListings = $query->get();
    $isExpandable = $userListings->count() > $limit;
    $gridColumnsClass = (int) $limit === 3 ? 'lg:grid-cols-3' : 'lg:grid-cols-4';
@endphp

<section class="page-container mb-16" data-expandable-listings-section data-expandable-listings-mode="{{ $mode }}" data-expandable-listings-threshold="{{ $limit }}">
    <div class="rounded-[40px] p-8 md:p-10 {{ $sectionClass }} overflow-hidden relative">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/35 ring-1 ring-white/40 rounded-full -mr-32 -mt-32 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/35 ring-1 ring-white/40 rounded-full -ml-24 -mb-24 pointer-events-none"></div>

        <div class="relative z-10">
            <div class="flex justify-between items-end mb-8 gap-4">
                <div class="text-right">
                    <h2 class="section-title mb-1">{{ $title }}</h2>
                    <p class="page-subtitle">{{ $subtitle }}</p>
                </div>
                @if ($isExpandable)
                    <button
                        class="text-secondary font-bold hover:underline"
                        type="button"
                        data-expandable-listings-toggle
                        aria-expanded="false"
                        data-collapsed-label="عرض الكل"
                        data-expanded-label="إخفاء"
                    >
                        عرض الكل
                    </button>
                @else
                    <a href="{{ route('marketplace.index') }}" class="text-secondary font-bold hover:underline">عرض الكل</a>
                @endif
            </div>

            @if ($userListings->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 {{ $gridColumnsClass }} gap-8 rtl-product-grid">
                    @foreach ($userListings as $listing)
                        @php
                            $isHidden = $loop->index >= $limit;
                        @endphp
                        <article class="group bg-surface-container-lowest rounded-xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-transform duration-300 hover:-translate-y-2 flex flex-col h-full {{ $isHidden ? 'hidden' : '' }}" data-expandable-listings-item>
                            <div class="relative aspect-[4/5] overflow-hidden">
                                @if ($listing->has_offer && $listing->offer_price && (float) $listing->price > 0)
                                    @php
                                        $discountPercent = (int) round((((float) $listing->price - (float) $listing->offer_price) / (float) $listing->price) * 100);
                                    @endphp
                                    <span class="absolute top-4 right-4 z-10 inline-flex items-center rounded-full bg-error text-white text-[10px] px-2 py-1 font-bold">عرض -{{ $discountPercent }}%</span>
                                @endif
                                <img
                                    alt="{{ $listing->title }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                    src="{{ route('marketplace.image', $listing) }}"
                                    loading="lazy"
                                />
                                <button class="absolute bottom-4 left-4 p-2 bg-white/90 backdrop-blur-sm rounded-full shadow-lg opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-300" type="button" data-favorite-toggle data-favorite-key="marketplace-section-{{ $mode }}-{{ $loop->index }}-{{ \Illuminate\Support\Str::slug($listing->title) }}" data-favorite-title="{{ $listing->title }}" data-favorite-image="{{ route('marketplace.image', $listing) }}" data-favorite-price="{{ number_format((float) ($listing->has_offer && $listing->offer_price ? $listing->offer_price : $listing->price)) }} ر.س" data-favorite-category="{{ $categoryNames[$listing->category_slug] ?? $listing->category_slug }}" aria-pressed="false" aria-label="إضافة إلى المفضلة">
                                    <span class="material-symbols-outlined text-primary">favorite_border</span>
                                </button>
                            </div>
                            <div class="p-6 flex flex-col flex-grow text-right">
                                <h3 class="text-lg font-bold text-primary mb-4 leading-tight group-hover:text-secondary transition-colors line-clamp-2">{{ $listing->title }}</h3>
                                @if (!empty($listing->description))
                                    <p class="text-sm text-on-surface-variant mb-3 line-clamp-2">{{ $listing->description }}</p>
                                @endif
                                <div class="mb-3 flex items-center justify-between text-xs text-on-surface-variant">
                                    <div class="inline-flex items-center gap-1 text-amber-500">
                                        @php
                                            $roundedRating = (int) round((float) ($listing->rating_avg ?? 0));
                                        @endphp
                                        @for ($i = 1; $i <= 5; $i++)
                                            <span class="material-symbols-outlined text-base" style="font-variation-settings: 'FILL' {{ $i <= $roundedRating ? 1 : 0 }};">star</span>
                                        @endfor
                                        <span class="text-on-surface text-[11px] font-bold">{{ number_format((float) ($listing->rating_avg ?? 0), 1) }}</span>
                                    </div>
                                    <span>{{ (int) ($listing->sales_count ?? 0) }} عملية</span>
                                </div>
                                @if ($listing->category_slug)
                                    <p class="text-xs font-bold text-on-surface-variant mb-2">{{ $categoryNames[$listing->category_slug] ?? $listing->category_slug }}</p>
                                @endif
                                <div class="mt-auto flex items-center justify-between">
                                    <div class="flex flex-col">
                                        @if ($listing->has_offer && $listing->offer_price)
                                            <span class="text-xl font-black text-error">{{ number_format((float) $listing->offer_price) }} ر.س</span>
                                            <p class="text-[11px] text-on-surface-variant line-through">{{ number_format((float) $listing->price) }} ر.س</p>
                                        @else
                                            <span class="text-xl font-black text-primary">{{ number_format((float) $listing->price) }} ر.س</span>
                                        @endif
                                    </div>
                                    @auth
                                        @if ((int) $listing->user_id === auth()->id())
                                            <button class="bg-surface-container-high text-on-surface-variant p-3 rounded-full flex items-center justify-center cursor-not-allowed opacity-70" type="button" disabled aria-disabled="true" aria-label="هذا المنتج ملكك">
                                                <span class="material-symbols-outlined">shopping_cart</span>
                                            </button>
                                        @else
                                            <button class="bg-primary text-white p-3 rounded-full hover:bg-primary-container transition-colors flex items-center justify-center" type="button" data-cart-add data-cart-item-id="listing-{{ $listing->id }}" data-cart-listing-id="{{ $listing->id }}" data-cart-title="{{ $listing->title }}" data-cart-description="{{ $listing->description }}" data-cart-image="{{ route('marketplace.image', $listing) }}" data-cart-price="{{ (float) ($listing->has_offer && $listing->offer_price ? $listing->offer_price : $listing->price) }}" data-cart-price-label="{{ number_format((float) ($listing->has_offer && $listing->offer_price ? $listing->offer_price : $listing->price)) }} ر.س" data-cart-category="{{ $categoryNames[$listing->category_slug] ?? $listing->category_slug }}" data-cart-buy-url="{{ route('marketplace.buy', ['listing' => $listing->id]) }}" aria-label="إضافة إلى السلة">
                                                <span class="material-symbols-outlined">shopping_cart</span>
                                            </button>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="bg-primary text-white p-3 rounded-full hover:bg-primary-container transition-colors flex items-center justify-center" aria-label="تسجيل الدخول للشراء">
                                            <span class="material-symbols-outlined">shopping_cart</span>
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="flex min-h-[320px] items-center justify-center rounded-[28px] border border-dashed border-outline-variant/20 bg-white/70 px-6 text-center">
                    <div class="max-w-md py-10">
                        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-surface-container-high text-primary">
                            <span class="material-symbols-outlined text-3xl">inventory_2</span>
                        </div>
                        <h3 class="text-2xl font-black text-primary mb-3">{{ $emptyTitle }}</h3>
                        <p class="text-sm text-on-surface-variant leading-7">{{ $emptyMessage }}</p>
                        <a href="{{ route('new-arrivals.index') }}" class="mt-6 inline-flex items-center justify-center rounded-full bg-primary px-6 py-3 font-bold text-white transition-colors hover:bg-primary-container">
                            تصفح السوق
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
