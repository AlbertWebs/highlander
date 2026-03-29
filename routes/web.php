<?php

use App\Http\Controllers\Admin\AboutPageController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DestinationController as AdminDestinationController;
use App\Http\Controllers\Admin\GalleryItemController;
use App\Http\Controllers\Admin\HomepageController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\MountainController as AdminMountainController;
use App\Http\Controllers\Admin\NewsletterSubscriberController;
use App\Http\Controllers\Admin\SafariExperienceController;
use App\Http\Controllers\Admin\SafariRequestController as AdminSafariRequestController;
use App\Http\Controllers\Admin\SeoMetaController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\TourController as AdminTourController;
use App\Http\Controllers\Admin\TourItineraryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SafariPlanController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/mountains', [PageController::class, 'mountains'])->name('mountains');
Route::get('/mountains/{mountain:slug}', [PageController::class, 'mountainShow'])->name('mountains.show');
Route::get('/explore-africa', [PageController::class, 'exploreAfrica'])->name('explore-africa');
Route::get('/explore-africa/{destination:slug}', [PageController::class, 'destinationShow'])->name('explore-africa.show');
Route::get('/safari', [PageController::class, 'safari'])->name('safari');
Route::get('/safari/{safari_experience:slug}', [PageController::class, 'safariExperienceShow'])->name('safari.show');
Route::get('/gallery', [PageController::class, 'gallery'])->name('gallery');
Route::get('/articles', [PageController::class, 'articles'])->name('articles');
Route::get('/articles/{article:slug}', [PageController::class, 'articleShow'])->name('articles.show');
Route::get('/experiences/{tour:slug}', [PageController::class, 'featuredExperienceShow'])->name('experiences.show');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/plan-my-safari', [SafariPlanController::class, 'index'])->name('plan-my-safari');
Route::post('/plan-my-safari', [SafariPlanController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('plan-my-safari.store');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/photo-credits', [PageController::class, 'photoCredits'])->name('photo-credits');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');
Route::post('/newsletter', [NewsletterController::class, 'store'])->name('newsletter.store');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => redirect()->route('admin.dashboard'))->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('bookings', AdminBookingController::class)->except(['create', 'store']);
    Route::resource('tours', AdminTourController::class);
    Route::patch('tours/{tour}/toggle', [AdminTourController::class, 'toggle'])->name('tours.toggle');
    Route::get('tours/{tour}/itinerary', [TourItineraryController::class, 'edit'])->name('tours.itinerary.edit');
    Route::put('tours/{tour}/itinerary', [TourItineraryController::class, 'update'])->name('tours.itinerary.update');
    Route::resource('destinations', AdminDestinationController::class)->except(['show']);
    Route::patch('destinations/{destination}/toggle', [AdminDestinationController::class, 'toggle'])->name('destinations.toggle');
    Route::resource('mountains', AdminMountainController::class)->except(['show']);
    Route::patch('mountains/{mountain}/toggle', [AdminMountainController::class, 'toggle'])->name('mountains.toggle');
    Route::resource('safari', SafariExperienceController::class)->parameters(['safari' => 'safari_experience'])->except(['show']);
    Route::patch('safari/{safari_experience}/toggle', [SafariExperienceController::class, 'toggle'])->name('safari.toggle');
    Route::post('gallery/bulk', [GalleryItemController::class, 'bulkStore'])->name('gallery.bulk-store');
    Route::resource('gallery', GalleryItemController::class)->parameters(['gallery' => 'gallery_item'])->except(['show']);
    Route::patch('gallery/{gallery_item}/toggle', [GalleryItemController::class, 'toggle'])->name('gallery.toggle');
    Route::resource('articles', AdminArticleController::class)->except(['show']);
    Route::patch('articles/{article}/toggle', [AdminArticleController::class, 'toggle'])->name('articles.toggle');
    Route::resource('testimonials', AdminTestimonialController::class)->except(['show']);
    Route::patch('testimonials/{testimonial}/toggle', [AdminTestimonialController::class, 'toggle'])->name('testimonials.toggle');
    Route::resource('users', AdminUserController::class)->except(['show']);
    Route::resource('contact-messages', ContactMessageController::class)->only(['index', 'show', 'destroy']);
    Route::resource('safari-requests', AdminSafariRequestController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::patch('contact-messages/{contact_message}/read', [ContactMessageController::class, 'markRead'])->name('contact-messages.read');
    Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('homepage', [HomepageController::class, 'edit'])->name('homepage.edit');
    Route::put('homepage', [HomepageController::class, 'update'])->name('homepage.update');
    Route::get('about-page', [AboutPageController::class, 'edit'])->name('about-page.edit');
    Route::put('about-page', [AboutPageController::class, 'update'])->name('about-page.update');
    Route::get('media', [MediaController::class, 'index'])->name('media.index');
    Route::post('media', [MediaController::class, 'store'])->name('media.store');
    Route::delete('media/{mediaFile}', [MediaController::class, 'destroy'])->name('media.destroy');
    Route::resource('seo', SeoMetaController::class)->parameters(['seo' => 'seo_meta'])->except(['show']);
    Route::resource('newsletter-subscribers', NewsletterSubscriberController::class)->only(['index', 'destroy']);
});

require __DIR__.'/auth.php';
