@extends('layouts.main')

@section('title', 'الملف الشخصي | '.config('app.name', 'ArabCart'))
@section('nav-active', 'dashboard')

@section('content')
    <section class="px-4 md:px-0">
        <div class="rounded-3xl bg-gradient-to-l from-primary via-primary-container to-secondary-container text-white p-6 md:p-8 shadow-lg">
            <p class="text-xs md:text-sm text-white/80">إعدادات الحساب</p>
            <h1 class="headline-font text-2xl md:text-3xl font-extrabold mt-2">الملف الشخصي</h1>
            <p class="text-sm md:text-base mt-3 text-white/90">حدّث بياناتك الشخصية، غيّر كلمة المرور، أو تحكم في إعدادات حسابك من صفحة واحدة.</p>
        </div>

        <div class="mt-6 space-y-5">
            <div class="rounded-2xl border border-outline-variant/30 bg-white p-5 md:p-6 shadow-sm">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="rounded-2xl border border-outline-variant/30 bg-white p-5 md:p-6 shadow-sm">
                @include('profile.partials.update-password-form')
            </div>

            <div class="rounded-2xl border border-error/20 bg-white p-5 md:p-6 shadow-sm">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </section>
@endsection
