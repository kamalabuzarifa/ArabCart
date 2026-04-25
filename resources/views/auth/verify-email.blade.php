<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>تحقق من البريد الإلكتروني | {{ config('app.name', 'ArabCart') }}</title>
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
        .cubic-bezier {
            transition-timing-function: cubic-bezier(0.2, 0, 0, 1);
        }
    </style>
</head>
<body class="bg-surface text-on-surface min-h-screen overflow-x-hidden">
<main class="min-h-screen flex flex-col md:flex-row">
    <section class="hidden md:flex w-1/2 bg-gradient-to-br from-primary via-primary-container to-secondary-container relative items-center justify-center p-12 overflow-hidden">
        <div class="absolute inset-0 opacity-30 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.22),transparent_30%),radial-gradient(circle_at_bottom_left,rgba(255,255,255,0.12),transparent_28%)]"></div>
        <div class="relative z-10 max-w-md text-right">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-white/10 mb-6">
                <span class="material-symbols-outlined text-white text-2xl">mail</span>
            </div>
            <h1 class="font-headline text-5xl font-extrabold text-on-primary leading-tight mb-6 tracking-tight">
                تحقق من بريدك الإلكتروني
            </h1>
            <p class="text-on-primary/80 leading-relaxed text-lg">
                أرسلنا لك رابط تفعيل إلى البريد الإلكتروني المسجل. بعد التأكيد ستتمكن من الوصول إلى حسابك بشكل كامل وآمن.
            </p>
            <div class="mt-12 flex gap-4 justify-end">
                <div class="h-1 w-10 bg-secondary rounded-full"></div>
                <div class="h-1 w-4 bg-on-primary/30 rounded-full"></div>
                <div class="h-1 w-4 bg-on-primary/30 rounded-full"></div>
            </div>
        </div>
    </section>

    <section class="w-full md:w-1/2 flex flex-col items-center justify-center p-8 md:p-16 lg:p-24 bg-surface-container-lowest relative">
        <div class="w-full max-w-md mt-14 md:mt-0 text-right">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-primary mb-6">
                <span class="material-symbols-outlined text-white">mark_email_read</span>
            </div>

            <h2 class="font-headline text-3xl font-bold text-primary tracking-tight mb-3">تأكيد البريد الإلكتروني</h2>
            <p class="text-on-surface-variant leading-relaxed mb-6">
                {{ __('لقد أرسلنا رابط تفعيل إلى البريد الإلكتروني الذي استخدمته أثناء التسجيل. إذا لم يصلك البريد، يمكنك طلب إرسال رابط جديد مباشرة من هنا.') }}
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-5 rounded-xl border border-primary/15 bg-primary/5 px-4 py-3 text-sm text-primary">
                    تم إرسال رابط تفعيل جديد إلى البريد الإلكتروني المسجل.
                </div>
            @endif

            <div class="space-y-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="w-full bg-gradient-to-r from-primary to-primary-container text-on-primary py-4 rounded-full font-bold text-lg shadow-xl shadow-primary/10 hover:shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all cubic-bezier">
                        إعادة إرسال رابط التفعيل
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full bg-surface-container-high text-on-surface py-4 rounded-full font-bold text-lg hover:bg-surface-container transition-all cubic-bezier border border-outline-variant/15">
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>
    </section>
</main>
</body>
</html>
