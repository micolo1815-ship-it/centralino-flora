<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TreeController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\TestEmailController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GenerateReportController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AuthController;

Route::get('/test-email', [TestEmailController::class, 'sendTestEmail']);

// ── Public routes ─────────────────────────────────────────────────────────────
Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home.index');
    Route::get('/abouts', 'abouts')->name('home.abouts');
    Route::get('/abouts/history-prev-officers', 'historical_officers')->name('home.historical_officers');
    Route::get('/contact', 'contact')->name('home.contact');
    Route::get('/forest', 'show')->name('forestry.forestrylist');
    Route::get('/forest/{location}', 'location')->name('home.location');
    Route::get('/forest/{location}/{tree}', 'tree')->name('home.tree');
    Route::get('/tree/{tree}', 'trees')->name('home.trees');
});

Route::get('/forestry', [TreeController::class, 'index']);
Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('auth.forgot');
Route::post('/forgot-password', [AuthController::class, 'sendOtp'])->name('auth.sendOtp');
Route::get('/verify-otp', [AuthController::class, 'verifyOtpPage'])->name('auth.verifyOtp');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('auth.checkOtp');
Route::get('/reset-password', [AuthController::class, 'resetPasswordPage'])->name('auth.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('auth.update');
Route::get('/resend-otp', [AuthController::class, 'resendOtp'])->name('auth.resendOtp');
// ── Authenticated routes ───────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard.index');
        Route::get('/about', 'about');
        Route::get('/activity-log', 'activity');

        // ✅ Analytics — restricted to admin-it, program-chair, adviser only
        Route::get('/analytics', 'analytics')
            ->name('dashboard.analytics')
            ->middleware('role:Program Chair,Adviser,IT');
    });
    Route::controller(UsersController::class)->group(function () {
        Route::get('/users', 'index')->name('users.index');
        Route::get('/users/{user}/edit', 'edit')->name('users.edit')
            ->middleware('role:Program Chair,Adviser,IT');
        Route::patch('/users/{user}', 'update')->name('users.update')
            ->middleware('role:Program Chair,Adviser,IT');
        Route::patch('/users/{user}/password', 'updatePassword')
            ->name('users.password')
            ->middleware('role:Program Chair,Adviser,IT');
    });

    // Reports
    Route::controller(GenerateReportController::class)->group(function () {
        Route::get('/report', 'generateReport')->name('reports.generate');
        Route::post('/report/display', 'display')->name('reports.export');
        Route::post('/report/download-pdf', 'downloadPdf')->name('reports.pdf');
    });

    // Profile
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile.index');
        Route::patch('/profile/update', 'update')->name('profile.update');
        Route::patch('/profile/password', 'updatePassword')->name('profile.password.update');
    });

    // Trees
    Route::controller(TreeController::class)->group(function () {
        Route::get('/trees', 'index')->name('tree.index');
        Route::get('/trees/create', 'create')->name('tree.create');
        Route::post('/trees', 'store')->name('tree.store');
        Route::get('/trees/{tree}/edit', 'edit')->name('tree.edit');
        Route::put('/trees/{tree}', 'update')->name('tree.update');
    });

    // Locations
    Route::controller(LocationController::class)->group(function () {
        Route::get('/locations', 'index')->name('location.index');
        Route::get('/locations/create', 'create')->name('location.create');
        Route::post('/locations', 'store')->name('location.store');
        Route::get('/locations/{location}/edit', 'edit')->name('location.edit');
        Route::put('/locations/{location}', 'update')->name('location.update');
    });

    // About / Officers
    Route::controller(AboutController::class)->group(function () {
        Route::get('/about', 'index')->name('about.index');
        Route::get('/about/create', 'create')->name('about.create');
        Route::get('/about/{school_year}/edit', 'edit')->name('about.edit');
        Route::post('/about/store', 'store')->name('about.store');
        Route::get('/about/previous_officers', 'previous_view')->name('about.previous_view');
        Route::get('/about/previous_officers/{school_year}/', 'previous_edit')->name('about.previous_edit');
        Route::post('/about/previous_officers/{school_year}/update', 'previous_edit_update')->name('about.previous_edit_update');
        Route::get('/about/current_officers', 'edit_current')->name('about.edit_current');
        Route::post('/about/current_officers', 'update_current')->name('about.update_current');
    });
});

// ── Auth routes ───────────────────────────────────────────────────────────────
Route::get('/login', [SessionController::class, 'create'])->name('login');
Route::post('/login', [SessionController::class, 'store']);
Route::post('/logout', [SessionController::class, 'destroy'])->name('logout');
Route::get('/forgot-password', [SessionController::class, 'forgot']);
