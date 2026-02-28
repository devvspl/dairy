<?php
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;
// Public routes
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/membership', [PublicController::class, 'membership'])->name('membership');
Route::get('/products', [PublicController::class, 'products'])->name('products');
Route::get('/products/{slug}', [PublicController::class, 'productDetail'])->name('product.detail');
Route::get('/api/search-products', [PublicController::class, 'searchProducts'])->name('api.search.products');
Route::get('/api/filter-products', [PublicController::class, 'filterProducts'])->name('api.filter.products');
Route::get('/blogs', [PublicController::class, 'blogs'])->name('blogs');
Route::get('/blogs/{slug}', [PublicController::class, 'blogDetail'])->name('blog.detail');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicController::class, 'submitContactInquiry'])->name('contact.submit');
Route::get('/privacy-policy', [PublicController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms-and-conditions', [PublicController::class, 'termsConditions'])->name('terms-conditions');
// Guest routes (redirect to dashboard if authenticated)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});
// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/member/dashboard', [DashboardController::class, 'index'])->name('member.dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('/account', [App\Http\Controllers\ProfileController::class, 'account'])->name('account');
    Route::delete('/account', [App\Http\Controllers\ProfileController::class, 'deleteAccount'])->name('account.delete');
    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        Route::resource('users', App\Http\Controllers\UserController::class);
        Route::resource('sliders', App\Http\Controllers\Admin\SliderController::class);
        Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
        Route::resource('types', App\Http\Controllers\Admin\TypeController::class);
        Route::resource('announcements', App\Http\Controllers\Admin\AnnouncementController::class);
        Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
        Route::resource('testimonials', App\Http\Controllers\Admin\TestimonialController::class);
        Route::resource('blogs', App\Http\Controllers\Admin\BlogController::class);
        Route::post('blogs/upload-image', [App\Http\Controllers\Admin\BlogController::class, 'uploadImage'])->name('blogs.upload-image');
        Route::resource('whychooseus', App\Http\Controllers\Admin\WhyChooseUsController::class);
        Route::resource('usps', App\Http\Controllers\Admin\UspController::class);
        Route::resource('content-sections', App\Http\Controllers\Admin\ContentSectionController::class);
        Route::resource('about-sections', App\Http\Controllers\Admin\AboutSectionController::class);
        
        Route::get('contact-page', [App\Http\Controllers\Admin\ContactPageController::class, 'index'])->name('contact-page.index');
        Route::post('contact-page', [App\Http\Controllers\Admin\ContactPageController::class, 'update'])->name('contact-page.update');
        
        Route::get('about-page', [App\Http\Controllers\Admin\AboutPageController::class, 'index'])->name('about-page.index');
        Route::post('about-page', [App\Http\Controllers\Admin\AboutPageController::class, 'update'])->name('about-page.update');
        
        Route::get('legal-pages/{pageKey}', [App\Http\Controllers\Admin\LegalPageController::class, 'index'])->name('legal-pages.index');
        Route::post('legal-pages/{pageKey}', [App\Http\Controllers\Admin\LegalPageController::class, 'update'])->name('legal-pages.update');
        
        Route::resource('membership-plans', App\Http\Controllers\Admin\MembershipPlanController::class);
        Route::resource('membership-benefits', App\Http\Controllers\Admin\MembershipBenefitController::class);
        Route::resource('membership-faqs', App\Http\Controllers\Admin\MembershipFaqController::class);
        Route::resource('membership-steps', App\Http\Controllers\Admin\MembershipStepController::class);
        
        Route::get('contact-inquiries', [App\Http\Controllers\Admin\ContactInquiryController::class, 'index'])->name('contact-inquiries.index');
        Route::get('contact-inquiries/{contactInquiry}', [App\Http\Controllers\Admin\ContactInquiryController::class, 'show'])->name('contact-inquiries.show');
        Route::post('contact-inquiries/{contactInquiry}/status', [App\Http\Controllers\Admin\ContactInquiryController::class, 'updateStatus'])->name('contact-inquiries.update-status');
        Route::delete('contact-inquiries/{contactInquiry}', [App\Http\Controllers\Admin\ContactInquiryController::class, 'destroy'])->name('contact-inquiries.destroy');
        
        Route::resource('seo-metas', App\Http\Controllers\Admin\SeoMetaController::class);
    });
});
