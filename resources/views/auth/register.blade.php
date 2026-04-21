<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>إنشاء حساب - {{ config('app.name', 'ArabCart') }}</title>
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
                        '2xl': '1rem',
                        '3xl': '1.5rem',
                        full: '9999px'
                    }
                }
            }
        };
    </script>
    <style>
        body { font-family: 'Manrope', 'IBM Plex Sans Arabic', sans-serif; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            line-height: 1;
            text-transform: none;
            letter-spacing: normal;
            word-wrap: normal;
            white-space: nowrap;
            direction: ltr;
        }
        .hero-gradient {
            background: linear-gradient(135deg, #003345 0%, #004b63 100%);
        }
        .cubic-bezier {
            transition-timing-function: cubic-bezier(0.2, 0, 0, 1);
        }
    </style>
</head>
<body class="bg-surface text-on-surface antialiased overflow-x-hidden">
<main class="min-h-screen flex flex-col md:flex-row">
    <section class="hidden md:flex w-1/2 hero-gradient relative items-center justify-center p-12 overflow-hidden">
        <div class="absolute inset-0 opacity-30 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.18),transparent_30%),radial-gradient(circle_at_bottom_left,rgba(255,255,255,0.08),transparent_28%)]"></div>
        <div class="relative z-10 text-center max-w-md">
            <h1 class="font-headline font-extrabold text-5xl text-on-primary leading-tight mb-6 tracking-tight">
                انضم إلى عالم الفخامة
            </h1>
            <p class="font-body text-lg text-on-primary/80 leading-relaxed">
                تجربة تسوق فريدة تنتظرك. اكتشف مجموعتنا المختارة بعناية من المنتجات الفاخرة التي تعكس ذوقك الرفيع.
            </p>
            <div class="mt-12 flex justify-center gap-4">
                <div class="h-1 w-12 bg-secondary-container rounded-full"></div>
                <div class="h-1 w-4 bg-on-primary/30 rounded-full"></div>
                <div class="h-1 w-4 bg-on-primary/30 rounded-full"></div>
            </div>
        </div>
    </section>

    <section class="flex-1 flex flex-col items-center justify-center px-6 py-12 md:px-16 lg:px-24 bg-surface">
        <div class="w-full max-w-md">
            <div class="mb-10 text-right">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-primary mb-6">
                    <span class="material-symbols-outlined text-on-primary text-2xl">account_balance_wallet</span>
                </div>
                <h2 class="font-headline font-bold text-3xl text-primary tracking-tight mb-2">إنشاء حساب</h2>
                <p class="font-body text-on-surface-variant">ابدأ رحلتك معنا اليوم واستمتع بمزايا حصرية.</p>
            </div>

            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-error/20 bg-error-container px-4 py-3 text-sm text-on-error-container">
                    <ul class="space-y-1 list-disc pr-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="space-y-5" method="POST" action="{{ route('register') }}">
                @csrf

                <div class="space-y-1.5">
                    <label for="name" class="font-label text-sm font-bold text-primary px-1">الاسم الكامل</label>
                    <div class="relative group">
                        <input id="name" name="name" value="{{ old('name') }}" class="w-full h-14 bg-surface-container-highest border-none rounded-xl px-12 font-body text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/20 cubic-bezier transition-all" placeholder="اسمك الكامل" type="text" required autofocus autocomplete="name" />
                        <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">person</span>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label for="email" class="font-label text-sm font-bold text-primary px-1">البريد الإلكتروني</label>
                    <div class="relative group">
                        <input id="email" name="email" value="{{ old('email') }}" class="w-full h-14 bg-surface-container-highest border-none rounded-xl px-12 font-body text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/20 cubic-bezier transition-all" dir="ltr" placeholder="example@domain.com" type="email" required autocomplete="username" />
                        <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">mail</span>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label for="phone" class="font-label text-sm font-bold text-primary px-1">رقم الهاتف</label>
                    <div class="relative group">
                        <input id="phone" name="phone" value="{{ old('phone') }}" class="w-full h-14 bg-surface-container-highest border-none rounded-xl px-12 font-body text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/20 cubic-bezier transition-all" dir="ltr" placeholder="+966 50 000 0000" type="tel" autocomplete="tel" />
                        <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">call</span>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label for="password" class="font-label text-sm font-bold text-primary px-1">كلمة المرور</label>
                    <div class="relative group">
                        <input id="password" name="password" class="w-full h-14 bg-surface-container-highest border-none rounded-xl px-12 font-body text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/20 cubic-bezier transition-all" dir="ltr" placeholder="••••••••" type="password" required autocomplete="new-password" />
                        <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">lock</span>
                        <button class="absolute left-4 top-1/2 -translate-y-1/2 text-outline hover:text-primary transition-colors" type="button" data-toggle-password="password">
                            <span class="material-symbols-outlined">visibility</span>
                        </button>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label for="password_confirmation" class="font-label text-sm font-bold text-primary px-1">تأكيد كلمة المرور</label>
                    <div class="relative group">
                        <input id="password_confirmation" name="password_confirmation" class="w-full h-14 bg-surface-container-highest border-none rounded-xl px-12 font-body text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/20 cubic-bezier transition-all" dir="ltr" placeholder="••••••••" type="password" required autocomplete="new-password" />
                        <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">verified_user</span>
                        <button class="absolute left-4 top-1/2 -translate-y-1/2 text-outline hover:text-primary transition-colors" type="button" data-toggle-password="password_confirmation">
                            <span class="material-symbols-outlined">visibility</span>
                        </button>
                    </div>
                </div>

                <div class="flex items-start gap-3 py-2">
                    <input class="mt-1 w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary transition-all" id="terms" type="checkbox" required />
                    <label class="font-body text-sm text-on-surface-variant leading-relaxed select-none" for="terms">
                       اوافق علي سياسة الخصوصية و الشروط و الأحكام الخاصة بسلة التسوق
                    </label>
                </div>

                <button class="w-full h-14 bg-secondary-container text-on-secondary-container font-headline font-bold text-lg rounded-full shadow-lg shadow-secondary-container/20 hover:scale-[1.02] active:scale-[0.98] cubic-bezier transition-all mt-4" type="submit">
                    إنشاء الحساب
                </button>
            </form>

            <div class="mt-10 text-center">
                <p class="font-body text-on-surface-variant">
                    لديك حساب بالفعل؟
                    <a class="text-primary font-bold mr-1 hover:underline" href="{{ route('login') }}">تسجيل الدخول</a>
                </p>
            </div>

            <div class="mt-12 pt-8 border-t border-outline-variant/10">
                @php
                    $socialProviders = [
                        ['key' => 'google', 'label' => 'Google', 'icon' => 'google.png'],
                        ['key' => 'linkedin', 'label' => 'LinkedIn', 'icon' => 'linkedIn.png'],
                        ['key' => 'facebook', 'label' => 'Facebook', 'icon' => 'facebook.png'],
                    ];
                @endphp
                <div class="grid grid-cols-3 gap-4">
                    @foreach ($socialProviders as $provider)
                        <a href="{{ route('social.redirect', $provider['key']) }}" class="flex items-center justify-center gap-3 py-3 px-4 rounded-xl border border-outline-variant/20 bg-white hover:bg-surface-container-low transition-all cubic-bezier">
                            <span class="text-sm font-bold text-on-surface">{{ $provider['label'] }}</span>
                            <span class="material-symbols-outlined text-base">{{ $provider['key'] === 'google' ? 'g_mobiledata' : ($provider['key'] === 'linkedin' ? 'work' : 'thumb_up') }}</span>
                        </a>
                    @endforeach
                </div>
                <p class="text-center font-label text-[10px] text-outline mt-6 uppercase tracking-widest">التسجيل السريع عبر المنصات الاجتماعية</p>
            </div>
        </div>
    </section>
</main>

<footer class="w-full bg-surface-container-low dark:bg-slate-900 flex flex-col md:flex-row justify-between items-center px-12 py-12 border-t border-outline-variant/10">
    <div class="text-lg font-bold text-primary mb-6 md:mb-0 tracking-tight font-headline">سلة التسوق</div>
    <div class="flex flex-wrap justify-center gap-8 mb-6 md:mb-0">
        <a class="font-body text-sm text-on-surface-variant hover:text-primary transition-colors" href="{{ route('privacy.index') }}">سياسة الخصوصية</a>
        <a class="font-body text-sm text-on-surface-variant hover:text-primary transition-colors" href="{{ route('terms.index') }}">الشروط والأحكام</a>
        <a class="font-body text-sm text-on-surface-variant hover:text-primary transition-colors" href="{{ route('help.index') }}">مركز المساعدة</a>
    </div>
    <div class="font-body text-sm text-on-surface-variant/70">
        © {{ date('Y') }} سلة التسوق  جميع الحقوق محفوظة.
    </div>
</footer>

<script>
    document.querySelectorAll('[data-toggle-password]').forEach(function (button) {
        button.addEventListener('click', function () {
            var targetId = this.getAttribute('data-toggle-password');
            var input = document.getElementById(targetId);
            if (!input) return;
            input.type = input.type === 'password' ? 'text' : 'password';
            this.querySelector('span').textContent = input.type === 'password' ? 'visibility' : 'visibility_off';
        });
    });
</script>
</body>
</html>
