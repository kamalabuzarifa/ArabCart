@extends('layouts.main')

@section('title', config('app.name', 'ArabCart') . ' | تم إكمال الطلب')
@section('nav-active', 'home')

@section('content')
    <section class="px-6 max-w-3xl mx-auto pb-16">
        <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/20 p-8 md:p-12 text-center">
            <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-4xl" style="font-variation-settings: 'FILL' 1;">check_circle</span>
            </div>

            <h1 class="text-3xl font-black text-primary mb-3">تم إكمال طلبك بنجاح</h1>
            <p class="text-on-surface-variant mb-8">شكرًا لك. تم تأكيد الطلب وسيتم تحديث حالة المنتجات تلقائيًا.</p>

            <div class="flex flex-col sm:flex-row-reverse gap-3 justify-center">
                <a href="{{ url('/') }}" class="inline-flex items-center justify-center rounded-full bg-primary text-white font-bold px-8 py-3 hover:bg-primary-container transition-colors">العودة للرئيسية</a>
                <a href="{{ route('offers.index') }}" class="inline-flex items-center justify-center rounded-full border border-outline-variant text-primary font-bold px-8 py-3 hover:bg-surface-container-low transition-colors">متابعة التسوق</a>
            </div>
        </div>
    </section>
@endsection
