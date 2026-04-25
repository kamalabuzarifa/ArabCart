<section>
    <header>
        <h2 class="headline-font text-lg font-bold text-on-surface text-right">
            معلومات الحساب
        </h2>

        <p class="mt-1 text-sm text-on-surface-variant text-right">
            عدّل الاسم والبريد الإلكتروني المرتبطين بحسابك.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="flex flex-col items-center gap-4 rounded-2xl bg-surface-container-low p-5">
            <div class="relative">
                @if ($user->avatar_url)
                    <img id="avatar-preview" src="{{ $user->avatar_url }}" alt="صورة الحساب" class="w-24 h-24 rounded-full object-cover ring-4 ring-white shadow-md" />
                @else
                    <img id="avatar-preview" src="" alt="صورة الحساب" class="hidden w-24 h-24 rounded-full object-cover ring-4 ring-white shadow-md" />
                    <div id="avatar-fallback" class="w-24 h-24 rounded-full bg-primary flex items-center justify-center text-white text-3xl font-bold ring-4 ring-white shadow-md">
                        {{ mb_substr($user->name, 0, 1) }}
                    </div>
                @endif
            </div>

            <div class="w-full">
                <x-input-label for="avatar" :value="__('الصورة الشخصية')" class="text-on-surface text-right" />
                <input id="avatar" name="avatar" type="file" accept=".jpg,.jpeg,.png,.webp" class="mt-1 block w-full rounded-xl border border-outline-variant bg-white text-sm text-on-surface file:ml-3 file:rounded-lg file:border-0 file:bg-primary file:px-4 file:py-2 file:text-white file:font-bold hover:file:bg-primary-container" />
                <p id="avatar-file-name" class="mt-2 text-xs text-on-surface-variant text-right">لم يتم اختيار ملف.</p>
                <p class="mt-2 text-xs text-on-surface-variant text-right">الأنواع المدعومة: JPG, PNG, WEBP. الحجم الأقصى 2MB.</p>
                <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
            </div>
        </div>

        <div>
            <x-input-label for="name" :value="__('الاسم')" class="text-on-surface text-right" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full rounded-xl border-outline-variant focus:border-primary focus:ring-primary text-right" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('البريد الإلكتروني')" class="text-on-surface text-right" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full rounded-xl border-outline-variant focus:border-primary focus:ring-primary text-right" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        بريدك الإلكتروني غير موثق.

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            اضغط هنا لإعادة إرسال رابط التحقق.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            تم إرسال رابط تحقق جديد إلى بريدك الإلكتروني.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 justify-start">
            <x-primary-button class="!bg-primary hover:!bg-primary-container focus:!ring-primary">
                حفظ التغييرات
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-on-surface-variant"
                >تم الحفظ بنجاح.</p>
            @endif
        </div>
    </form>

    <script>
        (function () {
            const avatarInput = document.getElementById('avatar');
            if (!avatarInput) {
                return;
            }

            const fileNameLabel = document.getElementById('avatar-file-name');
            const avatarPreview = document.getElementById('avatar-preview');
            const avatarFallback = document.getElementById('avatar-fallback');

            avatarInput.addEventListener('change', function () {
                const file = this.files && this.files[0] ? this.files[0] : null;

                if (!file) {
                    if (fileNameLabel) {
                        fileNameLabel.textContent = 'لم يتم اختيار ملف.';
                    }
                    return;
                }

                if (fileNameLabel) {
                    fileNameLabel.textContent = file.name;
                }

                if (!avatarPreview) {
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (event) {
                    avatarPreview.src = String(event.target?.result || '');
                    avatarPreview.classList.remove('hidden');
                    if (avatarFallback) {
                        avatarFallback.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(file);
            });
        })();
    </script>
</section>
