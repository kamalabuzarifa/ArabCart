<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\FacebookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/groups', function () {
    return view('groups');
})->name('groups.index');

Route::get('/new-arrivals', function () {
    return view('new-arrivals');
})->name('new-arrivals.index');

Route::get('/offers', function () {
    return view('offers');
})->name('offers.index');

Route::get('/help', function () {
    return view('help.page', [
        'title' => 'المساعدة',
        'eyebrow' => 'مركز المساعدة',
        'icon' => 'support_agent',
        'description' => 'هنا ستجد إجابات سريعة، وتتبّعًا واضحًا للطلبات، ودعمًا مباشرًا يساعدك على إنهاء المطلوب بأقل وقت ممكن.',
        'sectionTitle' => 'ماذا يميز دعمنا؟',
        'items' => [
            ['title' => 'تتبّع الطلبات', 'text' => 'راجع حالة الطلبات من لوحة حسابك واتبع خطوات الشحن والدفع بسهولة.'],
            ['title' => 'إدارة الحساب', 'text' => 'حدّث بياناتك الشخصية وطرق التواصل والإعدادات الأساسية من مكان واحد.'],
            ['title' => 'حل المشاكل', 'text' => 'إذا واجهت مشكلة في الدفع أو الشحن أو تسجيل الدخول فابدأ من هنا.'],
        ],
        'highlights' => [
            ['icon' => 'schedule', 'title' => 'استجابة أسرع', 'text' => 'نقصر وقت الانتظار ونوجّهك بسرعة إلى الحل المناسب.'],
            ['icon' => 'mail', 'title' => 'فريق مختص', 'text' => 'نراجع رسائلك حسب نوع الطلب ونحوّلها للفريق الأنسب مباشرة.'],
            ['icon' => 'help', 'title' => 'إرشاد مباشر', 'text' => 'إذا كانت المشكلة بسيطة ستجد الخطوات الأساسية في صفحة المساعدة أولًا.'],
        ],
        'ctaTitle' => 'تواصل بطريقتك',
        'ctaText' => 'اختر القناة المناسبة من الأسفل أو تواصل معنا مباشرة، وسنرد عليك بسرعة ونقودك إلى الحل الأنسب دون تعقيد.',
        'contacts' => [
            ['icon' => 'alternate_email', 'label' => 'بريد', 'value' => 'kamalabuzarifa@gmail.com', 'href' => 'mailto:kamalabuzarifa@gmail.com'],
            ['icon' => 'call', 'label' => 'اتصال', 'value' => '+972 593 236 412', 'href' => 'tel:+972593236412'],
        ],
    ]);
})->name('help.index');

Route::get('/privacy-policy', function () {
    return view('help.page', [
        'title' => 'سياسة الخصوصية',
        'eyebrow' => 'الخصوصية والأمان',
        'icon' => 'lock',
        'description' => 'نوضح هنا كيف نجمع البيانات، وكيف نستخدمها، وما هي الإجراءات التي نتخذها لحماية معلوماتك الشخصية.',
        'sectionTitle' => 'ماذا نلتزم به؟',
        'items' => [
            ['title' => 'جمع البيانات', 'text' => 'نجمع فقط البيانات اللازمة لتشغيل الخدمات وتحسين التجربة.'],
            ['title' => 'الاستخدام', 'text' => 'تُستخدم المعلومات لتشغيل الطلبات والتواصل وتخصيص التجربة داخل المتجر.'],
            ['title' => 'الحماية', 'text' => 'نعتمد إجراءات حماية مناسبة لتقليل المخاطر على بياناتك.'],
        ],
        'highlights' => [
            ['icon' => 'shield', 'title' => 'حماية', 'text' => 'نحافظ على بياناتك ضمن إجراءات وصول ومراجعة داخلية.'],
            ['icon' => 'visibility', 'title' => 'شفافية', 'text' => 'نوضح لك ما الذي نحتفظ به ولماذا نحتاجه.'],
            ['icon' => 'delete', 'title' => 'تحكم', 'text' => 'يمكنك طلب تحديث أو حذف بعض البيانات وفق الأنظمة المعمول بها.'],
        ],
        'ctaTitle' => 'أسئلة حول الخصوصية؟',
        'ctaText' => 'إذا احتجت توضيحًا إضافيًا، افتح صفحة المساعدة أو تواصل معنا مباشرة وسنشرح لك ما يلزم بسرعة ووضوح.',
        'contacts' => [
            ['icon' => 'support_agent', 'label' => 'مركز المساعدة', 'value' => 'زيارة الصفحة', 'href' => route('help.index')],
            ['icon' => 'alternate_email', 'label' => 'البريد', 'value' => 'kamalabuzarifa@gmail.com
', 'href' => 'mailto:kamalabuzarifa@gmail.com
'],
        ],
    ]);
})->name('privacy.index');

Route::get('/terms-conditions', function () {
    return view('help.page', [
        'title' => 'الشروط والأحكام',
        'eyebrow' => 'قواعد الاستخدام',
        'icon' => 'description',
        'description' => 'هذه الصفحة تلخص شروط استخدام المتجر، وما الذي يخص الطلبات والدفع والمحتوى داخل المنصة.',
        'sectionTitle' => 'أهم ما يجب معرفته',
        'items' => [
            ['title' => 'الاستخدام المقبول', 'text' => 'استخدم المنصة بشكل قانوني وراعِ حقوق الآخرين وخصوصيتهم.'],
            ['title' => 'الطلبات والدفع', 'text' => 'تخضع الطلبات لتوفر المنتج، وطريقة الدفع، وسياسة الإلغاء المعتمدة.'],
            ['title' => 'المحتوى', 'text' => 'يبقى المستخدم مسؤولًا عن البيانات والصور التي يضيفها بنفسه.'],
        ],
        'highlights' => [
            ['icon' => 'gavel', 'title' => 'الالتزام', 'text' => 'استخدامك للمنصة يعني موافقتك على الشروط والسياسات المنشورة.'],
            ['icon' => 'receipt_long', 'title' => 'الطلبات', 'text' => 'يتم اعتماد الطلبات وفق البيانات المتوفرة ونتيجة الدفع.'],
            ['icon' => 'warning', 'title' => 'الحدود', 'text' => 'قد نوقف بعض الخدمات عند وجود مخالفة واضحة أو إساءة استخدام.'],
        ],
        'ctaTitle' => 'قبل المتابعة',
        'ctaText' => 'راجع البنود الأساسية، وإذا بقي لديك سؤال عملي فانتقل إلى صفحة المساعدة أو تواصل معنا مباشرة.',
        'contacts' => [
            ['icon' => 'support_agent', 'label' => 'المساعدة', 'value' => 'فتح الصفحة', 'href' => route('help.index')],
            ['icon' => 'call', 'label' => 'اتصال', 'value' => '+972 593 236 412', 'href' => 'tel:+972593236412'],
        ],
    ]);
})->name('terms.index');

Route::get('/contact-us', function () {
    return view('help.page', [
        'title' => 'اتصل بنا',
        'eyebrow' => 'تواصل مباشر',
        'icon' => 'call',
        'description' => 'إذا احتجت مساعدة أو كان لديك اقتراح أو مشكلة، تواصل معنا عبر البريد أو الهاتف أو من خلال مركز المساعدة.',
        'sectionTitle' => 'طرق التواصل',
        'items' => [
            ['title' => 'هاتف الدعم', 'text' => 'اتصل بنا في ساعات العمل وسنوجّهك مباشرة إلى الخطوة المناسبة.'],
            ['title' => 'البريد الإلكتروني', 'text' => 'أرسل تفاصيل طلبك أو مشكلتك وسنرد عليك بأقرب وقت ممكن.'],
            ['title' => 'الرسائل السريعة', 'text' => 'يمكنك أيضًا استخدام قنوات التواصل الظاهرة في أسفل الصفحة.'],
        ],
        'highlights' => [
            ['icon' => 'schedule', 'title' => 'استجابة أسرع', 'text' => 'نحاول تقليل وقت الانتظار والرد بوضوح.'],
            ['icon' => 'mail', 'title' => 'بريد مخصص', 'text' => 'نراجع الرسائل حسب نوع الطلب ونحوّلها للفريق المناسب.'],
            ['icon' => 'help', 'title' => 'إرشاد مباشر', 'text' => 'إذا كانت المشكلة بسيطة قد تجد الحل في صفحة المساعدة أولًا.'],
        ],
        'ctaTitle' => 'نحن هنا',
        'ctaText' => 'أرسل رسالتك أو استخدم الهاتف، وسنتعامل مع طلبك بأسرع طريقة ممكنة ونوجّهك للحل المناسب مباشرة.',
        'contacts' => [
            ['icon' => 'alternate_email', 'label' => 'البريد', 'value' => 'kamalabuzarifa@gmail.com', 'href' => 'mailto:kamalabuzarifa@gmail.com'],
            ['icon' => 'call', 'label' => 'الهاتف', 'value' => '+972 593 236 412', 'href' => 'tel:+972593236412'],
        ],
    ]);
})->name('contact.index');

Route::get('/faq', function () {
    return view('help.page', [
        'title' => 'الأسئلة الشائعة',
        'eyebrow' => 'FAQ',
        'icon' => 'quiz',
        'description' => 'جمعنا أكثر الأسئلة تكرارًا مع إجابات مختصرة وواضحة حتى تصل للمعلومة بسرعة.',
        'sectionTitle' => 'الأسئلة الأكثر شيوعًا',
        'items' => [
            ['title' => 'كيف أبدأ الشراء؟', 'text' => 'ابحث عن المنتج، أضفه إلى السلة، ثم أكمل خطوات الدفع بسهولة.'],
            ['title' => 'كيف أتابع الطلب؟', 'text' => 'من لوحة الحساب يمكنك مراجعة حالة الطلب والتفاصيل المرتبطة به.'],
            ['title' => 'ماذا لو لم أجد الإجابة؟', 'text' => 'استخدم صفحة المساعدة أو اتصل بنا وسنساعدك مباشرة.'],
        ],
        'highlights' => [
            ['icon' => 'help_center', 'title' => 'إجابات جاهزة', 'text' => 'هنا ستجد حلولًا سريعة لأكثر الأسئلة تكرارًا.'],
            ['icon' => 'shopping_cart', 'title' => 'الشراء', 'text' => 'خطوات بسيطة من التصفح حتى إتمام الطلب.'],
            ['icon' => 'person', 'title' => 'الحساب', 'text' => 'تعرف على كيفية إدارة بياناتك ومتابعة نشاطك داخل المتجر.'],
        ],
        'ctaTitle' => 'لم تجد سؤالك؟',
        'ctaText' => 'انتقل إلى صفحة المساعدة أو تواصل معنا مباشرة، وسنرد عليك بأقرب وقت ممكن.',
        'contacts' => [
            ['icon' => 'support_agent', 'label' => 'المساعدة', 'value' => 'زيارة الصفحة', 'href' => route('help.index')],
            ['icon' => 'call', 'label' => 'اتصال', 'value' => '+972 593 236 412', 'href' => 'tel:+972593236412'],
        ],
    ]);
})->name('faq.index');

Route::post('/newsletter/subscribe', function () {
    request()->validate([
        'email' => ['required', 'email'],
    ]);

    session()->put('newsletter_subscribed', true);

    return back();
})->name('newsletter.subscribe');

Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');
Route::get('/marketplace/search', [MarketplaceController::class, 'search'])->name('marketplace.search');
Route::get('/marketplace/{listing}/image', [MarketplaceController::class, 'image'])->name('marketplace.image');

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/auth/google', [GoogleController::class, 'redirect']);
    Route::get('/auth/google/callback', [GoogleController::class, 'callback']);
    Route::get('/auth/facebook', [FacebookController::class, 'redirect']);
    Route::get('/auth/facebook/callback', [FacebookController::class, 'callback']);
});

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::get('/checkout/track', function () {
        return view('checkout.track');
    })->name('checkout.track');
    Route::get('/checkout/success', function () {
        return view('checkout.success');
    })->name('checkout.success');
    Route::post('/checkout/complete', [CheckoutController::class, 'complete'])->name('checkout.complete');

    Route::get('/marketplace/create', [MarketplaceController::class, 'create'])->name('marketplace.create');
    Route::post('/marketplace', [MarketplaceController::class, 'store'])->name('marketplace.store');
    Route::get('/marketplace/{listing}/edit', [MarketplaceController::class, 'edit'])->name('marketplace.edit');
    Route::put('/marketplace/{listing}', [MarketplaceController::class, 'update'])->name('marketplace.update');
    Route::delete('/marketplace/{listing}', [MarketplaceController::class, 'destroy'])->name('marketplace.destroy');
    Route::post('/marketplace/{listing}/buy', [MarketplaceController::class, 'buy'])->name('marketplace.buy');
    Route::post('/marketplace/{listing}/orders/{order}/review', [ProductReviewController::class, 'store'])->name('marketplace.reviews.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
