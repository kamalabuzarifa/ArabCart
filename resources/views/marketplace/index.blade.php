@extends('layouts.main')

@section('title', config('app.name', 'ArabCart') . ' | السوق المفتوح')
@section('nav-active', 'marketplace')

@section('sidebar')
    <div class="px-6 pt-6">
        <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined">storefront</span>
            <span>منتجاتي</span>
        </h3>
        <a href="{{ route('marketplace.index') }}" class="block text-sm px-3 py-2 rounded-lg {{ $selectedCategory ? 'text-slate-600 hover:bg-surface-container-high' : 'bg-primary text-white font-bold' }}">كل المنتجات</a>
        <div class="mt-2 space-y-1">
            @foreach ($categories as $category)
                <a href="{{ route('marketplace.index', ['category' => $category['slug']]) }}" class="flex items-center gap-2 text-sm px-3 py-2 rounded-lg {{ $selectedCategory === $category['slug'] ? 'bg-primary text-white font-bold' : 'text-slate-600 hover:bg-surface-container-high' }}">
                    <span class="material-symbols-outlined text-base">{{ $category['icon'] }}</span>
                    <span>{{ $category['name'] }}</span>
                </a>
            @endforeach
        </div>
    </div>

@endsection

@section('content')
    <section class="px-6 md:px-10">
        <div class="rounded-xl bg-gradient-to-l from-primary to-primary-container text-white p-8 md:p-10 shadow-[0_8px_30px_rgb(0,0,0,0.08)]">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
                <div>
                    <h1 class="text-2xl md:text-4xl font-black mb-3">منتجاتي</h1>
                    <p class="text-primary-fixed mb-6 max-w-2xl">هنا تظهر المنتجات التي أضفتها أنت فقط، ويمكنك تعديلها أو حذفها بسرعة.</p>
                    <div class="flex gap-3 flex-wrap">
                        @auth
                            <a href="{{ route('marketplace.create') }}" class="px-5 py-2 rounded-full bg-secondary-container text-on-secondary-container font-bold">أضف منتجًا جديدًا</a>
                        @else
                            <a href="{{ route('login') }}" class="px-5 py-2 rounded-full bg-secondary-container text-on-secondary-container font-bold">سجّل وابدأ البيع</a>
                        @endauth
                    </div>
                </div>

                <div class="md:min-w-[150px] flex items-center ">
                    <div class="rounded-xl bg-white/10 backdrop-blur px-4 py-3 text-right">
                        <p class="text-xs text-primary-fixed/80">عدد منتجاتي</p>
                        <p class="text-2xl font-black text-center">{{ number_format($ownCount) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="px-6 md:px-10 py-10 page-container">
        @if (session('status'))
            <div class="mb-6 rounded-xl bg-green-50 text-green-700 border border-green-200 px-4 py-3 text-sm font-bold">{{ session('status') }}</div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 rtl-product-grid">
            @forelse ($ownListings as $listing)
                <x-product-card
                    class="product-card-groups"
                    :title="$listing->title"
                    :image="route('marketplace.image', $listing)"
                    :category="$listing->category_slug ?? ''"
                    :description="$listing->description"
                    :price="number_format((float) ($listing->has_offer && $listing->offer_price ? $listing->offer_price : $listing->price)) . ' ر.س'"
                    :old-price="$listing->has_offer && $listing->offer_price ? number_format((float) $listing->price) . ' ر.س' : null"
                    :rating="$listing->rating_avg ?? 0"
                    :rating-count="$listing->rating_count ?? 0"
                    :sales-count="$listing->sales_count ?? 0"
                    :show-favorite="false"
                    :show-cart="false"
                    heading-tag="h2"
                >
                    <x-slot:actions>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('marketplace.edit', $listing) }}" class="btn-outline-unified text-sm px-4 py-2">تعديل</a>
                            <form method="POST" action="{{ route('marketplace.destroy', $listing) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm px-4 py-2 rounded-full bg-red-50 text-red-700 font-bold hover:bg-red-100 transition-colors">حذف</button>
                            </form>
                        </div>
                    </x-slot:actions>
                </x-product-card>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-outline-variant p-8 text-center text-on-surface-variant">
                    لا توجد لديك منتجات بعد. أضف أول منتج الآن.
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $ownListings->links() }}
        </div>
    </section>
@endsection
