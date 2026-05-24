<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['id', 'en'])) {
        session(['locale' => $locale]);
    }
    return back();
})->name('lang.switch');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/services', [HomeController::class, 'services'])->name('services');
Route::get('/portfolio', [HomeController::class, 'portfolio'])->name('portfolio');
Route::get('/portfolio/{slug}', [HomeController::class, 'workDetail'])->name('portfolio.detail');
Route::post('/contact', [HomeController::class, 'contact'])->name('contact.store')->middleware('throttle:5,1');
Route::post('/newsletter', [HomeController::class, 'newsletter'])->name('newsletter.store')->middleware('throttle:3,1');
Route::post('/api/analytics/track', [HomeController::class, 'trackEvent'])->name('analytics.track')->middleware('throttle:120,1');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->name('login.post')->middleware('throttle:10,1');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
});

Route::prefix('admin')->name('admin.')->middleware(['admin', 'throttle:60,1'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/content', [AdminController::class, 'editContent'])->name('content');
    Route::put('/content', [AdminController::class, 'updateContent'])->name('content.update');
    Route::get('/messages', [AdminController::class, 'messages'])->name('messages');
    Route::get('/messages/{message}', [AdminController::class, 'showMessage'])->name('messages.show');
    Route::delete('/messages/{message}', [AdminController::class, 'destroyMessage'])->name('messages.destroy');
    Route::get('/testimonials', [AdminController::class, 'testimonials'])->name('testimonials');
    Route::post('/testimonials', [AdminController::class, 'storeTestimonial'])->name('testimonials.store');
    Route::put('/testimonials/{testimonial}', [AdminController::class, 'updateTestimonial'])->name('testimonials.update');
    Route::delete('/testimonials/{testimonial}', [AdminController::class, 'destroyTestimonial'])->name('testimonials.destroy');
    Route::get('/faqs', [AdminController::class, 'faqs'])->name('faqs');
    Route::post('/faqs', [AdminController::class, 'storeFaq'])->name('faqs.store');
    Route::put('/faqs/{faq}', [AdminController::class, 'updateFaq'])->name('faqs.update');
    Route::delete('/faqs/{faq}', [AdminController::class, 'destroyFaq'])->name('faqs.destroy');
    Route::get('/newsletters', [AdminController::class, 'newsletters'])->name('newsletters');
    Route::get('/password', [AdminController::class, 'showPasswordForm'])->name('password');
    Route::put('/password', [AdminController::class, 'updatePassword'])->name('password.update');
    // Intro Sequence
    Route::get('/intro', [AdminController::class, 'editIntro'])->name('intro');
    Route::put('/intro', [AdminController::class, 'updateIntro'])->name('intro.update');
    // Founder Profile
    Route::get('/founder', [AdminController::class, 'editFounder'])->name('founder');
    Route::put('/founder', [AdminController::class, 'updateFounder'])->name('founder.update');
    // Skills (Constellation)
    Route::get('/skills', [AdminController::class, 'skills'])->name('skills');
    Route::post('/skills', [AdminController::class, 'storeSkill'])->name('skills.store');
    Route::put('/skills/{skill}', [AdminController::class, 'updateSkill'])->name('skills.update');
    Route::delete('/skills/{skill}', [AdminController::class, 'destroySkill'])->name('skills.destroy');
});

Route::get('/robots.txt', function () {
    return response("User-agent: *\nAllow: /\nDisallow: /admin/\nSitemap: ".url('/sitemap.xml'), 200, ['Content-Type' => 'text/plain']);
});

Route::get('/sitemap.xml', function () {
    return response('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"><url><loc>'.url('/').'</loc><changefreq>weekly</changefreq><priority>1.0</priority></url><url><loc>'.url('/about').'</loc><changefreq>monthly</changefreq><priority>0.8</priority></url><url><loc>'.url('/services').'</loc><changefreq>monthly</changefreq><priority>0.8</priority></url><url><loc>'.url('/portfolio').'</loc><changefreq>monthly</changefreq><priority>0.8</priority></url></urlset>', 200, ['Content-Type' => 'application/xml']);
});
