<section class="space-y-6">
    <header>
        <h2 class="headline-font text-lg font-bold text-error text-right">
            حذف الحساب
        </h2>

        <p class="mt-1 text-sm text-on-surface-variant text-right">
            عند حذف الحساب سيتم إزالة جميع بياناتك نهائياً ولا يمكن استرجاعها لاحقاً.
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    class="!bg-error !text-white hover:opacity-90"
    >حذف الحساب</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-on-surface text-right">
                هل أنت متأكد من حذف الحساب؟
            </h2>

            <p class="mt-1 text-sm text-on-surface-variant text-right">
                لتأكيد العملية، أدخل كلمة المرور. سيتم حذف الحساب وكل البيانات المرتبطة به بشكل دائم.
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full rounded-xl border-outline-variant focus:border-error focus:ring-error text-right"
                    placeholder="كلمة المرور"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-start gap-3">
                <x-secondary-button x-on:click="$dispatch('close')" class="!rounded-xl !border-outline-variant !text-on-surface">
                    إلغاء
                </x-secondary-button>

                <x-danger-button class="!rounded-xl !bg-error !text-white hover:opacity-90">
                    تأكيد الحذف
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
