@extends('layouts.main')

@section('title', config('app.name', 'ArabCart') . ' | تعديل المنتج')
@section('nav-active', 'marketplace')

@section('content')
    <section class="page-container py-8">
        <div class="max-w-3xl mx-auto w-full">
            <div class="mb-8 text-center">
                <h1 class="section-title">تعديل المنتج</h1>
                <p class="page-subtitle">حدّث بيانات منتجك ثم احفظ التغييرات.</p>
            </div>

            <form method="POST" action="{{ route('marketplace.update', $listing) }}" enctype="multipart/form-data" class="space-y-5 bg-white border border-outline-variant/20 rounded-xl p-6 md:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-bold mb-2" for="title">اسم المنتج</label>
                    <input id="title" name="title" type="text" value="{{ old('title', $listing->title) }}" required class="w-full surface-input" />
                    @error('title') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold mb-2" for="description">وصف المنتج</label>
                    <textarea id="description" name="description" rows="5" required class="w-full surface-input">{{ old('description', $listing->description) }}</textarea>
                    @error('description') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold mb-2" for="price">السعر (ر.س)</label>
                        <input id="price" name="price" type="number" min="1" step="0.01" value="{{ old('price', (float) $listing->price) }}" required class="w-full surface-input" />
                        @error('price') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold mb-2" for="category_slug">التصنيف</label>
                        <select id="category_slug" name="category_slug" class="w-full surface-input">
                            <option value="">بدون تصنيف</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category['slug'] }}" @selected(old('category_slug', $listing->category_slug) === $category['slug'])>{{ $category['name'] }}</option>
                            @endforeach
                        </select>
                        @error('category_slug') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-3 rounded-xl border border-outline-variant/20 bg-surface-container-low/40 p-4">
                    <label class="inline-flex items-center gap-2 text-sm font-bold text-on-surface" for="has_offer">
                        <input id="has_offer" name="has_offer" type="checkbox" value="1" @checked(old('has_offer', $listing->has_offer)) class="rounded border-outline-variant text-primary focus:ring-primary" />
                        المنتج عليه عرض
                    </label>
                    @error('has_offer') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror

                    <div id="offer-price-wrapper" class="{{ old('has_offer', $listing->has_offer) ? '' : 'hidden' }}">
                        <label class="block text-sm font-bold mb-2" for="offer_price">سعر العرض (ر.س)</label>
                        <input id="offer_price" name="offer_price" type="number" min="1" step="0.01" value="{{ old('offer_price', $listing->offer_price) }}" class="w-full surface-input" />
                        <p class="text-xs text-on-surface-variant mt-1">يجب أن يكون سعر العرض أقل من السعر الأساسي.</p>
                        @error('offer_price') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-4 rounded-xl border border-outline-variant/20 bg-surface-container-low/40 p-4">
                    <p class="text-sm font-bold text-primary">صورة المنتج</p>

                    @if ($listing->image_url)
                        <div class="rounded-lg border border-outline-variant/20 p-3 bg-white">
                            <p class="text-xs text-on-surface-variant mb-2">الصورة الحالية</p>
                            <img src="{{ route('marketplace.image', $listing) }}" alt="{{ $listing->title }}" class="w-24 h-24 object-cover rounded-lg" />
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-bold mb-2" for="image_file">رفع صورة جديدة من جهازك</label>
                        <input id="image_file" name="image_file" type="file" accept="image/*" class="sr-only" />

                        <div id="image-dropzone" class="group relative rounded-xl border-2 border-dashed border-outline-variant bg-white p-5 text-center transition-all duration-200 hover:border-primary/60 hover:bg-surface-container-lowest/70 cursor-pointer">
                            <div class="pointer-events-none flex flex-col items-center gap-2">
                                <span class="material-symbols-outlined text-4xl text-primary/80">add_photo_alternate</span>
                                <p class="text-sm font-bold text-on-surface">اسحب الصورة هنا وأفلِتها</p>
                                <p class="text-xs text-on-surface-variant">أو اضغط لاختيار صورة جديدة من جهازك (JPG, PNG, WEBP)</p>
                                <p id="image-file-name" class="text-xs font-semibold text-primary hidden"></p>
                            </div>
                        </div>

                        <div id="image-preview-wrapper" class="hidden mt-3 rounded-xl border border-outline-variant/20 bg-white p-3">
                            <img id="image-preview" alt="معاينة الصورة الجديدة" class="w-full max-h-64 object-contain rounded-lg bg-surface-container-low" />
                            <div class="mt-3 flex items-center justify-between">
                                <span class="text-xs text-on-surface-variant">المعاينة قبل حفظ التعديلات</span>
                                <button id="image-clear" type="button" class="text-xs font-bold text-error hover:opacity-80">إزالة الصورة</button>
                            </div>
                        </div>

                        @error('image_file') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <label class="inline-flex items-center gap-2 text-sm text-on-surface-variant">
                        <input type="checkbox" name="remove_image" value="1" @checked(old('remove_image')) class="rounded border-outline-variant text-primary focus:ring-primary" />
                        حذف الصورة الحالية
                    </label>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="btn-primary-unified px-6 py-3">حفظ التعديلات</button>
                    <a href="{{ route('marketplace.index') }}" class="btn-outline-unified px-6 py-3">إلغاء</a>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('page-scripts')
    <script>
        (function () {
            var input = document.getElementById('image_file');
            var dropzone = document.getElementById('image-dropzone');
            var previewWrapper = document.getElementById('image-preview-wrapper');
            var previewImage = document.getElementById('image-preview');
            var fileName = document.getElementById('image-file-name');
            var clearButton = document.getElementById('image-clear');
            var hasOfferToggle = document.getElementById('has_offer');
            var offerPriceWrapper = document.getElementById('offer-price-wrapper');
            var offerPriceInput = document.getElementById('offer_price');

            if (!input || !dropzone || !previewWrapper || !previewImage || !fileName || !clearButton) {
                return;
            }

            function toggleOfferPrice() {
                if (!hasOfferToggle || !offerPriceWrapper || !offerPriceInput) {
                    return;
                }

                var shouldShow = hasOfferToggle.checked;
                offerPriceWrapper.classList.toggle('hidden', !shouldShow);

                if (!shouldShow) {
                    offerPriceInput.value = '';
                }
            }

            function setDropzoneState(isDragging) {
                dropzone.classList.toggle('border-primary', isDragging);
                dropzone.classList.toggle('bg-primary/5', isDragging);
            }

            function updatePreview(file) {
                if (!file || !file.type.startsWith('image/')) {
                    previewWrapper.classList.add('hidden');
                    fileName.classList.add('hidden');
                    fileName.textContent = '';
                    previewImage.removeAttribute('src');
                    return;
                }

                var objectUrl = URL.createObjectURL(file);
                previewImage.src = objectUrl;
                previewImage.onload = function () {
                    URL.revokeObjectURL(objectUrl);
                };

                fileName.textContent = 'الملف المختار: ' + file.name;
                fileName.classList.remove('hidden');
                previewWrapper.classList.remove('hidden');
            }

            function assignFile(file) {
                if (!file || !file.type.startsWith('image/')) {
                    return;
                }

                var transfer = new DataTransfer();
                transfer.items.add(file);
                input.files = transfer.files;
                updatePreview(file);
            }

            dropzone.addEventListener('click', function () {
                input.click();
            });

            input.addEventListener('change', function () {
                updatePreview(input.files[0] || null);
            });

            dropzone.addEventListener('dragenter', function (event) {
                event.preventDefault();
                setDropzoneState(true);
            });

            dropzone.addEventListener('dragover', function (event) {
                event.preventDefault();
                setDropzoneState(true);
            });

            dropzone.addEventListener('dragleave', function (event) {
                event.preventDefault();
                if (!dropzone.contains(event.relatedTarget)) {
                    setDropzoneState(false);
                }
            });

            dropzone.addEventListener('drop', function (event) {
                event.preventDefault();
                setDropzoneState(false);
                assignFile(event.dataTransfer && event.dataTransfer.files ? event.dataTransfer.files[0] : null);
            });

            clearButton.addEventListener('click', function () {
                input.value = '';
                updatePreview(null);
            });

            if (hasOfferToggle) {
                toggleOfferPrice();
                hasOfferToggle.addEventListener('change', toggleOfferPrice);
            }
        })();
    </script>
@endpush
