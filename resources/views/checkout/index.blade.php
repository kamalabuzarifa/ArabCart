@extends('layouts.main')

@section('title', config('app.name', 'ArabCart') . ' | إتمام الطلب')
@section('nav-active', 'groups')

@section('content')
    <section class="px-6 max-w-7xl mx-auto pb-12 pt-16">
        <div class="mb-10 max-w-3xl mx-auto">
            <div id="checkout-stepper" class="flex items-center justify-between relative">
                <div class="absolute top-1/2 left-0 w-full h-0.5 bg-surface-container-highest -z-10 -translate-y-1/2"></div>
                <button type="button" data-step-trigger="1" class="flex flex-col items-center gap-2 cursor-pointer">
                    <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold ring-4 ring-primary/10">1</div>
                    <span class="text-sm font-bold text-primary">معلومات الشحن</span>
                </button>
                <button type="button" data-step-trigger="2" class="flex flex-col items-center gap-2 cursor-pointer">
                    <div class="w-10 h-10 rounded-full bg-surface-container-highest text-outline flex items-center justify-center font-bold">2</div>
                    <span class="text-sm text-outline">طريقة الدفع</span>
                </button>
                <button type="button" data-step-trigger="3" class="flex flex-col items-center gap-2 cursor-pointer">
                    <div class="w-10 h-10 rounded-full bg-surface-container-highest text-outline flex items-center justify-center font-bold">3</div>
                    <span class="text-sm text-outline">مراجعة الطلب</span>
                </button>
            </div>
        </div>

        <div id="checkout-status" class="hidden mb-6 rounded-xl px-4 py-3 text-sm font-bold"></div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <section class="lg:col-span-8 space-y-8">
                <div data-step-panel="1" class="bg-surface-container-lowest rounded-xl p-8 shadow-sm">
                    <div class="flex items-center gap-3 mb-8">
                        <span class="material-symbols-outlined text-primary">local_shipping</span>
                        <h2 class="text-xl font-bold text-primary">عنوان الشحن</h2>
                    </div>
                    <form class="grid grid-cols-1 md:grid-cols-2 gap-6" onsubmit="return false;">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-on-surface-variant">الاسم الكامل</label>
                            <input id="shipping-fullname" class="w-full bg-surface-container-low border-none rounded-lg p-4 focus:ring-2 focus:ring-primary text-right" placeholder="أحمد محمد" type="text" />
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-on-surface-variant">رقم الجوال</label>
                            <input id="shipping-phone" class="w-full bg-surface-container-low border-none rounded-lg p-4 focus:ring-2 focus:ring-primary text-left" dir="ltr" placeholder="+966 5X XXX XXXX" type="tel" />
                        </div>
                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-sm font-semibold text-on-surface-variant">العنوان</label>
                            <input id="shipping-address" class="w-full bg-surface-container-low border-none rounded-lg p-4 focus:ring-2 focus:ring-primary text-right" placeholder="الشارع، الحي" type="text" />
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-on-surface-variant">المدينة</label>
                            <select id="shipping-city" class="w-full bg-surface-container-low border-none rounded-lg p-4 focus:ring-2 focus:ring-primary text-right">
                                <option>الرياض</option>
                                <option>جدة</option>
                                <option>الدمام</option>
                                <option>مكة المكرمة</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-on-surface-variant">الرمز البريدي</label>
                            <input id="shipping-postal" class="w-full bg-surface-container-low border-none rounded-lg p-4 focus:ring-2 focus:ring-primary text-right" placeholder="12345" type="text" />
                        </div>
                    </form>
                </div>

                <div data-step-panel="2" class="bg-surface-container-lowest rounded-xl p-8 shadow-sm hidden">
                    <div class="flex items-center gap-3 mb-8">
                        <span class="material-symbols-outlined text-primary">payments</span>
                        <h2 class="text-xl font-bold text-primary">طريقة الدفع</h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <label class="border-2 border-primary bg-primary/5 rounded-xl p-4 flex items-center gap-4 cursor-pointer" data-payment-option="card">
                            <input checked class="hidden" name="payment_method" type="radio" value="card" />
                            <span class="w-6 h-6 rounded-full border-2 border-primary flex items-center justify-center"><span class="w-3 h-3 bg-primary rounded-full"></span></span>
                            <span class="font-bold text-primary">بطاقة مدى / ائتمانية</span>
                        </label>
                        <label class="border-2 border-transparent bg-surface-container-low rounded-xl p-4 flex items-center gap-4 cursor-pointer hover:bg-surface-container-high transition-colors" data-payment-option="apple">
                            <input class="hidden" name="payment_method" type="radio" value="apple" />
                            <span class="w-6 h-6 rounded-full border-2 border-outline"></span>
                            <span class="font-bold text-on-surface">Apple Pay</span>
                        </label>
                        <label class="border-2 border-transparent bg-surface-container-low rounded-xl p-4 flex items-center gap-4 cursor-pointer hover:bg-surface-container-high transition-colors" data-payment-option="cash">
                            <input class="hidden" name="payment_method" type="radio" value="cash" />
                            <span class="w-6 h-6 rounded-full border-2 border-outline"></span>
                            <span class="font-bold text-on-surface">الدفع عند الاستلام</span>
                        </label>
                    </div>

                    <div id="card-details" class="mt-8 space-y-4 pt-8 border-t border-surface-container-highest">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-on-surface-variant">رقم البطاقة</label>
                            <div class="relative">
                                <input id="card-number" class="w-full bg-surface-container-low border-none rounded-lg p-4 focus:ring-2 focus:ring-primary text-left" dir="ltr" placeholder="XXXX XXXX XXXX XXXX" type="text" />
                                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-outline">credit_card</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-on-surface-variant">تاريخ الانتهاء</label>
                                <input id="card-expiry" class="w-full bg-surface-container-low border-none rounded-lg p-4 focus:ring-2 focus:ring-primary text-left" dir="ltr" placeholder="MM/YY" type="text" />
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-on-surface-variant">رمز التحقق (CVV)</label>
                                <input id="card-cvv" class="w-full bg-surface-container-low border-none rounded-lg p-4 focus:ring-2 focus:ring-primary text-left" dir="ltr" placeholder="XXX" type="text" />
                            </div>
                        </div>
                    </div>
                </div>

                <div data-step-panel="3" class="bg-surface-container-lowest rounded-xl p-8 shadow-sm hidden">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="material-symbols-outlined text-primary">fact_check</span>
                        <h2 class="text-xl font-bold text-primary">مراجعة الطلب</h2>
                    </div>
                    <div class="space-y-4 text-right">
                        <div class="rounded-lg bg-surface-container-low p-4">
                            <p class="text-xs text-on-surface-variant mb-2">بيانات الشحن</p>
                            <p id="review-shipping-name" class="font-bold text-primary">-</p>
                            <p id="review-shipping-phone" class="text-sm text-on-surface-variant mt-1">-</p>
                            <p id="review-shipping-address" class="text-sm text-on-surface mt-2">-</p>
                        </div>
                        <div class="rounded-lg bg-surface-container-low p-4">
                            <p class="text-xs text-on-surface-variant mb-2">طريقة الدفع المختارة</p>
                            <p id="review-payment-method" class="font-bold text-primary">-</p>
                        </div>
                    </div>
                </div>
            </section>

            <aside class="lg:col-span-4 sticky top-24">
                <div class="bg-surface-container-low rounded-xl p-6 space-y-6">
                    <h3 class="text-lg font-bold text-primary">ملخص الطلب</h3>
                    <div id="checkout-items" class="space-y-4"></div>
                    <hr class="border-surface-container-high" />
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-on-surface-variant">المجموع الفرعي</span>
                            <span id="checkout-subtotal" class="font-semibold">0 ر.س</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-on-surface-variant">تكلفة الشحن</span>
                            <span class="text-secondary font-semibold">مجاني</span>
                        </div>
                        <div class="flex justify-between text-sm border-t border-surface-container-high pt-3">
                            <span class="text-lg font-bold text-primary">الإجمالي</span>
                            <span id="checkout-total" class="text-lg font-bold text-primary">0 ر.س</span>
                        </div>
                        <p class="text-[10px] text-on-surface-variant text-center mt-2">الأسعار تشمل ضريبة القيمة المضافة</p>
                    </div>
                    <button id="checkout-submit" class="w-full bg-secondary-container text-on-secondary-container py-4 rounded-full font-extrabold text-lg flex items-center justify-center gap-2 shadow-lg shadow-secondary/20 hover:scale-[1.02] active:scale-95 transition-transform" type="button">
                        <span>إتمام الدفع الآمن</span>
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">lock</span>
                    </button>
                    <button id="checkout-prev" class="w-full border border-outline-variant text-primary py-3 rounded-full font-bold hidden" type="button">السابق</button>
                </div>
            </aside>
        </div>
    </section>
@endsection

@push('page-scripts')
    <script>
        (function () {
            var cartStorageKey = 'arabcart:cart';
            var checkoutItemsKey = 'arabcart:checkout-items';
            var itemsContainer = document.getElementById('checkout-items');
            var totalElement = document.getElementById('checkout-total');
            var subtotalElement = document.getElementById('checkout-subtotal');
            var submitButton = document.getElementById('checkout-submit');
            var prevButton = document.getElementById('checkout-prev');
            var statusBox = document.getElementById('checkout-status');
            var stepTriggers = document.querySelectorAll('[data-step-trigger]');
            var stepPanels = document.querySelectorAll('[data-step-panel]');
            var paymentMethodInputs = document.querySelectorAll('input[name="payment_method"]');
            var paymentMethodOptions = document.querySelectorAll('[data-payment-option]');
            var cardDetails = document.getElementById('card-details');
            var cardNumberInput = document.getElementById('card-number');
            var cardExpiryInput = document.getElementById('card-expiry');
            var cardCvvInput = document.getElementById('card-cvv');
            var shippingNameInput = document.getElementById('shipping-fullname');
            var shippingPhoneInput = document.getElementById('shipping-phone');
            var shippingAddressInput = document.getElementById('shipping-address');
            var shippingCityInput = document.getElementById('shipping-city');
            var shippingPostalInput = document.getElementById('shipping-postal');
            var reviewShippingName = document.getElementById('review-shipping-name');
            var reviewShippingPhone = document.getElementById('review-shipping-phone');
            var reviewShippingAddress = document.getElementById('review-shipping-address');
            var reviewPaymentMethod = document.getElementById('review-payment-method');
            var completeEndpoint = "{{ route('checkout.complete') }}";
            var itemsMetaEndpoint = "{{ route('checkout.items-meta') }}";
            var successEndpoint = "{{ route('checkout.success') }}";
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content') || '';
            var selectedPaymentMethod = 'card';
            var currentStep = 1;
            var descriptionHydrationRequested = false;

            function getPaymentMethodLabel(value) {
                if (value === 'apple') return 'Apple Pay';
                if (value === 'cash') return 'الدفع عند الاستلام';
                return 'بطاقة مدى / ائتمانية';
            }

            function updateStepperUI() {
                stepTriggers.forEach(function (trigger) {
                    var step = Number(trigger.getAttribute('data-step-trigger') || '1');
                    var circle = trigger.querySelector('div');
                    var text = trigger.querySelector('span');
                    var active = step === currentStep;

                    if (circle) {
                        circle.classList.toggle('bg-primary', active);
                        circle.classList.toggle('text-white', active);
                        circle.classList.toggle('ring-4', active);
                        circle.classList.toggle('ring-primary/10', active);
                        circle.classList.toggle('bg-surface-container-highest', !active);
                        circle.classList.toggle('text-outline', !active);
                    }

                    if (text) {
                        text.classList.toggle('font-bold', active);
                        text.classList.toggle('text-primary', active);
                        text.classList.toggle('text-outline', !active);
                    }
                });

                stepPanels.forEach(function (panel) {
                    var panelStep = Number(panel.getAttribute('data-step-panel') || '1');
                    panel.classList.toggle('hidden', panelStep !== currentStep);
                });

                if (prevButton) {
                    prevButton.classList.toggle('hidden', currentStep === 1);
                }

                if (submitButton) {
                    var textSpan = submitButton.querySelector('span');
                    if (textSpan) {
                        textSpan.textContent = currentStep < 3 ? 'التالي' : 'إتمام الدفع الآمن';
                    }
                }
            }

            function fillReviewPanel() {
                if (reviewShippingName) {
                    reviewShippingName.textContent = (shippingNameInput && shippingNameInput.value.trim()) || '-';
                }

                if (reviewShippingPhone) {
                    reviewShippingPhone.textContent = (shippingPhoneInput && shippingPhoneInput.value.trim()) || '-';
                }

                if (reviewShippingAddress) {
                    var address = (shippingAddressInput && shippingAddressInput.value.trim()) || '';
                    var city = (shippingCityInput && shippingCityInput.value.trim()) || '';
                    var postal = (shippingPostalInput && shippingPostalInput.value.trim()) || '';
                    reviewShippingAddress.textContent = [address, city, postal].filter(Boolean).join(' - ') || '-';
                }

                if (reviewPaymentMethod) {
                    reviewPaymentMethod.textContent = getPaymentMethodLabel(selectedPaymentMethod);
                }
            }

            function validateShippingInput() {
                var name = shippingNameInput ? shippingNameInput.value.trim() : '';
                var phone = shippingPhoneInput ? shippingPhoneInput.value.trim() : '';
                var address = shippingAddressInput ? shippingAddressInput.value.trim() : '';
                var postal = shippingPostalInput ? shippingPostalInput.value.trim() : '';

                if (!name || !phone || !address || !postal) {
                    showStatus('يرجى تعبئة جميع معلومات الشحن قبل المتابعة.', 'error');
                    return false;
                }

                return true;
            }

            function canGoToStep(targetStep) {
                if (targetStep <= currentStep) {
                    return true;
                }

                if (targetStep >= 2 && !validateShippingInput()) {
                    return false;
                }

                if (targetStep >= 3 && !validatePaymentInput()) {
                    return false;
                }

                return true;
            }

            function goToStep(targetStep) {
                if (!canGoToStep(targetStep)) {
                    return;
                }

                currentStep = Math.min(3, Math.max(1, targetStep));
                fillReviewPanel();
                updateStepperUI();
            }

            function readCart() {
                try {
                    return JSON.parse(localStorage.getItem(cartStorageKey) || '[]');
                } catch (error) {
                    return [];
                }
            }

            function writeCart(items) {
                localStorage.setItem(cartStorageKey, JSON.stringify(items));
            }

            function readCheckoutItems() {
                try {
                    return JSON.parse(localStorage.getItem(checkoutItemsKey) || '[]');
                } catch (error) {
                    return [];
                }
            }

            function writeCheckoutItems(items) {
                localStorage.setItem(checkoutItemsKey, JSON.stringify(items));
            }

            function normalizeCheckoutItem(item) {
                if (!item || typeof item !== 'object') {
                    return {
                        description: ''
                    };
                }

                return Object.assign({}, item, {
                    description: String(item.description || '').trim()
                });
            }

            function mergeDescriptionsFromCart(checkoutItems, cartItems) {
                if (!Array.isArray(checkoutItems) || !checkoutItems.length) {
                    return [];
                }

                var cartByListingId = new Map();
                var cartByKey = new Map();

                (Array.isArray(cartItems) ? cartItems : []).forEach(function (item) {
                    var normalized = normalizeCheckoutItem(item);
                    var listingId = Number(normalized.listingId || 0);
                    var key = String(normalized.key || '');

                    if (listingId > 0) {
                        cartByListingId.set(listingId, normalized.description || '');
                    }

                    if (key) {
                        cartByKey.set(key, normalized.description || '');
                    }
                });

                return checkoutItems.map(function (item) {
                    var normalized = normalizeCheckoutItem(item);
                    if (normalized.description) {
                        return normalized;
                    }

                    var listingId = Number(normalized.listingId || 0);
                    var key = String(normalized.key || '');
                    var fallbackDescription = '';

                    if (listingId > 0 && cartByListingId.has(listingId)) {
                        fallbackDescription = String(cartByListingId.get(listingId) || '');
                    } else if (key && cartByKey.has(key)) {
                        fallbackDescription = String(cartByKey.get(key) || '');
                    }

                    normalized.description = fallbackDescription;

                    return normalized;
                });
            }

            function getCheckoutSourceItems() {
                var passedItems = readCheckoutItems();
                if (Array.isArray(passedItems) && passedItems.length) {
                    var mergedItems = mergeDescriptionsFromCart(passedItems, readCart());
                    writeCheckoutItems(mergedItems);
                    return mergedItems;
                }

                return readCart().map(normalizeCheckoutItem);
            }

            function hydrateMissingDescriptions(checkoutItems) {
                if (descriptionHydrationRequested || !Array.isArray(checkoutItems) || !checkoutItems.length) {
                    return;
                }

                var missingIds = checkoutItems
                    .filter(function (item) {
                        return !String(item.description || '').trim() && Number(item.listingId || 0) > 0;
                    })
                    .map(function (item) {
                        return Number(item.listingId || 0);
                    })
                    .filter(function (id, index, ids) {
                        return ids.indexOf(id) === index;
                    });

                if (!missingIds.length) {
                    return;
                }

                descriptionHydrationRequested = true;

                fetch(itemsMetaEndpoint + '?listing_ids=' + encodeURIComponent(missingIds.join(',')), {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                    .then(function (response) {
                        if (!response.ok) {
                            throw new Error('items meta request failed');
                        }

                        return response.json();
                    })
                    .then(function (payload) {
                        var itemsMeta = payload && payload.items ? payload.items : {};
                        var hasChanges = false;

                        var mergedCheckoutItems = checkoutItems.map(function (item) {
                            var normalized = normalizeCheckoutItem(item);
                            if (normalized.description) {
                                return normalized;
                            }

                            var listingId = Number(normalized.listingId || 0);
                            var meta = itemsMeta[String(listingId)] || null;
                            var serverDescription = meta && meta.description ? String(meta.description).trim() : '';

                            if (!serverDescription) {
                                return normalized;
                            }

                            hasChanges = true;

                            return Object.assign({}, normalized, {
                                description: serverDescription
                            });
                        });

                        if (!hasChanges) {
                            return;
                        }

                        writeCheckoutItems(mergedCheckoutItems);

                        var mergedCart = readCart().map(function (item) {
                            var normalized = normalizeCheckoutItem(item);
                            if (normalized.description) {
                                return normalized;
                            }

                            var listingId = Number(normalized.listingId || 0);
                            var meta = itemsMeta[String(listingId)] || null;
                            var serverDescription = meta && meta.description ? String(meta.description).trim() : '';

                            if (!serverDescription) {
                                return normalized;
                            }

                            return Object.assign({}, normalized, {
                                description: serverDescription
                            });
                        });

                        writeCart(mergedCart);
                        renderSummary();
                    })
                    .catch(function () {
                        descriptionHydrationRequested = false;
                    });
            }

            function resolveListingId(item) {
                if (Number(item.listingId) > 0) {
                    return Number(item.listingId);
                }

                var url = String(item.buyUrl || '');
                var match = url.match(/\/marketplace\/(\d+)\/buy$/);
                return match ? Number(match[1]) : 0;
            }

            function showStatus(message, type) {
                if (!statusBox) return;

                statusBox.textContent = message;
                statusBox.classList.remove('hidden', 'bg-error-container', 'text-error', 'bg-surface-container-high', 'text-primary');
                if (type === 'error') {
                    statusBox.classList.add('bg-error-container', 'text-error');
                } else {
                    statusBox.classList.add('bg-surface-container-high', 'text-primary');
                }
            }

            function updatePaymentMethodUI() {
                paymentMethodOptions.forEach(function (option) {
                    var isSelected = option.getAttribute('data-payment-option') === selectedPaymentMethod;

                    option.classList.toggle('border-primary', isSelected);
                    option.classList.toggle('bg-primary/5', isSelected);
                    option.classList.toggle('border-transparent', !isSelected);
                    option.classList.toggle('bg-surface-container-low', !isSelected);

                    var dot = option.querySelector('.w-3.h-3');
                    if (dot) {
                        dot.classList.toggle('hidden', !isSelected);
                    }

                    var borderRing = option.querySelector('.w-6.h-6.rounded-full');
                    if (borderRing) {
                        borderRing.classList.toggle('border-primary', isSelected);
                        borderRing.classList.toggle('border-outline', !isSelected);
                    }

                    var text = option.querySelector('span.font-bold');
                    if (text) {
                        text.classList.toggle('text-primary', isSelected);
                        text.classList.toggle('text-on-surface', !isSelected);
                    }
                });

                if (cardDetails) {
                    var showCardFields = selectedPaymentMethod === 'card';
                    cardDetails.classList.toggle('hidden', !showCardFields);
                }
            }

            function validatePaymentInput() {
                if (selectedPaymentMethod !== 'card') {
                    return true;
                }

                var cardNumber = (cardNumberInput ? cardNumberInput.value : '').replace(/\s+/g, '');
                var cardExpiry = cardExpiryInput ? cardExpiryInput.value.trim() : '';
                var cardCvv = cardCvvInput ? cardCvvInput.value.trim() : '';

                var numberValid = /^\d{16}$/.test(cardNumber);
                var expiryValid = /^(0[1-9]|1[0-2])\/\d{2}$/.test(cardExpiry);
                var cvvValid = /^\d{3,4}$/.test(cardCvv);

                if (!numberValid || !expiryValid || !cvvValid) {
                    showStatus('أدخل بيانات بطاقة صحيحة قبل إتمام الدفع.', 'error');
                    return false;
                }

                return true;
            }

            function readSelectedPaymentMethod() {
                var checkedInput = Array.prototype.find.call(paymentMethodInputs, function (input) {
                    return input.checked;
                });

                selectedPaymentMethod = checkedInput ? checkedInput.value : 'card';
                updatePaymentMethodUI();
                return selectedPaymentMethod;
            }

            function escapeHtml(value) {
                return String(value || '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/\"/g, '&quot;')
                    .replace(/'/g, '&#39;');
            }

            function formatDescription(value) {
                return escapeHtml(value).replace(/\r?\n/g, '<br>');
            }

            function renderSummary() {
                if (!itemsContainer) return;

                var checkoutItems = getCheckoutSourceItems();
                var total = checkoutItems.reduce(function (sum, item) {
                    return sum + (Number(item.price) || 0);
                }, 0);

                if (subtotalElement) {
                    subtotalElement.textContent = total.toLocaleString('ar-EG') + ' ر.س';
                }

                if (totalElement) {
                    totalElement.textContent = total.toLocaleString('ar-EG') + ' ر.س';
                }

                if (!checkoutItems.length) {
                    itemsContainer.innerHTML = '<div class="rounded-xl border border-outline-variant/20 bg-white p-4 text-sm text-on-surface-variant text-right">السلة فارغة حالياً.</div>';
                    if (submitButton) submitButton.disabled = true;
                    return;
                }

                hydrateMissingDescriptions(checkoutItems);

                itemsContainer.innerHTML = checkoutItems.map(function (item) {
                    var title = escapeHtml(item.title || 'منتج بدون اسم');
                    var description = formatDescription(item.description || '');
                    var category = escapeHtml(item.category || '');
                    var image = escapeHtml(item.image || '');
                    var priceLabel = escapeHtml(item.priceLabel || '0 ر.س');

                    return '<div class="flex gap-4 items-start">' +
                        '<div class="w-16 h-16 rounded-lg bg-white overflow-hidden flex-shrink-0"><img src="' + image + '" alt="' + title + '" class="w-full h-full object-cover" /></div>' +
                        '<div class="flex-grow min-w-0">' +
                            '<p class="text-sm font-bold text-on-surface leading-tight break-words">' + title + '</p>' +
                            '<p class="text-xs text-on-surface-variant mt-1 leading-6 whitespace-normal break-words">' + description + '</p>' +
                            '<p class="text-xs text-on-surface-variant/80 mt-1">' + category + '</p>' +
                        '</div>' +
                        '<p class="text-sm font-bold text-primary">' + priceLabel + '</p>' +
                    '</div>';
                }).join('');

                if (submitButton) submitButton.disabled = false;
            }

            function completeCheckout() {
                var checkoutItems = getCheckoutSourceItems();
                var paymentMethod = readSelectedPaymentMethod();

                if (!validatePaymentInput()) {
                    return;
                }

                var listingIds = checkoutItems
                    .map(function (item) {
                        return resolveListingId(item);
                    })
                    .filter(function (id) {
                        return id > 0;
                    });

                if (!listingIds.length) {
                    showStatus('لا توجد عناصر قابلة للشراء في السلة.', 'error');
                    return;
                }

                submitButton.disabled = true;

                fetch(completeEndpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ listing_ids: listingIds, payment_method: paymentMethod })
                })
                    .then(function (response) {
                        if (!response.ok) {
                            throw new Error('Checkout failed');
                        }
                        return response.json();
                    })
                    .then(function (payload) {
                        var purchasedIds = Array.isArray(payload.purchased_listing_ids) ? payload.purchased_listing_ids : [];

                        if (!purchasedIds.length) {
                            showStatus('لم يتم شراء أي منتج. تأكد من أن المنتجات متاحة وغير مملوكة لك.', 'error');
                            return;
                        }

                        var remaining = readCart().filter(function (item) {
                            var id = resolveListingId(item);
                            return purchasedIds.indexOf(id) === -1;
                        });

                        var remainingCheckoutItems = getCheckoutSourceItems().filter(function (item) {
                            var id = resolveListingId(item);
                            return purchasedIds.indexOf(id) === -1;
                        });

                        writeCart(remaining);
                        writeCheckoutItems(remainingCheckoutItems);
                        renderSummary();
                        showStatus('تم إكمال الطلب بنجاح لعدد ' + purchasedIds.length + ' منتج.', 'success');
                        window.location.href = successEndpoint;
                    })
                    .catch(function () {
                        showStatus('حدث خطأ أثناء تنفيذ الطلب. حاول مرة أخرى.', 'error');
                    })
                    .finally(function () {
                        submitButton.disabled = false;
                    });
            }

            if (submitButton) {
                submitButton.addEventListener('click', function () {
                    if (currentStep < 3) {
                        goToStep(currentStep + 1);
                        return;
                    }

                    completeCheckout();
                });
            }

            if (prevButton) {
                prevButton.addEventListener('click', function () {
                    goToStep(currentStep - 1);
                });
            }

            stepTriggers.forEach(function (trigger) {
                trigger.addEventListener('click', function () {
                    var target = Number(trigger.getAttribute('data-step-trigger') || '1');
                    goToStep(target);
                });
            });

            paymentMethodInputs.forEach(function (input) {
                input.addEventListener('change', function () {
                    readSelectedPaymentMethod();
                });
            });

            if (cardNumberInput) {
                cardNumberInput.addEventListener('input', function () {
                    var digits = this.value.replace(/\D/g, '').slice(0, 16);
                    this.value = digits.replace(/(\d{4})(?=\d)/g, '$1 ').trim();
                });
            }

            if (cardExpiryInput) {
                cardExpiryInput.addEventListener('input', function () {
                    var digits = this.value.replace(/\D/g, '').slice(0, 4);
                    if (digits.length >= 3) {
                        this.value = digits.slice(0, 2) + '/' + digits.slice(2);
                    } else {
                        this.value = digits;
                    }
                });
            }

            if (cardCvvInput) {
                cardCvvInput.addEventListener('input', function () {
                    this.value = this.value.replace(/\D/g, '').slice(0, 4);
                });
            }

            readSelectedPaymentMethod();
            renderSummary();
            goToStep(1);
        })();
    </script>
@endpush
