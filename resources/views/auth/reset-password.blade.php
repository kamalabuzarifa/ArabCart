<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>إعادة تعيين كلمة المرور | The Elevated Merchant</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&amp;family=Manrope:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "on-tertiary": "#ffffff",
                        "secondary-fixed-dim": "#ffb778",
                        "on-secondary": "#ffffff",
                        "error": "#ba1a1a",
                        "surface-container-lowest": "#ffffff",
                        "surface-container": "#edeef0",
                        "inverse-surface": "#2e3133",
                        "surface-container-high": "#e7e8ea",
                        "secondary": "#8e4e00",
                        "inverse-primary": "#96ceeb",
                        "surface-bright": "#f8f9fb",
                        "primary-container": "#004b63",
                        "tertiary-fixed-dim": "#e9c349",
                        "tertiary": "#735c00",
                        "primary-fixed": "#bfe8ff",
                        "primary-fixed-dim": "#96ceeb",
                        "background": "#f8f9fb",
                        "surface-variant": "#e1e2e5",
                        "secondary-container": "#fd9000",
                        "on-error-container": "#93000a",
                        "surface-dim": "#d9dadc",
                        "surface-tint": "#2a657e",
                        "on-surface": "#191c1e",
                        "on-error": "#ffffff",
                        "tertiary-container": "#cba72f",
                        "tertiary-fixed": "#ffe088",
                        "on-background": "#191c1e",
                        "on-tertiary-container": "#4e3d00",
                        "on-primary-fixed": "#001f2b",
                        "on-secondary-fixed": "#2e1500",
                        "outline": "#71787d",
                        "on-secondary-fixed-variant": "#6c3a00",
                        "on-primary-fixed-variant": "#044d65",
                        "error-container": "#ffdad6",
                        "secondary-fixed": "#ffdcc1",
                        "surface-container-low": "#f2f4f6",
                        "primary": "#003345",
                        "inverse-on-surface": "#f0f1f3",
                        "on-tertiary-fixed-variant": "#574500",
                        "on-tertiary-fixed": "#241a00",
                        "on-secondary-container": "#613400",
                        "on-primary": "#ffffff",
                        "surface": "#f8f9fb",
                        "surface-container-highest": "#e1e2e5",
                        "outline-variant": "#c0c7cd",
                        "on-surface-variant": "#40484c",
                        "on-primary-container": "#83bad6"
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
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .luxury-gradient {
            background: linear-gradient(135deg, #003345 0%, #004b63 100%);
        }
        body {
            font-family: 'Manrope', 'IBM Plex Sans Arabic', sans-serif;
        }
    </style>
</head>
<body class="bg-surface text-on-surface min-h-screen">
<main class="min-h-screen flex flex-col md:flex-row-reverse">
    <section class="w-full lg:w-1/2 flex flex-col justify-between p-8 lg:p-16 xl:p-24 bg-surface z-10 relative">
        <header class="flex items-center justify-between mb-12">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 luxury-gradient rounded-xl flex items-center justify-center shadow-lg">
                    <span class="material-symbols-outlined text-white" style="font-variation-settings: 'FILL' 1;">storefront</span>
                </div>
                <span class="font-headline text-xl font-bold tracking-tight text-primary">سلة التسوق</span>
            </div>
        </header>

        <div class="max-w-md w-full mx-auto lg:mr-0 lg:ml-auto">
            <div class="mb-10">
                <h1 class="text-3xl lg:text-4xl font-headline font-bold text-primary mb-4">إعادة تعيين كلمة المرور</h1>
                <p class="text-on-surface-variant leading-relaxed">أدخل كلمة المرور الجديدة الخاصة بك أدناه لتحديث وصولك.</p>
            </div>

            @if ($errors->has('email'))
                <div class="mb-6 rounded-xl bg-error-container border border-error/20 text-on-error-container text-sm px-4 py-3 font-body">
                    {{ $errors->first('email') }}
                </div>
            @endif

            <form class="space-y-6" method="POST" action="{{ route('password.store') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}" />
                <input type="hidden" name="email" value="{{ old('email', $request->email) }}" />

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-primary" for="password">كلمة المرور الجديدة</label>
                    <div class="relative group">
                        <input
                            class="w-full px-4 py-4 bg-surface-container-highest border-none rounded-xl focus:ring-2 focus:ring-primary-container transition-all duration-300 placeholder:text-outline/50"
                            id="password"
                            name="password"
                            type="password"
                            placeholder="••••••••"
                            required
                            autocomplete="new-password"
                        />
                        <button class="absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant hover:text-primary transition-colors toggle-password" data-target="password" type="button">
                            <span class="material-symbols-outlined">visibility</span>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-sm text-error font-body mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-primary" for="password_confirmation">تأكيد كلمة المرور الجديدة</label>
                    <div class="relative group">
                        <input
                            class="w-full px-4 py-4 bg-surface-container-highest border-none rounded-xl focus:ring-2 focus:ring-primary-container transition-all duration-300 placeholder:text-outline/50"
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            placeholder="••••••••"
                            required
                            autocomplete="new-password"
                        />
                        <button class="absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant hover:text-primary transition-colors toggle-password" data-target="password_confirmation" type="button">
                            <span class="material-symbols-outlined">visibility</span>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p class="text-sm text-error font-body mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-surface-container-low p-4 rounded-xl space-y-2">
                    <div class="flex items-center gap-2 text-xs text-secondary font-medium">
                        <span class="material-symbols-outlined text-[16px]">info</span>
                        <span>يجب أن تحتوي كلمة المرور على:</span>
                    </div>
                    <ul class="grid grid-cols-2 gap-2 text-[11px] text-on-surface-variant">
                        <li class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px] text-primary" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                            8 أحرف على الأقل
                        </li>
                        <li class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px] text-outline-variant">radio_button_unchecked</span>
                            حرف كبير واحد
                        </li>
                        <li class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px] text-outline-variant">radio_button_unchecked</span>
                            رمز خاص واحد
                        </li>
                        <li class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px] text-outline-variant">radio_button_unchecked</span>
                            رقم واحد على الأقل
                        </li>
                    </ul>
                </div>

                <button class="w-full luxury-gradient text-white py-4 rounded-full font-bold text-lg shadow-lg hover:shadow-primary-container/20 hover:scale-[1.01] transition-all duration-300" type="submit">
                    تحديث كلمة المرور
                </button>

                <div class="pt-8 text-center lg:text-right">
                    <a class="inline-flex items-center text-primary font-bold hover:text-primary-container transition-colors group" href="{{ route('login') }}">
                        <span class="material-symbols-outlined text-[20px] ml-2 group-hover:translate-x-1 transition-transform">keyboard_tab</span>
                        <span class="font-body">العودة لتسجيل الدخول</span>
                    </a>
                </div>

                {{-- خط طولي --}}
                <div class="grid grid-cols-2 gap-4 mt-12 opacity-40">
                    <div class="h-1 bg-surface-container-high rounded-full"></div>
                    <div class="h-1 bg-surface-container-high rounded-full w-1/2"></div>
                </div>
            </form>
        </div>

        <footer class="mt-16 flex items-center justify-between text-xs font-medium text-outline">
            <div class="flex gap-6">
                <a class="hover:text-primary transition-colors" href="{{ route('privacy.index') }}">سياسة الخصوصية</a>
                <a class="hover:text-primary transition-colors" href="{{ route('help.index') }}">مركز المساعدة</a>
            </div>
            <div class="hidden sm:block">
                © 2024 المتجر الراقي. جميع الحقوق محفوظة.
            </div>
        </footer>
    </section>

    <section class="hidden lg:block w-1/2 relative overflow-hidden bg-primary-container">
        <div class="absolute inset-0 bg-gradient-to-br from-primary via-primary-container to-secondary-container"></div>
        <div class="absolute inset-0 opacity-35 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.22),transparent_30%),radial-gradient(circle_at_bottom_left,rgba(255,255,255,0.12),transparent_28%)]"></div>
        <div class="absolute inset-0 flex items-center justify-center p-12">
            <div class="bg-surface/10 backdrop-blur-xl p-10 rounded-[2.5rem] border border-white/10 max-w-lg">
                <span class="material-symbols-outlined text-secondary-container mb-4 text-4xl" style="font-variation-settings: 'FILL' 1;">security</span>
                <h2 class="text-4xl font-headline font-extrabold text-white mb-6 leading-tight">أمانك هو أولويتنا القصوى</h2>
                <p class="text-white/80 text-lg leading-relaxed font-light">
                    نحن نستخدم أحدث تقنيات التشفير لضمان حماية بياناتك الشخصية وخصوصية حسابك في كل مرة تتفاعل فيها مع المتجر الراقي.
                </p>
                <div class="mt-8 flex items-center gap-4">
                    <div class="flex -space-x-3 space-x-reverse">
                        <span class="w-10 h-10 rounded-full border-2 border-primary-container bg-white/15 text-white text-xs font-bold inline-flex items-center justify-center">A</span>
                        <span class="w-10 h-10 rounded-full border-2 border-primary-container bg-white/15 text-white text-xs font-bold inline-flex items-center justify-center">R</span>
                        <span class="w-10 h-10 rounded-full border-2 border-primary-container bg-white/15 text-white text-xs font-bold inline-flex items-center justify-center">C</span>
                    </div>
                    <p class="text-white/60 text-sm">أكثر من 10,000 عميل يثقون بنا</p>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 p-12 w-full flex justify-between items-end">
            <div class="flex gap-4">
                <div class="w-2 h-2 rounded-full bg-white/20"></div>
                <div class="w-12 h-2 rounded-full bg-secondary-container"></div>
                <div class="w-2 h-2 rounded-full bg-white/20"></div>
            </div>
            {{-- <div class="text-white/40 text-xs font-headline uppercase tracking-[0.2em]">The Elevated Merchant / Atelier</div> --}}
        </div>
    </section>
</main>

<div class="fixed bottom-8 left-8 bg-primary text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3 transform translate-y-32 transition-transform duration-500 pointer-events-none opacity-0" id="toast">
    <span class="material-symbols-outlined text-green-400" style="font-variation-settings: 'FILL' 1;">check_circle</span>
    <span class="font-medium">تم تحديث كلمة المرور بنجاح!</span>
</div>

<script>
    document.querySelectorAll('.toggle-password').forEach(function(button) {
        button.addEventListener('click', function() {
            var target = document.getElementById(button.dataset.target);
            if (!target) return;
            var isPassword = target.type === 'password';
            target.type = isPassword ? 'text' : 'password';
            button.querySelector('.material-symbols-outlined').textContent = isPassword ? 'visibility_off' : 'visibility';
        });
    });
</script>
</body>
</html>
