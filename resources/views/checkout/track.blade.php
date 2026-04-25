@extends('layouts.main')

@section('title', config('app.name', 'ArabCart') . ' | تتبع طلبك')
@section('nav-active', 'groups')

@php
    $latestOrder = auth()->check()
        ? \App\Models\ListingOrder::query()
            ->where('buyer_id', auth()->id())
            ->with('listing:id,title,description,image_url')
            ->latest()
            ->first()
        : null;

    $statusLabels = [
        'completed' => 'تم التوصيل',
        'processing' => 'قيد التجهيز',
        'shipped' => 'في الطريق',
    ];

    $statusText = $latestOrder ? ($statusLabels[$latestOrder->status] ?? 'تم التأكيد') : 'لا يوجد طلبات حالياً';
    $trackingCode = $latestOrder ? ('ORD-' . str_pad((string) $latestOrder->id, 6, '0', STR_PAD_LEFT)) : '---';
    $latestTitle = $latestOrder && $latestOrder->listing ? $latestOrder->listing->title : 'لا يوجد منتج';
    $latestDescription = $latestOrder && $latestOrder->listing ? (string) $latestOrder->listing->description : 'لا يوجد وصف متاح.';
@endphp

@section('content')
    <section class="px-6 max-w-7xl mx-auto pb-16">
        <div class="mb-10 text-right">
            <h1 class="text-4xl font-black text-primary font-headline mb-2">تتبع طلبك</h1>
            <p class="text-on-surface-variant">تابع حالة آخر طلب لديك بشكل فوري.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8 space-y-8">
                <div class="bg-surface-container-lowest p-8 rounded-xl shadow-sm">
                    <h2 class="text-xl font-bold text-primary mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined">local_shipping</span>
                        حالة الشحنة
                    </h2>
                    <div class="rounded-xl bg-surface-container-low p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <p class="text-sm text-on-surface-variant">رقم الطلب</p>
                            <p class="text-lg font-bold text-primary">{{ $trackingCode }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-on-surface-variant">الحالة</p>
                            <p class="text-lg font-bold text-secondary">{{ $statusText }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-on-surface-variant">وسيلة الدفع</p>
                            <p class="text-lg font-bold text-primary">
                                @if ($latestOrder)
                                    {{ $latestOrder->payment_method === 'apple' ? 'Apple Pay' : ($latestOrder->payment_method === 'cash' ? 'الدفع عند الاستلام' : 'بطاقة مدى / ائتمانية') }}
                                @else
                                    --
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <aside class="lg:col-span-4">
                <div class="bg-surface-container-low rounded-xl p-6 space-y-4 sticky top-24">
                    <h3 class="text-lg font-bold text-primary">ملخص الطلب</h3>
                    <p class="text-sm text-on-surface-variant">آخر منتج تم شراؤه:</p>
                    <p class="font-bold text-on-surface">{{ $latestTitle }}</p>
                    <p class="text-sm text-on-surface-variant leading-7 break-words">{{ $latestDescription }}</p>
                    <a href="{{ route('checkout.index') }}" class="inline-flex w-full items-center justify-center rounded-full bg-primary text-white font-bold py-3 hover:bg-primary-container transition-colors">العودة للدفع</a>
                </div>
            </aside>
        </div>
    </section>
@endsection
