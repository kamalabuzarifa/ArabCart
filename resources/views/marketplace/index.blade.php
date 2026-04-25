@extends('layouts.main')

@section('title', config('app.name', 'ArabCart') . ' | السوق المفتوح')
@section('nav-active', 'marketplace')

@php
    $limit = 3;
    $gridColumnsClass = (int) $limit === 3 ? 'lg:grid-cols-3' : 'lg:grid-cols-4';
    $categoryNameMap = collect($categories)->mapWithKeys(static fn (array $category): array => [
        $category['slug'] => $category['name'],
    ]);
@endphp

@section('sidebar')
    <div class="px-6 pt-6">
        <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined">storefront</span>
            <span>منتجاتي</span>
        </h3>
        <a href="{{ route('marketplace.index') }}" class="block text-sm px-3 py-2 rounded-lg {{ $selectedCategory ? 'text-slate-600 hover:bg-surface-container-high' : 'bg-primary text-white font-bold' }}">كل المنتجات</a>
        <div class="mt-2 space-y-1">
            @foreach ($categories as $category)
                <a href="{{ route('marketplace.index', ['category' => $category['slug']]) }}" class="flex items-center gap-2 text-sm px-3 py-2 rounded-lg {{ $selectedCategory === $category['slug'] ? 'bg-primary text-white font-bold' : 'text-slate-600 hover:bg-surface-container-high' }}">
                    <span class="material-symbols-outlined text-base">{{ $category['icon'] }}</span>
                    <span>{{ $category['name'] }}</span>
                </a>
            @endforeach
        </div>
    </div>

@endsection

@section('content')
    <section class="px-6 md:px-10">
        <div class="rounded-xl bg-gradient-to-l from-primary to-primary-container text-white p-8 md:p-10 shadow-[0_8px_30px_rgb(0,0,0,0.08)]">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
                <div>
                    <h1 class="text-2xl md:text-4xl font-black mb-3">منتجاتي</h1>
                    <p class="text-primary-fixed mb-6 max-w-2xl">هنا تظهر المنتجات التي أضفتها أنت فقط، ويمكنك تعديلها أو حذفها بسرعة.</p>
                    <div class="flex gap-3 flex-wrap">
                        @auth
                            <a href="{{ route('marketplace.create') }}" class="px-5 py-2 rounded-full bg-secondary-container text-on-secondary-container font-bold">أضف منتجًا جديدًا</a>
                        @else
                            <a href="{{ route('login') }}" class="px-5 py-2 rounded-full bg-secondary-container text-on-secondary-container font-bold">سجّل وابدأ البيع</a>
                        @endauth
                    </div>
                </div>

                <div class="md:min-w-[150px] flex items-center ">
                    <div class="rounded-xl bg-white/10 backdrop-blur px-4 py-3 text-right">
                        <p class="text-xs text-primary-fixed/80">عدد منتجاتي</p>
                        <p class="text-2xl font-black text-center">{{ number_format($ownCount) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="px-6 md:px-10 py-10 page-container">
        @if (session('status'))
            @php
                $statusMessage = (string) session('status');
                $isDeleteStatus = str_contains($statusMessage, 'حذف');
                $toastClasses = $isDeleteStatus
                    ? 'border-red-200 bg-red-50/95 text-red-800 shadow-[0_18px_48px_rgba(239,68,68,0.22)]'
                    : 'border-emerald-200 bg-emerald-50/95 text-emerald-800 shadow-[0_18px_48px_rgba(16,185,129,0.22)]';
                $toastIcon = $isDeleteStatus ? 'delete' : 'check_circle';
                $iconClasses = $isDeleteStatus ? 'text-red-600' : 'text-emerald-600';
            @endphp
            <div id="marketplace-status-toast" class="fixed bottom-6 left-6 z-50 max-w-sm rounded-2xl border px-5 py-4 text-sm font-bold backdrop-blur transition-all duration-300 {{ $toastClasses }}" role="status" aria-live="polite">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined {{ $iconClasses }}">{{ $toastIcon }}</span>
                    <p class="leading-6">{{ $statusMessage }}</p>
                </div>
            </div>
        @endif

        <div id="marketplace-grid" class="grid grid-cols-1 sm:grid-cols-2 {{ $gridColumnsClass }} gap-8 rtl-product-grid">
            @forelse ($ownListings as $listing)
                <x-product-card
                    class="product-card-groups marketplace-own-card"
                    data-order="{{ $loop->index }}"
                    :title="$listing->title"
                    :image="route('marketplace.image', $listing)"
                    :category="$categoryNameMap[$listing->category_slug] ?? ($listing->category_slug ?? '')"
                    :description="$listing->description"
                    :price="number_format((float) ($listing->has_offer && $listing->offer_price ? $listing->offer_price : $listing->price)) . ' ر.س'"
                    :old-price="$listing->has_offer && $listing->offer_price ? number_format((float) $listing->price) . ' ر.س' : null"
                    :rating="$listing->rating_avg ?? 0"
                    :rating-count="$listing->rating_count ?? 0"
                    :sales-count="$listing->sales_count ?? 0"
                    :show-favorite="false"
                    :show-cart="false"
                    heading-tag="h2"
                >
                    <x-slot:actions>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('marketplace.edit', $listing) }}" class="btn-outline-unified text-sm px-4 py-2">تعديل</a>
                            <form method="POST" action="{{ route('marketplace.destroy', $listing) }}" class="marketplace-delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm px-4 py-2 rounded-full bg-red-50 text-red-700 font-bold hover:bg-red-100 transition-colors">حذف</button>
                            </form>
                        </div>
                    </x-slot:actions>
                </x-product-card>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-outline-variant p-8 text-center text-on-surface-variant">
                    لا توجد لديك منتجات بعد. أضف أول منتج الآن.
                </div>
            @endforelse
        </div>

        <div class="mt-16 flex justify-center items-center gap-4 {{ $ownListings->isEmpty() ? 'hidden' : '' }}" id="marketplace-pagination">
            <button id="marketplace-page-prev" class="w-10 h-10 rounded-full border border-outline-variant flex items-center justify-center hover:bg-surface-container-high transition-colors disabled:opacity-40 disabled:cursor-not-allowed" type="button"><span class="material-symbols-outlined">chevron_right</span></button>
            <div id="marketplace-page-numbers" class="flex items-center gap-2"></div>
            <button id="marketplace-page-next" class="w-10 h-10 rounded-full border border-outline-variant flex items-center justify-center hover:bg-surface-container-high transition-colors disabled:opacity-40 disabled:cursor-not-allowed" type="button"><span class="material-symbols-outlined">chevron_left</span></button>
        </div>

        <div id="marketplace-delete-modal" class="fixed inset-0 z-[60] hidden" aria-hidden="true">
            <div class="absolute inset-0 bg-black/50" data-delete-modal-close></div>
            <div class="absolute inset-0 flex items-center justify-center p-4">
                <div class="w-full max-w-md rounded-3xl border border-red-200 bg-white p-6 md:p-7 shadow-[0_24px_80px_rgba(15,23,42,0.25)] text-right">
                    <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-full bg-red-100 text-red-700">
                        <span class="material-symbols-outlined">delete</span>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-2">تأكيد حذف المنتج</h3>
                    <p id="marketplace-delete-modal-message" class="text-sm leading-7 text-slate-600 mb-6">هل أنت متأكد من حذف هذا المنتج؟ لا يمكن التراجع بعد الحذف.</p>
                    <div class="flex items-center justify-end gap-3">
                        <button type="button" id="marketplace-delete-cancel" class="px-5 py-2.5 rounded-full bg-slate-100 text-slate-700 font-bold hover:bg-slate-200 transition-colors">إلغاء</button>
                        <button type="button" id="marketplace-delete-confirm" class="px-5 py-2.5 rounded-full bg-red-600 text-white font-bold hover:bg-red-700 transition-colors">نعم، احذف</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('page-scripts')
    <script>
        (function () {
            var statusToast = document.getElementById('marketplace-status-toast');
            var deleteForms = document.querySelectorAll('.marketplace-delete-form');
            var deleteModal = document.getElementById('marketplace-delete-modal');
            var deleteModalMessage = document.getElementById('marketplace-delete-modal-message');
            var deleteConfirmButton = document.getElementById('marketplace-delete-confirm');
            var deleteCancelButton = document.getElementById('marketplace-delete-cancel');
            var deleteCloseTriggers = document.querySelectorAll('[data-delete-modal-close]');
            var cards = document.querySelectorAll('.marketplace-own-card');
            var prevPageButton = document.getElementById('marketplace-page-prev');
            var nextPageButton = document.getElementById('marketplace-page-next');
            var pageNumbersContainer = document.getElementById('marketplace-page-numbers');
            var pagination = document.getElementById('marketplace-pagination');
            var currentPage = 1;
            var pageSize = 3;
            var pendingDeleteForm = null;

            function closeDeleteModal() {
                if (!deleteModal) return;

                deleteModal.classList.add('hidden');
                deleteModal.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('overflow-hidden');
                pendingDeleteForm = null;
            }

            function openDeleteModal(form) {
                if (!deleteModal) {
                    form.submit();
                    return;
                }

                pendingDeleteForm = form;

                if (deleteModalMessage) {
                    deleteModalMessage.textContent = 'هل أنت متأكد من حذف هذا المنتج؟ لا يمكن التراجع بعد الحذف.';
                }

                deleteModal.classList.remove('hidden');
                deleteModal.setAttribute('aria-hidden', 'false');
                document.body.classList.add('overflow-hidden');
            }

            function renderPagination(totalPages) {
                if (!pageNumbersContainer) return;

                pageNumbersContainer.innerHTML = '';

                for (var pageNumber = 1; pageNumber <= totalPages; pageNumber += 1) {
                    var pageButton = document.createElement('button');
                    pageButton.type = 'button';
                    pageButton.dataset.page = String(pageNumber);
                    pageButton.textContent = pageNumber.toLocaleString('ar-EG');
                    pageButton.className = 'w-10 h-10 flex items-center justify-center rounded-full transition-colors';

                    if (currentPage === pageNumber) {
                        pageButton.classList.add('bg-primary', 'text-white', 'font-bold');
                    } else {
                        pageButton.classList.add('text-on-surface-variant', 'hover:bg-surface-container-high');
                    }

                    pageNumbersContainer.appendChild(pageButton);
                }
            }

            function applyPagination() {
                var totalCards = cards.length;
                var totalPages = Math.max(1, Math.ceil(totalCards / pageSize));

                if (currentPage > totalPages) {
                    currentPage = totalPages;
                }

                if (pagination) {
                    pagination.classList.toggle('hidden', totalCards <= pageSize);
                }

                cards.forEach(function (card) {
                    card.style.display = 'none';
                });

                var startIndex = (currentPage - 1) * pageSize;
                var endIndex = startIndex + pageSize;
                Array.prototype.slice.call(cards, startIndex, endIndex).forEach(function (card) {
                    card.style.display = 'flex';
                });

                renderPagination(totalPages);

                if (prevPageButton) {
                    prevPageButton.disabled = currentPage <= 1;
                }

                if (nextPageButton) {
                    nextPageButton.disabled = currentPage >= totalPages;
                }
            }

            if (pageNumbersContainer) {
                pageNumbersContainer.addEventListener('click', function (event) {
                    var button = event.target.closest('button[data-page]');
                    if (!button) return;

                    currentPage = Number(button.dataset.page || '1');
                    applyPagination();
                });
            }

            if (prevPageButton) {
                prevPageButton.addEventListener('click', function () {
                    if (currentPage > 1) {
                        currentPage -= 1;
                        applyPagination();
                    }
                });
            }

            if (nextPageButton) {
                nextPageButton.addEventListener('click', function () {
                    currentPage += 1;
                    applyPagination();
                });
            }

            if (statusToast) {
                window.setTimeout(function () {
                    statusToast.classList.add('opacity-0', 'translate-y-2');

                    window.setTimeout(function () {
                        statusToast.remove();
                    }, 300);
                }, 2000);
            }

            deleteForms.forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();
                    openDeleteModal(form);
                });
            });

            if (deleteConfirmButton) {
                deleteConfirmButton.addEventListener('click', function () {
                    if (!pendingDeleteForm) {
                        closeDeleteModal();
                        return;
                    }

                    var form = pendingDeleteForm;
                    closeDeleteModal();
                    form.submit();
                });
            }

            if (deleteCancelButton) {
                deleteCancelButton.addEventListener('click', closeDeleteModal);
            }

            deleteCloseTriggers.forEach(function (trigger) {
                trigger.addEventListener('click', closeDeleteModal);
            });

            window.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && deleteModal && !deleteModal.classList.contains('hidden')) {
                    closeDeleteModal();
                }
            });

            applyPagination();
        })();
    </script>
@endpush
