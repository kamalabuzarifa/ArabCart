<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>استعادة كلمة المرور | The Elevated Merchant</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&amp;family=Manrope:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "secondary-fixed": "#ffdcc1",
                        "on-error-container": "#93000a",
                        "on-error": "#ffffff",
                        "on-tertiary": "#ffffff",
                        "primary-fixed-dim": "#96ceeb",
                        "on-surface-variant": "#40484c",
                        "surface": "#f8f9fb",
                        "tertiary-fixed-dim": "#e9c349",
                        "tertiary": "#735c00",
                        "inverse-surface": "#2e3133",
                        "on-primary-container": "#83bad6",
                        "outline-variant": "#c0c7cd",
                        "on-secondary": "#ffffff",
                        "on-tertiary-fixed-variant": "#574500",
                        "on-secondary-fixed-variant": "#6c3a00",
                        "tertiary-fixed": "#ffe088",
                        "surface-container-high": "#e7e8ea",
                        "on-background": "#191c1e",
                        "error-container": "#ffdad6",
                        "error": "#ba1a1a",
                        "background": "#f8f9fb",
                        "surface-bright": "#f8f9fb",
                        "secondary": "#8e4e00",
                        "inverse-primary": "#96ceeb",
                        "on-secondary-container": "#613400",
                        "tertiary-container": "#cba72f",
                        "surface-dim": "#d9dadc",
                        "primary": "#003345",
                        "surface-variant": "#e1e2e5",
                        "outline": "#71787d",
                        "on-primary": "#ffffff",
                        "secondary-fixed-dim": "#ffb778",
                        "on-tertiary-fixed": "#241a00",
                        "inverse-on-surface": "#f0f1f3",
                        "surface-container-low": "#f2f4f6",
                        "on-primary-fixed": "#001f2b",
                        "on-secondary-fixed": "#2e1500",
                        "secondary-container": "#fd9000",
                        "on-tertiary-container": "#4e3d00",
                        "on-primary-fixed-variant": "#044d65",
                        "surface-container-highest": "#e1e2e5",
                        "surface-tint": "#2a657e",
                        "primary-fixed": "#bfe8ff",
                        "surface-container-lowest": "#ffffff",
                        "surface-container": "#edeef0",
                        "primary-container": "#004b63",
                        "on-surface": "#191c1e"
                    },
                    fontFamily: {
                        "headline": ["Plus Jakarta Sans", "IBM Plex Sans Arabic"],
                        "body": ["Manrope", "IBM Plex Sans Arabic"],
                        "label": ["Manrope", "IBM Plex Sans Arabic"]
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Manrope', 'IBM Plex Sans Arabic', sans-serif;
            background-color: #f8f9fb;
        }
        .font-plus-jakarta { font-family: 'Plus Jakarta Sans', 'IBM Plex Sans Arabic', sans-serif; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-surface text-on-surface antialiased overflow-hidden">
<main class="min-h-screen flex flex-row">
    <section class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-primary via-primary-container to-secondary-container z-0"></div>
        <div class="absolute inset-0 z-10 opacity-30 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.22),transparent_30%),radial-gradient(circle_at_bottom_left,rgba(255,255,255,0.12),transparent_28%)]"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-primary/60 to-transparent z-20"></div>
        <div class="relative z-20 flex flex-col justify-end p-16 text-white text-right">
            <h2 class="text-4xl font-headline font-extrabold mb-4 tracking-tight leading-tight">
                نُعيد صياغة <br/> مفهوم الأناقة
            </h2>
            <p class="text-lg opacity-90 max-w-md font-body leading-relaxed">
                انضم إلى عالم النخبة من التجار، حيث نجمع بين الأصالة والحداثة في تجربة رقمية لا تُنسى.
            </p>
        </div>
    </section>

    <section class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-surface">
        <div class="w-full max-w-md space-y-10">
            <div class="flex flex-col items-center lg:items-start text-center lg:text-right space-y-2">
                <div class="flex items-center space-x-2 space-x-reverse mb-6">
                    <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-2xl">storefront</span>
                    </div>
                    <span class="text-xl font-headline font-bold text-primary tracking-tight">سلة التسوق</span>
                </div>
                <h1 class="text-3xl font-headline font-extrabold text-on-surface tracking-tight">استعادة كلمة المرور</h1>
                <p class="text-on-surface-variant font-body text-base leading-relaxed">
                    أدخل بريدك الإلكتروني المسجل وسنرسل لك رابطاً لإعادة تعيين كلمة المرور
                </p>
            </div>

            @if (session('status'))
                <div class="rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 font-body">
                    {{ session('status') }}
                </div>
            @endif

            <form class="space-y-6" method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-on-surface mr-1" for="email">البريد الإلكتروني</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-outline group-focus-within:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[20px]">mail</span>
                        </div>
                        <input
                            class="w-full h-14 pr-12 bg-surface-container-highest border-none rounded-xl focus:ring-2 focus:ring-primary/20 text-on-surface placeholder:text-outline transition-all duration-300 font-body"
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            placeholder="name@example.com"
                            required
                            autofocus
                        />
                    </div>
                    @error('email')
                        <p class="text-sm text-error font-body mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4">
                    <button class="w-full h-14 bg-gradient-to-r from-primary to-primary-container text-on-primary font-bold rounded-full shadow-lg hover:shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-2 group" type="submit">
                        <span>إرسال رابط الاستعادة</span>
                        <span class="material-symbols-outlined text-[20px] group-hover:-translate-x-1 transition-transform">arrow_back</span>
                    </button>
                </div>
            </form>

            <div class="pt-8 text-center lg:text-right">
                <a class="inline-flex items-center text-primary font-bold hover:text-primary-container transition-colors group" href="{{ route('login') }}">
                    <span class="material-symbols-outlined text-[20px] ml-2 group-hover:translate-x-1 transition-transform">keyboard_tab</span>
                    <span class="font-body">العودة لتسجيل الدخول</span>
                </a>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-12 opacity-40">
                <div class="h-1 bg-surface-container-high rounded-full"></div>
                <div class="h-1 bg-surface-container-high rounded-full w-1/2"></div>
            </div>
        </div>
    </section>
</main>
</body>
</html>
