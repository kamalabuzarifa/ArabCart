@extends('layouts.main')

@section('title', 'حسابي | '.config('app.name', 'ArabCart'))
@section('nav-active', 'dashboard')

@section('content')
    <section class="px-4 md:px-0 pt-10">
        <div class="rounded-3xl bg-gradient-to-l from-primary via-primary-container to-secondary-container text-white p-6 md:p-8 shadow-lg">
            <div class="flex flex-col md:flex-row md:items-center gap-5">
                <div class="shrink-0">
                    @if (auth()->user()->avatar_url)
                        <img src="{{ auth()->user()->avatar_url }}" alt="صورة الحساب" class="w-20 h-20 md:w-24 md:h-24 rounded-full object-cover ring-4 ring-white/30 shadow-xl" />
                    @else
                        <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white/15 flex items-center justify-center text-white text-3xl md:text-4xl font-bold ring-4 ring-white/20 shadow-xl">
                            {{ mb_substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif
                </div>

                <div class="min-w-0">
                    <p class="text-xs md:text-sm text-white/80">لوحة حسابي</p>
                    <h1 class="headline-font text-2xl md:text-3xl font-extrabold mt-2">مرحباً {{ auth()->user()->name }}</h1>
                    <p class="text-sm md:text-base mt-3 text-white/90">هنا تقدر تراجع بيانات حسابك وتتحكم بإعداداتك الأساسية بسرعة.</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 mt-6">
            <article class="lg:col-span-2 rounded-2xl border border-outline-variant/30 bg-white p-5 md:p-6 shadow-sm">
                <h2 class="headline-font text-lg font-bold text-on-surface">بيانات المستخدم</h2>

                <dl class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-xl bg-surface-container-low p-4">
                        <dt class="text-xs text-on-surface-variant">الاسم</dt>
                        <dd class="mt-1 text-sm font-bold text-on-surface">{{ auth()->user()->name }}</dd>
                    </div>

                    <div class="rounded-xl bg-surface-container-low p-4">
                        <dt class="text-xs text-on-surface-variant">البريد الإلكتروني</dt>
                        <dd class="mt-1 text-sm font-bold text-on-surface break-all">{{ auth()->user()->email }}</dd>
                    </div>

                    <div class="rounded-xl bg-surface-container-low p-4">
                        <dt class="text-xs text-on-surface-variant">نوع تسجيل الدخول</dt>
                        <dd class="mt-1 text-sm font-bold text-on-surface">{{ auth()->user()->provider ? ucfirst(auth()->user()->provider) : 'بريد إلكتروني وكلمة مرور' }}</dd>
                    </div>

                    <div class="rounded-xl bg-surface-container-low p-4">
                        <dt class="text-xs text-on-surface-variant">تاريخ الانضمام</dt>
                        <dd class="mt-1 text-sm font-bold text-on-surface">{{ optional(auth()->user()->created_at)->format('Y-m-d') }}</dd>
                    </div>
                </dl>
            </article>

            <aside class="rounded-2xl border border-outline-variant/30 bg-white p-5 md:p-6 shadow-sm">
                <h2 class="headline-font text-lg font-bold text-on-surface">إجراءات سريعة</h2>

                <div class="mt-5 space-y-3">
                    <a href="{{ route('profile.edit') }}" class="w-full inline-flex items-center justify-center rounded-xl bg-primary text-white font-bold py-2.5 hover:bg-primary-container transition-colors">
                        تعديل الملف الشخصي
                    </a>

                    <a href="{{ route('marketplace.index') }}" class="w-full inline-flex items-center justify-center rounded-xl bg-surface-container-low text-on-surface font-bold py-2.5 hover:bg-surface-container transition-colors">
                        منتجاتي
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full rounded-xl bg-error-container text-error font-bold py-2.5 hover:opacity-90 transition-opacity">
                            تسجيل الخروج
                        </button>
                    </form>
                </div>
            </aside>
        </div>
    </section>
@endsection
