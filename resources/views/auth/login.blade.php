<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>تسجيل الدخول | {{ config('app.name', 'ArabCart') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;family=Manrope:wght@400;500;600;700&amp;family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'on-surface-variant': '#40484c',
                        'surface-container-high': '#e7e8ea',
                        'primary-container': '#004b63',
                        'on-tertiary-fixed': '#241a00',
                        'tertiary-fixed': '#ffe088',
                        'outline-variant': '#c0c7cd',
                        'surface-tint': '#2a657e',
                        'on-surface': '#191c1e',
                        'secondary-container': '#fd9000',
                        'surface-dim': '#d9dadc',
                        'surface-variant': '#e1e2e5',
                        'secondary-fixed': '#ffdcc1',
                        'inverse-primary': '#96ceeb',
                        'on-tertiary': '#ffffff',
                        'surface-bright': '#f8f9fb',
                        'outline': '#71787d',
                        'on-secondary': '#ffffff',
                        'surface-container-highest': '#e1e2e5',
                        'surface-container': '#edeef0',
                        'error-container': '#ffdad6',
                        'primary-fixed': '#bfe8ff',
                        'on-primary-fixed': '#001f2b',
                        'background': '#f8f9fb',
                        'primary-fixed-dim': '#96ceeb',
                        'on-primary-container': '#83bad6',
                        'on-secondary-fixed-variant': '#6c3a00',
                        'on-secondary-container': '#613400',
                        'tertiary': '#735c00',
                        'surface-container-lowest': '#ffffff',
                        'on-primary-fixed-variant': '#044d65',
                        'on-error': '#ffffff',
                        'on-secondary-fixed': '#2e1500',
                        'secondary': '#8e4e00',
                        'on-background': '#191c1e',
                        'secondary-fixed-dim': '#ffb778',
                        'on-error-container': '#93000a',
                        'error': '#ba1a1a',
                        'surface-container-low': '#f2f4f6',
                        'on-tertiary-fixed-variant': '#574500',
                        'surface': '#f8f9fb',
                        'inverse-on-surface': '#f0f1f3',
                        'primary': '#003345',
                        'on-tertiary-container': '#4e3d00',
                        'tertiary-fixed-dim': '#e9c349',
                        'on-primary': '#ffffff',
                        'tertiary-container': '#cba72f',
                        'inverse-surface': '#2e3133'
                    },
                    fontFamily: {
                        headline: ['Plus Jakarta Sans', 'IBM Plex Sans Arabic'],
                        body: ['Manrope', 'IBM Plex Sans Arabic'],
                        label: ['Manrope', 'IBM Plex Sans Arabic']
                    },
                    borderRadius: {
                        DEFAULT: '0.25rem',
                        lg: '0.5rem',
                        xl: '0.75rem',
                        full: '9999px'
                    }
                }
            }
        };
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        .custom-bezier {
            transition-timing-function: cubic-bezier(0.2, 0, 0, 1);
        }
    </style>
</head>
<body class="bg-surface font-body text-on-surface min-h-screen overflow-x-hidden">
<main class="flex min-h-screen w-full">
    <section class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-primary items-center justify-center p-12">
        <div class="absolute inset-0 z-0 bg-gradient-to-br from-primary via-primary-container to-secondary-container opacity-95"></div>
        <div class="absolute inset-0 z-0 opacity-30 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.22),transparent_30%),radial-gradient(circle_at_bottom_left,rgba(255,255,255,0.12),transparent_28%)]"></div>
        <div class="absolute inset-0 z-10 bg-gradient-to-tr from-primary/90 to-transparent"></div>
        <div class="relative z-20 max-w-lg text-right">
            <h1 class="font-headline text-5xl font-extrabold text-on-primary leading-tight mb-6 tracking-tight">
                عالم من الفخامة <br /> يبدأ من هنا
            </h1>
            <p class="text-on-primary/70 text-lg leading-relaxed font-light">
                انضم إلى منصتنا الحصرية واكتشف تجربة تسوق مصممة خصيصا لأصحاب الذوق الرفيع. نحن لا نبيع مجرد منتجات، بل نقدم تجارب استثنائية.
            </p>
            <div class="mt-12 flex gap-4 justify-end">
                <div class="h-1 w-12 bg-secondary rounded-full"></div>
                <div class="h-1 w-4 bg-on-primary/30 rounded-full"></div>
                <div class="h-1 w-4 bg-on-primary/30 rounded-full"></div>
            </div>
        </div>
    </section>

    <section class="w-full lg:w-1/2 flex flex-col items-center justify-center p-8 md:p-16 lg:p-24 bg-surface-container-lowest relative">
        <div class="absolute top-12 right-12 flex items-center gap-3">
            <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center shadow-lg">
                <span class="material-symbols-outlined text-white">storefront</span>
            </div>
            <span class="font-headline text-2xl font-bold text-primary tracking-tighter"> سلة التسوق</span>
        </div>

        <div class="w-full max-w-md mt-20 lg:mt-0">
            <div class="mb-10 text-right">
                <h2 class="font-headline text-3xl font-bold text-on-surface mb-3">مرحبا بك مجددا</h2>
                <p class="text-on-surface-variant font-medium">سجل دخولك للوصول إلى مجموعتك المختارة</p>
            </div>

            @if (session('status'))
                <div class="mb-5 rounded-xl border border-primary/15 bg-primary/5 px-4 py-3 text-sm text-primary">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-error/20 bg-error-container px-4 py-3 text-sm text-on-error-container">
                    <ul class="space-y-1 list-disc pr-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('social_error'))
                <div class="mb-5 rounded-xl border border-error/20 bg-error-container px-4 py-3 text-sm text-on-error-container">
                    {{ session('social_error') }}
                </div>
            @endif

            @if (app()->environment('local') && config('app.social_login_mock'))
                <div class="mb-5 rounded-xl border border-secondary/20 bg-secondary/10 px-4 py-3 text-sm text-on-surface">
                    وضع التطوير مفعل: أزرار Google وLinkedIn وFacebook تستخدم تسجيل دخول تجريبي محلي.
                </div>
            @endif

            <form class="space-y-6 text-right" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-on-surface-variant mr-1" for="email">البريد الإلكتروني</label>
                    <div class="relative">
                        <input class="w-full px-5 py-4 bg-surface-container-high rounded-xl border-none focus:ring-2 focus:ring-primary/20 transition-all text-right placeholder-outline/50" id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" type="email" required autofocus autocomplete="username" />
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline/60">mail</span>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-center px-1">
                        <label class="block text-sm font-bold text-on-surface-variant" for="password">كلمة المرور</label>
                        @if (Route::has('password.request'))
                            <a class="text-xs font-bold text-secondary hover:underline transition-all" href="{{ route('password.request') }}">هل نسيت كلمة المرور؟</a>
                        @else
                            <span></span>
                        @endif
                    </div>
                    <div class="relative">
                        <input class="w-full px-5 py-4 bg-surface-container-high rounded-xl border-none focus:ring-2 focus:ring-primary/20 transition-all text-right placeholder-outline/50" id="password" name="password" placeholder="••••••••" type="password" required autocomplete="current-password" />
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline/60">lock</span>
                    </div>
                </div>

                <div class="flex items-center justify-start gap-2 px-1">
                    <input class="w-5 h-5 rounded text-primary border-outline-variant focus:ring-primary cursor-pointer" id="remember" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }} />
                    <label class="text-sm text-on-surface-variant cursor-pointer" for="remember">تذكرني على هذا الجهاز</label>
                </div>

                <button
                    class="w-full bg-gradient-to-r from-primary to-primary-container text-on-primary py-4 rounded-full font-bold text-lg shadow-xl shadow-primary/10 hover:shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all custom-bezier"
                    type="submit"
                >
                    تسجيل الدخول
                </button>
            </form>

            <div class="relative my-10">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-outline-variant/30"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-surface-container-lowest text-outline font-medium">أو عبر المنصات الاجتماعية</span>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                @php
                    $socialProviders = [
                        ['key' => 'google', 'label' => 'Google', 'image' => 'google.png'],
                        ['key' => 'linkedin', 'label' => 'LinkedIn', 'image' => 'linkedIn.png'],
                        ['key' => 'facebook', 'label' => 'Facebook', 'image' => 'facebook.png'],
                    ];
                @endphp

                @foreach ($socialProviders as $provider)
                    <a href="{{ route('social.redirect', $provider['key']) }}" class="flex items-center justify-center gap-3 py-3 px-4 rounded-xl border border-outline-variant/20 bg-white hover:bg-surface-container-low transition-all custom-bezier">
                        <img class="h-6 w-6 shrink-0 object-contain" src="{{ asset('images/' . $provider['image']) }}" alt="{{ $provider['label'] }}" />
                        <span class="text-sm font-bold text-on-surface">{{ $provider['label'] }}</span>
                    </a>
                @endforeach
            </div>

            <p class="mt-12 text-center text-on-surface-variant font-medium">
                ليس لديك حساب؟
                <a class="text-primary font-bold hover:underline mr-1" href="{{ route('register') }}">إنشاء حساب جديد</a>
            </p>
        </div>

        <div class="absolute bottom-8 flex gap-6 text-[10px] font-bold text-outline tracking-wider uppercase opacity-50">
            <a class="hover:text-primary transition-colors" href="{{ route('privacy.index') }}">سياسة الخصوصية</a>
            <a class="hover:text-primary transition-colors" href="{{ route('terms.index') }}">شروط الاستخدام</a>
            <a class="hover:text-primary transition-colors" href="{{ route('help.index') }}">مركز المساعدة</a>
        </div>
    </section>
</main>
</body>
</html>
