<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/membership', [PublicController::class, 'membership'])->name('membership');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicController::class, 'submitContactInquiry'])->name('contact.submit');
Route::get('/privacy-policy', [PublicController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms-and-conditions', [PublicController::class, 'termsConditions'])->name('terms-conditions');
Route::get('/location/{slug}', [PublicController::class, 'locationDetail'])->name('location.detail');

// Products
Route::get('/products', [PublicController::class, 'products'])->name('products');
Route::get('/products/{slug}', [PublicController::class, 'productDetail'])->name('product.detail');
Route::get('/api/search-products', [PublicController::class, 'searchProducts'])->name('api.search.products');
Route::get('/api/filter-products', [PublicController::class, 'filterProducts'])->name('api.filter.products');

// Blogs
Route::get('/blogs', [PublicController::class, 'blogs'])->name('blogs');
Route::get('/blogs/{slug}', [PublicController::class, 'blogDetail'])->name('blog.detail');

/*
|--------------------------------------------------------------------------
| Guest Routes (unauthenticated only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    // Admin Auth
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

    // Member Auth Views (GET only — POST routes are outside guest to avoid AJAX HTML redirects)
    Route::prefix('member')->name('member.')->group(function () {
        Route::get('/login', [App\Http\Controllers\Auth\MemberAuthController::class, 'showLoginForm'])->name('login');
        Route::get('/register', [App\Http\Controllers\Auth\MemberAuthController::class, 'showRegisterForm'])->name('register');
    });
});

/*
|--------------------------------------------------------------------------
| Member Auth POST Routes (outside guest middleware — AJAX safe)
|--------------------------------------------------------------------------
*/
Route::prefix('member')->name('member.')->group(function () {
    Route::post('/send-login-otp', [App\Http\Controllers\Auth\MemberAuthController::class, 'sendLoginOtp'])->name('send-login-otp');
    Route::post('/verify-login-otp', [App\Http\Controllers\Auth\MemberAuthController::class, 'verifyLoginOtp'])->name('verify-login-otp');
    Route::post('/send-register-otp', [App\Http\Controllers\Auth\MemberAuthController::class, 'sendRegisterOtp'])->name('send-register-otp');
    Route::post('/register', [App\Http\Controllers\Auth\MemberAuthController::class, 'register'])->name('register.submit');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboards
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/member/dashboard', [DashboardController::class, 'member'])->name('member.dashboard');
    Route::get('/delivery/dashboard', [DashboardController::class, 'delivery'])->name('delivery.dashboard');

    // Auth Actions
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('/member/logout', [App\Http\Controllers\Auth\MemberAuthController::class, 'logout'])->name('member.logout');

    // Profile & Account
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('/account', [App\Http\Controllers\ProfileController::class, 'account'])->name('account');
    Route::delete('/account', [App\Http\Controllers\ProfileController::class, 'deleteAccount'])->name('account.delete');

    // Membership
    Route::post('/membership/subscribe', [App\Http\Controllers\MembershipController::class, 'subscribe'])->name('membership.subscribe');

    // Payments
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::post('/initiate', [App\Http\Controllers\PaymentController::class, 'initiate'])->name('initiate');
        Route::any('/callback', [App\Http\Controllers\PaymentController::class, 'callback'])->name('callback');
        Route::get('/success/{order}', [App\Http\Controllers\PaymentController::class, 'success'])->name('success');
        Route::get('/failure', [App\Http\Controllers\PaymentController::class, 'failure'])->name('failure');
        Route::get('/history', [App\Http\Controllers\PaymentController::class, 'history'])->name('history');
        Route::get('/invoice/{order}', [App\Http\Controllers\PaymentController::class, 'invoice'])->name('invoice');
    });

    // Member Area
    Route::prefix('member')->name('member.')->group(function () {
        Route::resource('support-tickets', App\Http\Controllers\Member\SupportTicketController::class)->only(['index', 'create', 'store', 'show']);
        Route::get('referrals', [App\Http\Controllers\Member\ReferralController::class, 'index'])->name('referrals.index');
        Route::get('loyalty-points', [App\Http\Controllers\Member\LoyaltyPointController::class, 'index'])->name('loyalty-points.index');
    });

    /*
    |----------------------------------------------------------------------
    | Admin Routes
    |----------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

        // Users
        Route::resource('users', App\Http\Controllers\UserController::class);

        // Content Management
        Route::resource('sliders', App\Http\Controllers\Admin\SliderController::class);
        Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
        Route::resource('types', App\Http\Controllers\Admin\TypeController::class);
        Route::resource('announcements', App\Http\Controllers\Admin\AnnouncementController::class);
        Route::resource('testimonials', App\Http\Controllers\Admin\TestimonialController::class);
        Route::resource('whychooseus', App\Http\Controllers\Admin\WhyChooseUsController::class);
        Route::resource('usps', App\Http\Controllers\Admin\UspController::class);
        Route::resource('content-sections', App\Http\Controllers\Admin\ContentSectionController::class);
        Route::resource('about-sections', App\Http\Controllers\Admin\AboutSectionController::class);

        // Products
        Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
        Route::delete('products/{product}/images', [App\Http\Controllers\Admin\ProductController::class, 'removeImage'])->name('products.remove-image');
        Route::post('products/{product}/images/reorder', [App\Http\Controllers\Admin\ProductController::class, 'reorderImages'])->name('products.reorder-images');

        // Blogs
        Route::resource('blogs', App\Http\Controllers\Admin\BlogController::class);
        Route::post('blogs/upload-image', [App\Http\Controllers\Admin\BlogController::class, 'uploadImage'])->name('blogs.upload-image');

        // Pages
        Route::get('contact-page', [App\Http\Controllers\Admin\ContactPageController::class, 'index'])->name('contact-page.index');
        Route::post('contact-page', [App\Http\Controllers\Admin\ContactPageController::class, 'update'])->name('contact-page.update');
        Route::get('about-page', [App\Http\Controllers\Admin\AboutPageController::class, 'index'])->name('about-page.index');
        Route::post('about-page', [App\Http\Controllers\Admin\AboutPageController::class, 'update'])->name('about-page.update');
        Route::get('legal-pages/{pageKey}', [App\Http\Controllers\Admin\LegalPageController::class, 'index'])->name('legal-pages.index');
        Route::post('legal-pages/{pageKey}', [App\Http\Controllers\Admin\LegalPageController::class, 'update'])->name('legal-pages.update');

        // Membership
        Route::resource('membership-plans', App\Http\Controllers\Admin\MembershipPlanController::class);
        Route::resource('membership-benefits', App\Http\Controllers\Admin\MembershipBenefitController::class);
        Route::resource('membership-faqs', App\Http\Controllers\Admin\MembershipFaqController::class);
        Route::resource('membership-steps', App\Http\Controllers\Admin\MembershipStepController::class);

        // Subscriptions
        Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\UserSubscriptionController::class, 'index'])->name('index');
            Route::get('{subscription}', [App\Http\Controllers\Admin\UserSubscriptionController::class, 'show'])->name('show');
            Route::post('{subscription}/status', [App\Http\Controllers\Admin\UserSubscriptionController::class, 'updateStatus'])->name('update-status');
            Route::post('{subscription}/payment', [App\Http\Controllers\Admin\UserSubscriptionController::class, 'updatePaymentStatus'])->name('update-payment');
            Route::post('{subscription}/note', [App\Http\Controllers\Admin\UserSubscriptionController::class, 'addNote'])->name('add-note');
            Route::get('{subscription}/deliveries', [App\Http\Controllers\Admin\DeliveryLogController::class, 'index'])->name('deliveries.index');
            Route::post('{subscription}/deliveries/generate', [App\Http\Controllers\Admin\DeliveryLogController::class, 'generateSchedule'])->name('deliveries.generate');
        });

        // Deliveries
        Route::prefix('deliveries')->name('deliveries.')->group(function () {
            Route::get('today', [App\Http\Controllers\Admin\DeliveryLogController::class, 'todayDeliveries'])->name('today');
            Route::post('today/export', [App\Http\Controllers\Admin\DeliveryLogController::class, 'exportToday'])->name('today.export');
            Route::get('exports', [App\Http\Controllers\Admin\DeliveryLogController::class, 'exportList'])->name('exports.list');
            Route::delete('exports/{export}', [App\Http\Controllers\Admin\DeliveryLogController::class, 'exportDelete'])->name('exports.delete');
            Route::post('bulk-update', [App\Http\Controllers\Admin\DeliveryLogController::class, 'bulkUpdateToday'])->name('bulk-update');
            Route::post('{delivery}/status', [App\Http\Controllers\Admin\DeliveryLogController::class, 'updateStatus'])->name('update-status');
            Route::post('{delivery}/forward', [App\Http\Controllers\Admin\DeliveryLogController::class, 'forwardToNextDay'])->name('forward');
        });

        // Contact Inquiries
        Route::prefix('contact-inquiries')->name('contact-inquiries.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\ContactInquiryController::class, 'index'])->name('index');
            Route::get('{contactInquiry}', [App\Http\Controllers\Admin\ContactInquiryController::class, 'show'])->name('show');
            Route::post('{contactInquiry}/status', [App\Http\Controllers\Admin\ContactInquiryController::class, 'updateStatus'])->name('update-status');
            Route::delete('{contactInquiry}', [App\Http\Controllers\Admin\ContactInquiryController::class, 'destroy'])->name('destroy');
        });

        // Support Tickets
        Route::resource('support-tickets', App\Http\Controllers\Admin\SupportTicketController::class)->except(['create', 'store']);

        // Offers & Engagement
        Route::resource('coupons', App\Http\Controllers\Admin\CouponController::class);
        Route::resource('referral-codes', App\Http\Controllers\Admin\ReferralCodeController::class);
        Route::resource('loyalty-points', App\Http\Controllers\Admin\LoyaltyPointController::class);

        // Locations & SEO
        Route::resource('locations', App\Http\Controllers\Admin\LocationController::class);
        Route::resource('seo-metas', App\Http\Controllers\Admin\SeoMetaController::class);
    });
});
