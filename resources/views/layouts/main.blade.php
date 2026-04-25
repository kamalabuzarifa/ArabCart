<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<title>@yield('title', config('app.name', 'ArabCart'))</title>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
	<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&amp;family=Manrope:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet" />
	<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
	<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
	<script>
		tailwind.config = {
			darkMode: 'class',
			theme: {
				extend: {
					colors: {
						primary: '#003345',
						'surface-tint': '#2a657e',
						'on-tertiary': '#ffffff',
						'on-surface': '#191c1e',
						'surface-container-high': '#e7e8ea',
						'on-tertiary-fixed': '#241a00',
						'error-container': '#ffdad6',
						'tertiary-fixed-dim': '#e9c349',
						'on-secondary-fixed': '#2e1500',
						error: '#ba1a1a',
						'inverse-primary': '#96ceeb',
						'on-error-container': '#93000a',
						'primary-container': '#004b63',
						'on-primary-fixed': '#001f2b',
						'primary-fixed': '#bfe8ff',
						'on-secondary-container': '#613400',
						'on-surface-variant': '#40484c',
						'surface-container-low': '#f2f4f6',
						outline: '#71787d',
						'on-primary-container': '#83bad6',
						'on-primary': '#ffffff',
						'primary-fixed-dim': '#96ceeb',
						'on-tertiary-fixed-variant': '#574500',
						'secondary-fixed-dim': '#ffb778',
						'on-tertiary-container': '#4e3d00',
						'on-error': '#ffffff',
						'inverse-on-surface': '#f0f1f3',
						'secondary-container': '#fd9000',
						'outline-variant': '#c0c7cd',
						'surface-variant': '#e1e2e5',
						secondary: '#8e4e00',
						'on-secondary': '#ffffff',
						surface: '#f8f9fb',
						'surface-container-highest': '#e1e2e5',
						'tertiary-fixed': '#ffe088',
						'tertiary-container': '#cba72f',
						'surface-container-lowest': '#ffffff',
						'on-background': '#191c1e',
						background: '#f8f9fb',
						'surface-bright': '#f8f9fb',
						tertiary: '#735c00',
						'surface-container': '#edeef0',
						'secondary-fixed': '#ffdcc1',
						'inverse-surface': '#2e3133',
						'on-secondary-fixed-variant': '#6c3a00',
						'surface-dim': '#d9dadc'
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
		.material-symbols-outlined {
			font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
		}

		body {
			font-family: 'Manrope', 'IBM Plex Sans Arabic', sans-serif;
		}

		.headline-font {
			font-family: 'Plus Jakarta Sans', 'IBM Plex Sans Arabic', sans-serif;
		}

		.rtl-product-grid {
			direction: rtl;
		}
	</style>
	@stack('page-styles')
</head>
<body class="@yield('body-class', 'bg-surface text-on-surface antialiased')">
	@php
		$activeNav = trim($__env->yieldContent('nav-active')) ?: 'home';
		$pageSignature = trim($__env->yieldContent('title')) ?: $activeNav;
		$footerMessages = [
			'وجهتك الأولى للتسوق الفاخر. اكتشف منتجات مختارة بعناية تمنحك جودة عالية وتجربة شراء مريحة.',
			'هنا تجد تنوعًا أنيقًا من المنتجات العالمية مع خدمة موثوقة وتجربة تسوق بسيطة وسريعة.',
			'نحن نختار لك منتجات تمنحك قيمة حقيقية، وتصميمًا راقيًا، وخدمة تهتم بتفاصيل طلبك.',
			'تسوّق بثقة من مجموعة منتقاة بعناية لتناسب ذوقك وتلبي احتياجاتك اليومية بسهولة.',
			'كل صفحة تقودك إلى تجربة مختلفة، لكن الهدف واحد: منتجات مميزة وخدمة ترضي توقعاتك.',
		];
		$footerMessage = $footerMessages[abs(crc32($pageSignature)) % count($footerMessages)];
		$currentUser = auth()->user();
	@endphp

	<header class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-md shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
		<nav class="flex flex-row justify-between items-center px-6 py-4 w-full">
			<div class="flex items-center gap-3 text-2xl font-bold text-primary tracking-tight headline-font">
				<img src="{{ asset('images/slogan.png') }}" alt="شعار المتجر الرفيع" class="w-16 h-16 md:w-20 md:h-20 object-contain rounded-md" loading="eager" />
				<span>المتجر الرفيع</span>
			</div>

			<div class="hidden md:flex flex-row gap-8 items-center">
				<a class="{{ $activeNav === 'home' ? 'text-primary font-bold border-b-2 border-primary headline-font' : 'text-slate-500 hover:text-primary transition-opacity duration-300 headline-font' }}" href="{{ url('/') }}">الرئيسية</a>
				<a class="{{ $activeNav === 'new-arrivals' ? 'text-primary font-bold border-b-2 border-primary headline-font' : 'text-slate-500 hover:text-primary transition-opacity duration-300 headline-font' }}" href="{{ route('new-arrivals.index') }}">وصلنا حديثاً</a>
				<a class="{{ $activeNav === 'offers' ? 'text-primary font-bold border-b-2 border-primary headline-font' : 'text-slate-500 hover:text-primary transition-opacity duration-300 headline-font' }}" href="{{ route('offers.index') }}">عروض</a>
				<a class="{{ $activeNav === 'groups' ? 'text-primary font-bold border-b-2 border-primary headline-font' : 'text-slate-500 hover:text-primary transition-opacity duration-300 headline-font' }}" href="{{ route('groups.index') }}">مجموعات</a>
				<a class="{{ $activeNav === 'marketplace' ? 'text-primary font-bold border-b-2 border-primary headline-font' : 'text-slate-500 hover:text-primary transition-opacity duration-300 headline-font' }}" href="{{ route('marketplace.index') }}">منتجاتي</a>
			</div>

			<div class="flex items-center gap-6">
                <div class="flex gap-4 items-center">
					@auth
						<button id="favorites-panel-trigger" data-favorites-open class="hidden md:inline-flex relative hover:opacity-80 transition-opacity duration-300 scale-95 active:scale-100" type="button" aria-label="فتح المفضلة">
							<span class="material-symbols-outlined">favorite</span>
							<span id="favorites-count-badge" class="hidden absolute -top-2 -left-2 min-w-5 h-5 px-1 rounded-full bg-secondary text-white text-[10px] font-bold items-center justify-center">0</span>
						</button>
					@endauth

					@auth
						<button id="shopping-cart-trigger" data-cart-open class="relative hover:opacity-80 transition-opacity duration-300 scale-95 active:scale-100" type="button" aria-label="سلة التسوق">
							<span class="material-symbols-outlined">shopping_cart</span>
							<span id="cart-count-badge" class="hidden absolute -top-2 -left-2 min-w-5 h-5 px-1 rounded-full bg-secondary text-white text-[10px] font-bold items-center justify-center">0</span>
						</button>

						<div class="relative hidden md:block">
							<button id="account-menu-trigger" class="hover:opacity-80 transition-opacity duration-300 scale-95 active:scale-100" type="button" aria-label="الحساب" aria-expanded="false">
								@if ($currentUser && $currentUser->avatar_url)
									<img src="{{ $currentUser->avatar_url }}" alt="صورة الحساب" class="w-10 h-10 rounded-full object-cover ring-2 ring-white shadow-sm" />
								@else
									<span class="material-symbols-outlined">person</span>
								@endif
							</button>
							<div id="account-menu" class="hidden absolute top-12 left-0 w-44 rounded-xl border border-outline-variant/20 bg-white shadow-lg p-2 z-50">
								<a href="{{ route('dashboard') }}" class="block text-sm text-right px-3 py-2 rounded-lg text-on-surface hover:bg-surface-container-low">حسابي</a>
								<form method="POST" action="{{ route('logout') }}" class="mt-1">
									@csrf
									<button type="submit" class="w-full text-sm text-right px-3 py-2 rounded-lg text-error hover:bg-error-container">تسجيل الخروج</button>
								</form>
							</div>
						</div>
					@else
						<a href="{{ route('login') }}" class="hidden md:block text-xs px-3 py-1.5 rounded-full bg-primary text-white font-bold hover:bg-primary-container transition-colors">تسجيل الدخول</a>
					@endauth
				</div>
				<div id="global-search" class="hidden lg:flex relative items-center bg-surface-container-highest px-4 py-2 rounded-full">
					<span class="material-symbols-outlined text-outline ml-2">search</span>
					<input id="global-search-input" class="bg-transparent border-none focus:ring-0 text-sm w-48 text-right" placeholder="ابحث عن منتج..." type="text" autocomplete="off" />
					<div id="global-search-results" class="hidden absolute top-[calc(100%+0.5rem)] right-0 w-96 max-h-96 overflow-y-auto rounded-2xl border border-outline-variant/20 bg-white shadow-xl p-2"></div>
				</div>
			</div>
		</nav>
		<div id="global-search-mobile" class="md:hidden px-4 pb-3">
			<div class="relative flex items-center bg-surface-container-highest px-4 py-2 rounded-full">
				<span class="material-symbols-outlined text-outline ml-2">search</span>
				<input id="global-search-mobile-input" class="bg-transparent border-none focus:ring-0 text-sm w-full text-right" placeholder="ابحث عن منتج..." type="text" autocomplete="off" />
				<div id="global-search-mobile-results" class="hidden absolute top-[calc(100%+0.5rem)] right-0 left-0 max-h-80 overflow-y-auto rounded-2xl border border-outline-variant/20 bg-white shadow-xl p-2"></div>
			</div>
		</div>
	</header>

	<div class="mt-10 pt-32 lg:pt-24 pb-12 lg:flex lg:flex-row lg:items-start lg:gap-8 lg:px-6 lg:max-w-7xl lg:mx-auto">
		@hasSection('sidebar')
			<aside id="page-sidebar" class="hidden lg:flex w-72 shrink-0 rounded-2xl border border-outline-variant/20 bg-surface-container-low shadow-sm flex-col lg:sticky lg:top-32 lg:self-start">
				@yield('sidebar')
			</aside>
		@endif

		<main class="lg:flex-1 lg:min-w-0">
			@yield('content')
		</main>
	</div>

	<footer class="w-full mt-auto bg-surface-container-low">
		<div class="w-full py-12 px-8 grid grid-cols-1 md:grid-cols-4 gap-8 text-right">
			<div>
				<div class="text-xl font-black text-primary mb-6">المتجر الرفيع</div>
				<p class="text-slate-500 text-sm leading-relaxed">{{ $footerMessage }}</p>
			</div>
			<div>
				<h4 class="font-bold text-primary mb-6">روابط سريعة</h4>
				<ul class="space-y-3">
					<li><a class="text-sm text-slate-500 hover:text-primary hover:underline decoration-secondary transition-colors duration-200" href="{{ url('/') }}">الرئيسية</a></li>
					<li><a class="text-sm text-slate-500 hover:text-primary hover:underline decoration-secondary transition-colors duration-200" href="{{ route('new-arrivals.index') }}">وصلنا حديثاً</a></li>
					<li><a class="text-sm text-slate-500 hover:text-primary hover:underline decoration-secondary transition-colors duration-200" href="{{ route('offers.index') }}">عروض</a></li>
					<li><a class="text-sm text-slate-500 hover:text-primary hover:underline decoration-secondary transition-colors duration-200" href="{{ route('groups.index') }}">مجموعات</a></li>
					<li><a class="text-sm text-slate-500 hover:text-primary hover:underline decoration-secondary transition-colors duration-200" href="{{ route('marketplace.index') }}">منتجاتي</a></li>
				</ul>
			</div>
			<div>
				<h4 class="font-bold text-primary mb-6">المساعدة</h4>
				<ul class="space-y-3">
					<li><a class="text-sm text-slate-500 hover:text-primary hover:underline decoration-secondary transition-colors duration-200" href="{{ route('privacy.index') }}">سياسة الخصوصية</a></li>
					<li><a class="text-sm text-slate-500 hover:text-primary hover:underline decoration-secondary transition-colors duration-200" href="{{ route('terms.index') }}">الشروط والأحكام</a></li>
					<li><a class="text-sm text-slate-500 hover:text-primary hover:underline decoration-secondary transition-colors duration-200" href="{{ route('contact.index') }}">اتصل بنا</a></li>
					<li><a class="text-sm text-slate-500 hover:text-primary hover:underline decoration-secondary transition-colors duration-200" href="{{ route('faq.index') }}">الأسئلة الشائعة</a></li>
				</ul>
			</div>
			<div>
				<h4 class="font-bold text-primary mb-6">تواصل معنا</h4>
				<div class="flex flex-row gap-4 mb-6">
					<a class="w-10 h-10 rounded-full bg-white flex items-center justify-center hover:bg-secondary transition-all shadow-sm" href="https://www.facebook.com/kamalAbuZarifa2003" aria-label="Facebook">
						<img class="w-5 h-5 object-contain" src="{{ asset('images/facebook.png') }}" alt="Facebook" />
					</a>
					<a class="w-10 h-10 rounded-full bg-white flex items-center justify-center hover:bg-secondary transition-all shadow-sm" href="https://www.linkedin.com/in/kamal-abuzarifa-777887330/" aria-label="Twitter">
						<img class="w-5 h-5 object-contain" src="{{ asset('images/tiwtter.png') }}" alt="Twitter" />
					</a>
					<a class="w-10 h-10 rounded-full bg-white flex items-center justify-center hover:bg-secondary transition-all shadow-sm" href="https://www.instagram.com/eng_kamal_2003/?__pwa=1" aria-label="Instagram">
						<img class="w-5 h-5 object-contain" src="{{ asset('images/instegram.png') }}" alt="Instagram" />
					</a>
				</div>
				<p class="text-xs text-slate-400">© 2026 المتجر الرفيع. جميع الحقوق محفوظة</p>
			</div>
		</div>
	</footer>

	<div id="favorites-panel" class="fixed inset-0 z-[60] hidden">
		<div id="favorites-panel-backdrop" class="absolute inset-0 bg-black/40"></div>
		<div class="absolute top-0 left-0 h-full w-full max-w-md bg-white shadow-2xl flex flex-col">
			<div class="p-5 border-b border-outline-variant/20 flex items-center justify-between">
				<div>
					<h3 class="text-lg font-bold text-primary">المفضلة</h3>
					<p class="text-xs text-on-surface-variant mt-1">العناصر المحفوظة على هذا الجهاز</p>
				</div>
				<button id="favorites-panel-close" class="w-10 h-10 rounded-full bg-surface-container-low flex items-center justify-center text-primary" type="button" aria-label="إغلاق المفضلة">
					<span class="material-symbols-outlined">close</span>
				</button>
			</div>
			<div id="favorites-panel-list" class="flex-1 overflow-y-auto p-5 space-y-4"></div>
		</div>
	</div>

	<div id="cart-panel" class="fixed inset-0 z-[60] hidden">
		<div id="cart-panel-backdrop" class="absolute inset-0 bg-black/40"></div>
		<div class="absolute top-0 left-0 h-full w-full max-w-md bg-white shadow-2xl flex flex-col">
			<div class="p-5 border-b border-outline-variant/20 flex items-center justify-between">
				<div>
					<h3 class="text-lg font-bold text-primary">سلة التسوق</h3>
					<p class="text-xs text-on-surface-variant mt-1">العناصر المحفوظة على هذا الجهاز</p>
				</div>
				<button id="cart-panel-close" class="w-10 h-10 rounded-full bg-surface-container-low flex items-center justify-center text-primary" type="button" aria-label="إغلاق السلة">
					<span class="material-symbols-outlined">close</span>
				</button>
			</div>
			<div id="cart-panel-list" class="flex-1 overflow-y-auto p-5 space-y-4"></div>
			<div class="border-t border-outline-variant/20 p-5 space-y-3">
				<div class="flex items-center justify-between text-sm font-bold text-on-surface-variant">
					<span>الإجمالي</span>
					<span id="cart-panel-total">0 ر.س</span>
				</div>
				<button id="cart-panel-checkout" class="w-full rounded-full bg-primary text-white font-bold py-3 hover:bg-primary-container transition-colors" type="button">إتمام الطلب</button>
			</div>
		</div>
	</div>

	<div class="md:hidden fixed bottom-0 left-0 w-full bg-white/90 backdrop-blur-md px-6 py-3 flex justify-between items-center z-50 border-t border-slate-100">
		<a class="flex flex-col items-center {{ $activeNav === 'home' ? 'text-primary' : 'text-slate-400' }}" href="{{ url('/') }}">
			<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">home</span>
			<span class="text-[10px] mt-1 font-bold">الرئيسية</span>
		</a>
		<a class="flex flex-col items-center {{ $activeNav === 'marketplace' ? 'text-primary' : 'text-slate-400' }}" href="{{ route('marketplace.index') }}">
			<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">storefront</span>
			<span class="text-[10px] mt-1 font-bold">منتجاتك</span>
		</a>
		<a class="flex flex-col items-center {{ $activeNav === 'groups' ? 'text-primary' : 'text-slate-400' }}" href="{{ route('groups.index') }}">
			<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">grid_view</span>
			<span class="text-[10px] mt-1 font-bold">الفئات</span>
		</a>
		@auth
			<button class="flex flex-col items-center text-slate-400" type="button" data-favorites-open aria-label="فتح المفضلة">
				<span class="material-symbols-outlined">favorite</span>
				<span class="text-[10px] mt-1">المفضلة</span>
			</button>
		@endauth
		<button class="flex flex-col items-center text-slate-400" type="button" data-cart-open>
			<span class="material-symbols-outlined">shopping_bag</span>
			<span class="text-[10px] mt-1">حقيبتي</span>
		</button>
		@auth
			<a class="flex flex-col items-center {{ $activeNav === 'dashboard' ? 'text-primary' : 'text-slate-400' }}" href="{{ route('dashboard') }}">
				@if ($currentUser && $currentUser->avatar_url)
					<img src="{{ $currentUser->avatar_url }}" alt="صورة الحساب" class="w-7 h-7 rounded-full object-cover border border-slate-200" />
				@else
					<span class="material-symbols-outlined">person</span>
				@endif
				<span class="text-[10px] mt-1">حسابي</span>
			</a>
		@else
			<a class="flex flex-col items-center text-slate-400" href="{{ route('login') }}">
				<span class="material-symbols-outlined">person</span>
				<span class="text-[10px] mt-1">تسجيل الدخول</span>
			</a>
		@endauth
	</div>

	<script>
		(function () {
			var storageKey = 'arabcart:favorites';
			var cartStorageKey = 'arabcart:cart';
			var checkoutItemsKey = 'arabcart:checkout-items';
			var panel = document.getElementById('favorites-panel');
			var panelList = document.getElementById('favorites-panel-list');
			var panelClose = document.getElementById('favorites-panel-close');
			var panelBackdrop = document.getElementById('favorites-panel-backdrop');
			var countBadge = document.getElementById('favorites-count-badge');
			var favoriteButtons = document.querySelectorAll('[data-favorite-toggle]');
			var cartPanel = document.getElementById('cart-panel');
			var cartPanelList = document.getElementById('cart-panel-list');
			var cartPanelClose = document.getElementById('cart-panel-close');
			var cartPanelBackdrop = document.getElementById('cart-panel-backdrop');
			var cartCountBadge = document.getElementById('cart-count-badge');
			var cartPanelTotal = document.getElementById('cart-panel-total');
			var accountMenuTrigger = document.getElementById('account-menu-trigger');
			var accountMenu = document.getElementById('account-menu');
			var searchContainer = document.getElementById('global-search');
			var searchInput = document.getElementById('global-search-input');
			var searchResults = document.getElementById('global-search-results');
			var mobileSearchContainer = document.getElementById('global-search-mobile');
			var mobileSearchInput = document.getElementById('global-search-mobile-input');
			var mobileSearchResults = document.getElementById('global-search-mobile-results');
			var searchEndpoint = "{{ route('marketplace.search') }}";
			var marketplaceEndpoint = "{{ route('marketplace.index') }}";
			var checkoutEndpoint = "{{ route('checkout.index') }}";

			function readFavorites() {
				try {
					return JSON.parse(localStorage.getItem(storageKey) || '[]');
				} catch (error) {
					return [];
				}
			}

			function writeFavorites(items) {
				localStorage.setItem(storageKey, JSON.stringify(items));
			}

			function readCart() {
				try {
					return normalizeCart(JSON.parse(localStorage.getItem(cartStorageKey) || '[]'));
				} catch (error) {
					return [];
				}
			}

			function writeCart(items) {
				localStorage.setItem(cartStorageKey, JSON.stringify(normalizeCart(items)));
			}

			function canonicalCartKey(item) {
				var listingId = Number(item && item.listingId ? item.listingId : 0);
				if (listingId > 0) {
					return 'listing-' + String(listingId);
				}

				return String((item && item.key) || '');
			}

			function normalizeCart(items) {
				if (!Array.isArray(items)) {
					return [];
				}

				var normalizedByKey = {};
				items.forEach(function (item) {
					var key = canonicalCartKey(item);
					if (!key) {
						return;
					}

					if (!normalizedByKey[key]) {
						normalizedByKey[key] = {
							key: key,
							title: item.title || '',
							image: item.image || '',
							price: Number(item.price) || 0,
							priceLabel: item.priceLabel || '',
							category: item.category || '',
							buyUrl: item.buyUrl || '',
							listingId: Number(item.listingId || 0),
							quantity: 1
						};
					}
				});

				return Object.keys(normalizedByKey).map(function (key) {
					return normalizedByKey[key];
				});
			}

			function writeCheckoutItems(items) {
				localStorage.setItem(checkoutItemsKey, JSON.stringify(items));
			}

			function getFavoriteKey(button) {
				return button.getAttribute('data-favorite-key') || '';
			}

			function isFavorited(key) {
				return readFavorites().some(function (item) {
					return item.key === key;
				});
			}

			function setButtonState(button, active) {
				var icon = button.querySelector('.material-symbols-outlined');
				if (!icon) return;

				button.classList.toggle('bg-red-50', active);
				button.classList.toggle('text-red-600', active);
				button.classList.toggle('bg-white/90', !active);
				button.classList.toggle('text-primary', !active);
				icon.classList.toggle('text-red-600', active);
				icon.classList.toggle('text-primary', !active);
				icon.textContent = active ? 'favorite' : 'favorite_border';
				button.setAttribute('aria-pressed', active ? 'true' : 'false');
			}

			function syncButtons() {
				favoriteButtons = document.querySelectorAll('[data-favorite-toggle]');
				favoriteButtons.forEach(function (button) {
					setButtonState(button, isFavorited(getFavoriteKey(button)));
				});
			}

			function syncCartButtons() {
				var cartButtons = document.querySelectorAll('[data-cart-add]');
				cartButtons.forEach(function (button) {
					var active = readCart().some(function (item) {
						return item.key === getCartKey(button);
					});
					button.classList.toggle('bg-green-50', active);
					button.classList.toggle('text-green-700', active);
				});
			}

			function getCartKey(button) {
				var listingId = Number(button.getAttribute('data-cart-listing-id') || '0');
				if (listingId > 0) {
					return 'listing-' + String(listingId);
				}

				return button.getAttribute('data-cart-item-id') || '';
			}

			function renderCartPanel() {
				if (!cartPanelList) return;

				var cart = readCart();
				var cartCount = cart.reduce(function (sum, item) {
					return sum + (item.quantity || 1);
				}, 0);
				var total = cart.reduce(function (sum, item) {
					return sum + (Number(item.price) || 0) * (item.quantity || 1);
				}, 0);

				if (cartCountBadge) {
					cartCountBadge.textContent = String(cartCount);
					cartCountBadge.classList.toggle('hidden', cartCount === 0);
					cartCountBadge.classList.toggle('inline-flex', cartCount > 0);
				}

				if (cartPanelTotal) {
					cartPanelTotal.textContent = total.toLocaleString('ar-EG') + ' ر.س';
				}

				if (!cart.length) {
					cartPanelList.innerHTML = '<div class="rounded-2xl border border-outline-variant/20 bg-surface-container-low p-6 text-right text-sm text-on-surface-variant">سلتك فارغة حالياً.</div>';
					return;
				}

				cartPanelList.innerHTML = cart.map(function (item) {
					var buyButton = item.buyUrl
						? '<button class="rounded-full bg-primary text-white text-xs font-bold px-4 py-2 hover:bg-primary-container transition-colors" type="button" data-cart-buy="' + item.key + '">إكمال الطلب</button>'
						: '<button class="rounded-full bg-surface-container-high text-on-surface-variant text-xs font-bold px-4 py-2 cursor-not-allowed" type="button" disabled aria-disabled="true">غير متاح</button>';

					return '<div class="rounded-2xl border border-outline-variant/20 bg-surface-container-low p-3 text-right space-y-3">' +
						'<button class="flex items-center gap-4 w-full text-right" type="button" data-cart-buy="' + item.key + '"' + (item.buyUrl ? '' : ' disabled aria-disabled="true"') + '>' +
						'<img src="' + item.image + '" alt="' + item.title + '" class="w-16 h-16 rounded-xl object-cover flex-shrink-0" />' +
						'<div class="flex-1 min-w-0">' +
							'<p class="text-xs text-secondary font-bold mb-1">' + item.category + '</p>' +
							'<h4 class="text-sm font-bold text-primary truncate">' + item.title + '</h4>' +
							'<p class="text-sm text-on-surface-variant mt-1">' + item.priceLabel + '</p>' +
						'</div>' +
						'</button>' +
						'<div class="flex items-center justify-between gap-3">' +
							buyButton +
							'<button class="w-9 h-9 rounded-full bg-white border border-outline-variant/20 text-primary flex items-center justify-center" type="button" data-cart-remove="' + item.key + '" aria-label="إزالة من السلة">' +
							'<span class="material-symbols-outlined text-base">close</span>' +
						'</button>' +
						'</div>' +
					'</div>';
				}).join('');
			}

			function openCartPanel() {
				if (!cartPanel) return;
				cartPanel.classList.remove('hidden');
				document.body.classList.add('overflow-hidden');
				renderCartPanel();
			}

			function closeCartPanel() {
				if (!cartPanel) return;
				cartPanel.classList.add('hidden');
				document.body.classList.remove('overflow-hidden');
			}

			function addToCart(payload) {
				var cart = readCart();
				var existingIndex = cart.findIndex(function (item) {
					return item.key === payload.key;
				});

				if (existingIndex >= 0) {
					renderCartPanel();
					syncCartButtons();
					return;
				}

				cart.push({
					key: payload.key,
					title: payload.title,
					description: payload.description || '',
					image: payload.image,
					price: payload.price,
					priceLabel: payload.priceLabel,
					category: payload.category,
					buyUrl: payload.buyUrl || '',
					listingId: Number(payload.listingId || 0),
					quantity: 1
				});

				writeCart(cart);
				renderCartPanel();
				syncCartButtons();
			}

			function renderFavoritesPanel() {
				if (!panelList) return;

				var favorites = readFavorites();
				if (countBadge) {
					countBadge.textContent = String(favorites.length);
					countBadge.classList.toggle('hidden', favorites.length === 0);
					countBadge.classList.toggle('inline-flex', favorites.length > 0);
				}

				if (!favorites.length) {
					panelList.innerHTML = '<div class="rounded-2xl border border-outline-variant/20 bg-surface-container-low p-6 text-right text-sm text-on-surface-variant">لا توجد عناصر في المفضلة بعد.</div>';
					return;
				}

				panelList.innerHTML = favorites.map(function (item) {
					return '<div class="flex items-center gap-4 rounded-2xl border border-outline-variant/20 bg-surface-container-low p-3 text-right">' +
						'<img src="' + item.image + '" alt="' + item.title + '" class="w-16 h-16 rounded-xl object-cover flex-shrink-0" />' +
						'<div class="flex-1 min-w-0">' +
							'<p class="text-xs text-secondary font-bold mb-1">' + item.category + '</p>' +
							'<h4 class="text-sm font-bold text-primary truncate">' + item.title + '</h4>' +
							'<p class="text-sm text-on-surface-variant mt-1">' + item.price + '</p>' +
						'</div>' +
						'<button class="w-9 h-9 rounded-full bg-white border border-outline-variant/20 text-primary flex items-center justify-center" type="button" data-favorite-remove="' + item.key + '" aria-label="إزالة من المفضلة">' +
							'<span class="material-symbols-outlined text-base">close</span>' +
						'</button>' +
					'</div>';
				}).join('');
			}

			function openPanel() {
				if (!panel) return;
				panel.classList.remove('hidden');
				document.body.classList.add('overflow-hidden');
				renderFavoritesPanel();
			}

			function closePanel() {
				if (!panel) return;
				panel.classList.add('hidden');
				document.body.classList.remove('overflow-hidden');
			}

			function hideSearchResults(resultsElement) {
				if (!resultsElement) return;
				resultsElement.classList.add('hidden');
				resultsElement.innerHTML = '';
			}

			function renderSearchResults(items, resultsElement) {
				if (!resultsElement) return;

				if (!items.length) {
					resultsElement.innerHTML = '<div class="px-3 py-2 text-sm text-on-surface-variant text-right">لا توجد نتائج مطابقة.</div>';
					resultsElement.classList.remove('hidden');
					return;
				}

				resultsElement.innerHTML = items.map(function (item) {
					var imageHtml = item.image
						? '<img src="' + item.image + '" alt="' + item.title + '" class="w-12 h-12 rounded-lg object-cover flex-shrink-0" />'
						: '<div class="w-12 h-12 rounded-lg bg-surface-container-low flex items-center justify-center text-outline flex-shrink-0"><span class="material-symbols-outlined text-base">inventory_2</span></div>';

					return '<a href="' + item.url + '" class="flex items-center gap-3 p-2 rounded-xl hover:bg-surface-container-low transition-colors">' +
						imageHtml +
						'<div class="min-w-0 flex-1 text-right">' +
							'<p class="text-xs text-secondary font-semibold truncate">' + item.category + '</p>' +
							'<p class="text-sm text-primary font-bold truncate mt-0.5">' + item.title + '</p>' +
							'<p class="text-xs text-on-surface-variant mt-0.5">' + item.price + '</p>' +
						'</div>' +
					'</a>';
				}).join('');

				resultsElement.classList.remove('hidden');
			}

			function fetchSearchSuggestions(query, resultsElement) {
				if (!resultsElement) return;

				if (resultsElement._abortController) {
					resultsElement._abortController.abort();
				}

				resultsElement._abortController = new AbortController();
				fetch(searchEndpoint + '?q=' + encodeURIComponent(query), {
					headers: {
						'Accept': 'application/json'
					},
					signal: resultsElement._abortController.signal
				})
					.then(function (response) {
						if (!response.ok) {
							throw new Error('Search request failed');
						}
						return response.json();
					})
					.then(function (payload) {
						renderSearchResults(Array.isArray(payload.items) ? payload.items : [], resultsElement);
					})
					.catch(function (error) {
						if (error && error.name === 'AbortError') {
							return;
						}
						hideSearchResults(resultsElement);
					});
			}

			function bindSearchInput(inputElement, resultsElement) {
				if (!inputElement || !resultsElement) {
					return;
				}

				inputElement.addEventListener('input', function (event) {
					var query = event.target.value.trim();

					if (inputElement._debounceTimer) {
						clearTimeout(inputElement._debounceTimer);
					}

					if (query.length < 2) {
						hideSearchResults(resultsElement);
						return;
					}

					inputElement._debounceTimer = setTimeout(function () {
						fetchSearchSuggestions(query, resultsElement);
					}, 250);
				});

				inputElement.addEventListener('keydown', function (event) {
					if (event.key === 'Enter') {
						event.preventDefault();
						var query = inputElement.value.trim();
						window.location.href = query ? marketplaceEndpoint + '?q=' + encodeURIComponent(query) : marketplaceEndpoint;
					}
				});

				inputElement.addEventListener('focus', function () {
					var query = inputElement.value.trim();
					if (query.length >= 2) {
						fetchSearchSuggestions(query, resultsElement);
					}
				});
			}

			document.addEventListener('click', function (event) {
				if (accountMenuTrigger && accountMenu) {
					var clickedTrigger = event.target.closest('#account-menu-trigger');
					if (clickedTrigger) {
						event.preventDefault();
						var willOpen = accountMenu.classList.contains('hidden');
						accountMenu.classList.toggle('hidden', !willOpen);
						accountMenuTrigger.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
						return;
					}

					if (!event.target.closest('#account-menu')) {
						accountMenu.classList.add('hidden');
						accountMenuTrigger.setAttribute('aria-expanded', 'false');
					}
				}

				var toggleButton = event.target.closest('[data-favorite-toggle]');
				if (toggleButton) {
					event.preventDefault();
					var key = getFavoriteKey(toggleButton);
					if (!key) return;

					var favorites = readFavorites();
					var index = favorites.findIndex(function (item) {
						return item.key === key;
					});

					if (index >= 0) {
						favorites.splice(index, 1);
					} else {
						favorites.push({
							key: key,
							title: toggleButton.getAttribute('data-favorite-title') || '',
							image: toggleButton.getAttribute('data-favorite-image') || '',
							price: toggleButton.getAttribute('data-favorite-price') || '',
							category: toggleButton.getAttribute('data-favorite-category') || ''
						});
					}

					writeFavorites(favorites);
					syncButtons();
					renderFavoritesPanel();
					return;
				}

				var cartAddButton = event.target.closest('[data-cart-add]');
				if (cartAddButton) {
					event.preventDefault();
					addToCart({
						key: getCartKey(cartAddButton),
						title: cartAddButton.getAttribute('data-cart-title') || '',
						description: cartAddButton.getAttribute('data-cart-description') || '',
						image: cartAddButton.getAttribute('data-cart-image') || '',
						price: Number(cartAddButton.getAttribute('data-cart-price') || '0'),
						priceLabel: cartAddButton.getAttribute('data-cart-price-label') || '',
						category: cartAddButton.getAttribute('data-cart-category') || '',
						buyUrl: cartAddButton.getAttribute('data-cart-buy-url') || '',
						listingId: cartAddButton.getAttribute('data-cart-listing-id') || '0'
					});
					return;
				}

				var cartBuyButton = event.target.closest('[data-cart-buy]');
				if (cartBuyButton) {
					event.preventDefault();
					var cartItemKey = cartBuyButton.getAttribute('data-cart-buy');
					var selectedItem = readCart().find(function (item) {
						return item.key === cartItemKey;
					});

					if (!selectedItem) {
						return;
					}

					writeCheckoutItems([selectedItem]);
					window.location.href = checkoutEndpoint;
					return;
				}

				var cartOpenTrigger = event.target.closest('[data-cart-open]');
				if (cartOpenTrigger) {
					event.preventDefault();
					openCartPanel();
					return;
				}

				var cartRemoveButton = event.target.closest('[data-cart-remove]');
				if (cartRemoveButton) {
					var removeKey = cartRemoveButton.getAttribute('data-cart-remove');
					var updatedCart = readCart().filter(function (item) {
						return item.key !== removeKey;
					});
					writeCart(updatedCart);
					syncCartButtons();
					renderCartPanel();
					return;
				}

				var removeButton = event.target.closest('[data-favorite-remove]');
				if (removeButton) {
					var removeKey = removeButton.getAttribute('data-favorite-remove');
					var updatedFavorites = readFavorites().filter(function (item) {
						return item.key !== removeKey;
					});
					writeFavorites(updatedFavorites);
					syncButtons();
					renderFavoritesPanel();
					return;
				}

				var openTrigger = event.target.closest('[data-favorites-open]');
				if (openTrigger) {
					event.preventDefault();
					openPanel();
				}
			});

			if (panelClose) {
				panelClose.addEventListener('click', closePanel);
			}

			if (cartPanelClose) {
				cartPanelClose.addEventListener('click', closeCartPanel);
			}

			if (panelBackdrop) {
				panelBackdrop.addEventListener('click', closePanel);
			}

			if (cartPanelBackdrop) {
				cartPanelBackdrop.addEventListener('click', closeCartPanel);
			}

			var cartCheckoutButton = document.getElementById('cart-panel-checkout');
			if (cartCheckoutButton) {
				cartCheckoutButton.addEventListener('click', function () {
					writeCheckoutItems(readCart());
					window.location.href = checkoutEndpoint;
				});
			}

			bindSearchInput(searchInput, searchResults);
			bindSearchInput(mobileSearchInput, mobileSearchResults);

			window.addEventListener('keydown', function (event) {
				if (event.key === 'Escape') {
					closePanel();
					hideSearchResults(searchResults);
					hideSearchResults(mobileSearchResults);
					if (accountMenuTrigger && accountMenu) {
						accountMenu.classList.add('hidden');
						accountMenuTrigger.setAttribute('aria-expanded', 'false');
					}
				}
			});

			document.addEventListener('click', function (event) {
				if (searchContainer && searchResults && !event.target.closest('#global-search')) {
					hideSearchResults(searchResults);
				}

				if (mobileSearchContainer && mobileSearchResults && !event.target.closest('#global-search-mobile')) {
					hideSearchResults(mobileSearchResults);
				}
			});

			syncButtons();
			renderFavoritesPanel();
			syncCartButtons();
			renderCartPanel();
		})();
	</script>

	@stack('page-scripts')
</body>
</html>
