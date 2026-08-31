<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Front;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public site
|--------------------------------------------------------------------------
*/
Route::get('/', [Front\PageController::class, 'home'])->name('home');

Route::get('/services', [Front\ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service}', [Front\ServiceController::class, 'show'])->name('services.show');

Route::get('/projects', [Front\ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{project}', [Front\ProjectController::class, 'show'])->name('projects.show');

Route::get('/pricing', [Front\PageController::class, 'pricing'])->name('pricing');
Route::get('/about-us', [Front\PageController::class, 'about'])->name('about');
Route::get('/team/{member}', [Front\TeamController::class, 'show'])->name('team.show');
Route::get('/faqs', [Front\PageController::class, 'faq'])->name('faq');
Route::get('/contact', [Front\PageController::class, 'contact'])->name('contact');

Route::get('/blog', [Front\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post}', [Front\BlogController::class, 'show'])->name('blog.show');

Route::post('/enquiry', [Front\EnquiryController::class, 'store'])->name('enquiry.store');
Route::post('/quote/estimate', [Front\QuoteController::class, 'estimate'])->name('quote.estimate');
Route::post('/quote', [Front\QuoteController::class, 'store'])->name('quote.store');
Route::get('/sitemap.xml', [Front\SitemapController::class, 'index'])->name('sitemap');

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('login', [Admin\AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [Admin\AuthController::class, 'login'])->name('login.attempt');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('logout', [Admin\AuthController::class, 'logout'])->name('logout');

        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Every content type gets the same verbs from ResourceController.
        $resources = [
            'services' => Admin\ServiceController::class,
            'projects' => Admin\ProjectController::class,
            'posts' => Admin\PostController::class,
            'post-categories' => Admin\PostCategoryController::class,
            'team' => Admin\TeamController::class,
            'faqs' => Admin\FaqController::class,
            'faq-categories' => Admin\FaqCategoryController::class,
            'testimonials' => Admin\TestimonialController::class,
            'pages' => Admin\PageController::class,
            'plans' => Admin\PlanController::class,
            'redirects' => Admin\RedirectController::class,
        ];

        foreach ($resources as $slug => $controller) {
            $name = str_replace('-', '_', $slug);

            Route::get($slug, [$controller, 'index'])->name("{$slug}.index");
            Route::get("{$slug}/create", [$controller, 'create'])->name("{$slug}.create");
            Route::post($slug, [$controller, 'store'])->name("{$slug}.store");
            Route::post("{$slug}/reorder", [$controller, 'reorder'])->name("{$slug}.reorder");
            Route::get("{$slug}/{item}/edit", [$controller, 'edit'])->name("{$slug}.edit");
            Route::put("{$slug}/{item}", [$controller, 'update'])->name("{$slug}.update");
            Route::post("{$slug}/{item}/duplicate", [$controller, 'duplicate'])->name("{$slug}.duplicate");
            Route::post("{$slug}/{item}/toggle", [$controller, 'toggle'])->name("{$slug}.toggle");
            Route::delete("{$slug}/{item}", [$controller, 'destroy'])->name("{$slug}.destroy");
        }

        // Media library
        Route::get('media', [Admin\MediaController::class, 'index'])->name('media.index');
        Route::post('media', [Admin\MediaController::class, 'store'])->name('media.store');
        Route::put('media/{medium}', [Admin\MediaController::class, 'update'])->name('media.update');
        Route::delete('media/{medium}', [Admin\MediaController::class, 'destroy'])->name('media.destroy');

        // Enquiries
        Route::get('quotes', [Admin\QuoteController::class, 'index'])->name('quotes.index');
        Route::get('quotes/{quote}', [Admin\QuoteController::class, 'show'])->name('quotes.show');
        Route::delete('quotes/{quote}', [Admin\QuoteController::class, 'destroy'])->name('quotes.destroy');

        Route::get('enquiries', [Admin\EnquiryController::class, 'index'])->name('enquiries.index');
        Route::get('enquiries/export', [Admin\EnquiryController::class, 'export'])->name('enquiries.export');
        Route::get('enquiries/{enquiry}', [Admin\EnquiryController::class, 'show'])->name('enquiries.show');
        Route::delete('enquiries/{enquiry}', [Admin\EnquiryController::class, 'destroy'])->name('enquiries.destroy');

        // Settings + SEO + users
        Route::get('settings', [Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [Admin\SettingController::class, 'update'])->name('settings.update');
        Route::post('settings/new', [Admin\SettingController::class, 'store'])->name('settings.store');
        Route::delete('settings/{setting}', [Admin\SettingController::class, 'destroy'])->name('settings.destroy');

        Route::get('seo', [Admin\SeoController::class, 'index'])->name('seo.index');

        Route::get('users', [Admin\UserController::class, 'index'])->name('users.index');
        Route::get('users/create', [Admin\UserController::class, 'create'])->name('users.create');
        Route::post('users', [Admin\UserController::class, 'store'])->name('users.store');
        Route::get('users/{user}/edit', [Admin\UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [Admin\UserController::class, 'update'])->name('users.update');
        Route::delete('users/{user}', [Admin\UserController::class, 'destroy'])->name('users.destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Editable one-off pages — must stay last so it never shadows a real route.
|--------------------------------------------------------------------------
*/
Route::get('/{slug}', [Front\PageController::class, 'show'])->name('page');
