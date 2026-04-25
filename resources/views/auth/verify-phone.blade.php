<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>تحقق رقم الهاتف | {{ config('app.name', 'ArabCart') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;family=Manrope:wght@400;500;600;700&amp;family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
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
    </style>
</head>
<body class="bg-[#f7f6ff] text-on-surface min-h-screen overflow-x-hidden">
<main class="min-h-screen flex items-center justify-center p-4 md:p-6">
    <section class="w-full max-w-4xl rounded-[28px] bg-white shadow-[0_14px_50px_rgba(107,78,255,0.12)] border border-[#ece9ff] p-5 md:p-8 lg:p-10 text-right">
        <div class="mx-auto flex w-full max-w-2xl flex-col items-center text-center">
            <div class="mb-8 flex items-center justify-center rounded-full bg-[radial-gradient(circle,rgba(119,84,255,0.18),rgba(119,84,255,0.03))] p-6">
                <div class="relative flex items-center justify-center">
                    <div class="absolute inset-0 rounded-full bg-[#7c5cff]/10 blur-2xl"></div>
                    <span class="material-symbols-outlined relative text-[140px] leading-none text-[#5f3fd7] drop-shadow-[0_10px_24px_rgba(95,63,215,0.24)]">shield</span>
                    <span class="absolute -bottom-2 -right-2 flex h-12 w-12 items-center justify-center rounded-full border-4 border-white bg-[#6b5cf6] text-white shadow-lg">
                        <span class="material-symbols-outlined text-[28px]">check</span>
                    </span>
                </div>
            </div>

            <h1 class="font-headline text-[clamp(2rem,4vw,3.4rem)] font-extrabold tracking-tight text-slate-900 mb-4">
                تحقق من رقم الهاتف
            </h1>
            <p class="max-w-2xl text-[1.02rem] leading-8 text-slate-600 mb-8">
                تم إنشاء رمز تحقق تجريبي للرقم {{ $phone_display ?? $phone }}. أدخل الرمز لإكمال التفعيل والوصول إلى حسابك.
            </p>

            @if (session('status') === 'phone-verification-code-sent')
                <div class="mb-6 w-full max-w-[520px] rounded-[18px] border border-primary/15 bg-primary/5 px-4 py-3 text-sm text-primary">
                    تم إنشاء رمز تحقق تجريبي جديد.
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 w-full max-w-[520px] rounded-[18px] border border-error/20 bg-error-container px-4 py-3 text-sm text-on-error-container text-right">
                    <ul class="space-y-1 list-disc pr-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="w-full max-w-[520px] space-y-6" method="POST" action="{{ route('phone.verification.verify') }}" data-otp-form>
                @csrf
                <input type="hidden" name="code" value="{{ old('code') }}" data-otp-hidden />

                <div class="space-y-2 text-right">
                    <label class="block text-base font-bold text-slate-900" for="code-0">رمز التحقق</label>
                    <p class="text-sm text-slate-500">أدخل الرمز المكون من 6 أرقام</p>
                </div>

                <div class="flex flex-wrap items-center justify-center gap-3 md:gap-4" dir="ltr" data-otp-group>
                    @for ($index = 0; $index < 6; $index++)
                        <input
                            id="code-{{ $index }}"
                            type="text"
                            inputmode="numeric"
                            maxlength="1"
                            autocomplete="one-time-code"
                            class="h-16 w-16 rounded-2xl border border-[#d9d7e9] bg-white text-center text-2xl font-extrabold text-slate-900 shadow-[0_4px_18px_rgba(15,23,42,0.04)] outline-none transition focus:border-[#6b4ef6] focus:ring-4 focus:ring-[#6b4ef6]/10"
                            data-otp-digit
                        />
                    @endfor
                </div>

                <button class="w-full rounded-2xl bg-[#6b4ef6] py-4 text-lg font-bold text-white shadow-[0_16px_28px_rgba(107,78,255,0.22)] transition hover:bg-[#5e41ea] hover:shadow-[0_18px_34px_rgba(107,78,255,0.28)] active:scale-[0.99]" type="submit">
                    تأكيد رقم الهاتف
                </button>
            </form>

            <div class="mt-6 flex flex-col items-center gap-4 sm:flex-row sm:justify-center sm:gap-10">
                <form method="POST" action="{{ route('phone.verification.send') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 text-base font-semibold text-[#6b4ef6] hover:underline">
                        <span class="material-symbols-outlined text-[20px]">refresh</span>
                        إعادة إرسال الرمز
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 text-base font-semibold text-[#ef4444] hover:underline">
                        <span class="material-symbols-outlined text-[20px]">logout</span>
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>
    </section>
</main>

<script>
    (function () {
        const form = document.querySelector('[data-otp-form]');
        if (!form) return;

        const hiddenInput = form.querySelector('[data-otp-hidden]');
        const digits = Array.from(form.querySelectorAll('[data-otp-digit]'));

        const syncHidden = () => {
            hiddenInput.value = digits.map((input) => input.value.trim()).join('').slice(0, 6);
        };

        const fillFromValue = (value) => {
            const numbers = value.replace(/\D/g, '').slice(0, 6).split('');
            digits.forEach((input, index) => {
                input.value = numbers[index] || '';
            });
            syncHidden();
        };

        digits.forEach((input, index) => {
            input.addEventListener('input', () => {
                input.value = input.value.replace(/\D/g, '').slice(0, 1);
                if (input.value && index < digits.length - 1) {
                    digits[index + 1].focus();
                }
                syncHidden();
            });

            input.addEventListener('keydown', (event) => {
                if (event.key === 'Backspace' && !input.value && index > 0) {
                    digits[index - 1].focus();
                }
            });

            input.addEventListener('paste', (event) => {
                event.preventDefault();
                const pasted = (event.clipboardData || window.clipboardData).getData('text');
                fillFromValue(pasted);
                const targetIndex = Math.min(pasted.replace(/\D/g, '').length, digits.length - 1);
                digits[targetIndex].focus();
            });
        });

        const existingValue = hiddenInput.value || @json($debug_code ?? '');
        if (existingValue) {
            fillFromValue(String(existingValue));
        }
    })();
</script>
</body>
</html>