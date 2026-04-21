@extends('layouts.main')

@section('title', config('app.name', 'ArabCart') . ' | ' . $selectedCategory['name'])
@section('nav-active', 'groups')

@section('sidebar')
    <div class="px-6 pt-6">
        <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined">category</span>
            <span>التصنيفات</span>
        </h3>
        <nav class="flex flex-col gap-1">
            <a class="flex items-center gap-3 p-3 rounded-xl text-slate-600 hover:bg-surface-container-high hover:text-primary transition-all" href="{{ route('categories.index', ['max_price' => $maxPrice]) }}">
                <span class="material-symbols-outlined text-xl">apps</span>
                <span>جميع التصنيفات</span>
            </a>
            @foreach ($categories as $category)
                <a class="flex items-center gap-3 p-3 rounded-xl {{ $selectedCategory['slug'] === $category['slug'] ? 'bg-primary text-white font-bold' : 'text-slate-600 hover:bg-surface-container-high hover:text-primary' }} transition-all" href="{{ route('categories.show', ['slug' => $category['slug'], 'max_price' => $maxPrice]) }}">
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
        <form method="GET" action="{{ route('categories.show', ['slug' => $selectedCategory['slug']]) }}" class="space-y-3">
            <input id="price-range-single" name="max_price" class="w-full h-1 bg-surface-container-highest rounded-lg appearance-none cursor-pointer accent-primary" type="range" min="0" max="5000" step="50" value="{{ $maxPrice }}" />
            <div class="flex justify-between items-center text-xs font-bold text-on-surface-variant">
                <span>٠ ر.س</span>
                <span>٥٠٠٠ ر.س</span>
            </div>
            <p class="text-sm text-primary font-bold">الحد الاعلى: <span id="price-value-single">{{ number_format($maxPrice) }}</span> ر.س</p>
            <button type="submit" class="w-full text-sm px-4 py-2 rounded-full bg-primary text-white font-bold hover:bg-primary-container transition-colors">تطبيق</button>
        </form>
    </div>
@endsection

@section('content')
    <section class="page-container">
        <div class="mb-8 page-panel text-right">
            <h1 class="page-title">{{ $selectedCategory['name'] }}</h1>
            <p class="page-subtitle">عدد المنتجات ضمن النطاق الحالي: {{ count($products) }}</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 rtl-product-grid">
            @forelse ($products as $product)
                @php
                    $isOwnProduct = auth()->check() && !empty($product['user_id']) && (int) $product['user_id'] === auth()->id();
                @endphp
                <x-product-card
                    class="product-card-groups"
                    :title="$product['title']"
                    :image="$product['image']"
                    :category="$product['category_name']"
                    :description="$product['description']"
                    :price="number_format($product['price']) . ' ر.س'"
                    :old-price="!empty($product['old_price']) ? number_format($product['old_price']) . ' ر.س' : null"
                    :show-stats="false"
                    :favorite-key="'category-show-' . $loop->index . '-' . \Illuminate\Support\Str::slug($product['title'])"
                    :favorite-title="$product['title']"
                    :favorite-image="$product['image']"
                    :favorite-price="number_format($product['price']) . ' ر.س'"
                    :favorite-category="$product['category_name']"
                    :cart-item-id="'listing-' . $product['listing_id']"
                    :cart-listing-id="$product['listing_id']"
                    :cart-title="$product['title']"
                    :cart-image="$product['image']"
                    :cart-price="$product['price_value']"
                    :cart-price-label="number_format($product['price']) . ' ر.س'"
                    :cart-category="$product['category_name']"
                    :cart-buy-url="route('marketplace.buy', ['listing' => $product['listing_id']])"
                    :is-own-product="$isOwnProduct"
                    heading-tag="h2"
                />
            @empty
                <div class="sm:col-span-2 lg:col-span-3 rounded-2xl border border-outline-variant/20 bg-surface-container-low p-10 text-center text-on-surface-variant">
                    لا توجد منتجات في {{ $selectedCategory['name'] }} داخل نطاق السعر المحدد.
                </div>
            @endforelse
        </div>
    </section>
@endsection

@push('page-scripts')
    <script>
        (function () {
            var range = document.getElementById('price-range-single');
            var output = document.getElementById('price-value-single');
            if (!range || !output) return;
            range.addEventListener('input', function () {
                output.textContent = Number(range.value).toLocaleString('en-US');
            });
        })();
    </script>
@endpush
