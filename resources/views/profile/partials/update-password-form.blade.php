<section>
    <header>
        <h2 class="headline-font text-lg font-bold text-on-surface text-right">
            تغيير كلمة المرور
        </h2>

        <p class="mt-1 text-sm text-on-surface-variant text-right">
            استخدم كلمة مرور قوية وفريدة للحفاظ على أمان حسابك.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('كلمة المرور الحالية')" class="text-on-surface text-right" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full rounded-xl border-outline-variant focus:border-primary focus:ring-primary text-right" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('كلمة المرور الجديدة')" class="text-on-surface text-right" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full rounded-xl border-outline-variant focus:border-primary focus:ring-primary text-right" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('تأكيد كلمة المرور')" class="text-on-surface text-right" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full rounded-xl border-outline-variant focus:border-primary focus:ring-primary text-right" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 justify-start">
            <x-primary-button class="!bg-primary hover:!bg-primary-container focus:!ring-primary">
                حفظ كلمة المرور
            </x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-on-surface-variant"
                >تم تحديث كلمة المرور.</p>
            @endif
        </div>
    </form>
</section>
