<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\DependentDropdownController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\BotManController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminEventController;
use App\Http\Controllers\LokasiController;

// ========================================
// WELCOME & HOME ROUTES
// ========================================

// Welcome/Landing Page - Accessible without login
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Home redirect - untuk navigasi yang lebih intuitif
Route::get('/home', function() {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->role === 'admin' || $user->is_admin == 1) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.home');
    }
    return redirect()->route('welcome');
})->name('home');

// Public Article routes
Route::get('/article', [ArticleController::class, 'index'])->name('article.index');
Route::get('/article/{slug}', [ArticleController::class, 'show'])->name('article.show');

// Public Information routes
Route::get('/contact', function() {
    return view('informasi.contact');
})->name('contact');

Route::get('/about', function() {
    return view('informasi.about');
})->name('about');

// Public Location routes
Route::get('/location', [LokasiController::class, 'publicIndex'])->name('location.index');
Route::get('/location/{id}', [LokasiController::class, 'publicShow'])->name('location.show');
Route::get('/location/nearby/search', [LokasiController::class, 'publicNearby'])->name('location.nearby');

// ========================================
// PUBLIC EVENTS (Alternative dengan prefix informasi)
// ========================================
Route::prefix('informasi')->name('informasi.')->group(function () {
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    
    // Event creation (requires auth) - pindahkan ke atas sebelum route dengan parameter
    Route::middleware('auth')->group(function () {
        Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
        Route::post('/events', [EventController::class, 'store'])->name('events.store');
    });

    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
});




// Legacy support for old dropdown routes
Route::get('/provinces', [DependentDropdownController::class, 'provinces'])->name('provinces');
Route::get('/cities', [DependentDropdownController::class, 'cities'])->name('cities');
Route::get('/districts', [DependentDropdownController::class, 'districts'])->name('districts');
Route::get('/villages', [DependentDropdownController::class, 'villages'])->name('villages');

// ========================================
// BOTMAN ROUTES
// ========================================
Route::get('/botman/iframe', function() {
    return view('botman.iframe');
})->name('botman.iframe');
Route::post('/botman', [BotManController::class, 'handle'])->name('botman.handle');

// ========================================
// AUTHENTICATION ROUTES (GUEST ONLY)
// ========================================
Route::middleware('guest')->group(function () {
    // Registration routes
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'registerPost'])->name('register.post');
    
    // Login routes
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginPost'])->name('login.post');
});

// ========================================
// AUTHENTICATED USER ROUTES
// ========================================
Route::middleware('auth')->group(function () {
    
    // Logout routes
    Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout.post');
    
    // Dashboard redirect berdasarkan role
    Route::get('/dashboard', function() {
        $user = auth()->user();
        
        if ($user->role === 'admin' || $user->is_admin == 1) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('user.home');
        }
    })->name('dashboard');
    
    // User Dashboard/Home
    Route::get('/user/home', [HomeController::class, 'userHome'])->name('user.home');
    
    // ========================================
    // USER PROFILE ROUTES - FIXED
    // ========================================
    Route::prefix('user/profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/form', [ProfileController::class, 'form'])->name('form');
        Route::get('/edit', [ProfileController::class, 'form'])->name('edit');
        Route::post('/save', [ProfileController::class, 'save'])->name('save');
    });

    // ========================================
    // USER DONOR ROUTES
    // ========================================
    Route::prefix('donor')->name('donor.')->group(function () {
        Route::get('/', [DonorController::class, 'index'])->name('index');
        Route::post('/start', [DonorController::class, 'start'])->name('start');
        Route::post('/cancel', [DonorController::class, 'cancel'])->name('cancel');
        
        // Location selection
        Route::get('/location/{donor}', [DonorController::class, 'location'])->name('location');
        Route::post('/location/{donor}', [DonorController::class, 'saveLocation'])->name('location.save');
        
        // Health questions (multi-step)
        Route::get('/questions/{step}', [DonorController::class, 'questions'])
             ->name('questions')
             ->where('step', '[1-3]');
        Route::post('/questions/{step}', [DonorController::class, 'saveQuestions'])
             ->name('questions.save')
             ->where('step', '[1-3]');
        
        // Consent form
        Route::get('/consent', [DonorController::class, 'consent'])->name('consent');
        Route::post('/consent', [DonorController::class, 'saveConsent'])->name('consent.save');
        
        // Success and history
        Route::get('/success/{donor}', [DonorController::class, 'success'])->name('success');
        Route::get('/history', [DonorController::class, 'history'])->name('history');
        Route::get('/detail/{donor}', [DonorController::class, 'detail'])->name('detail');
        Route::get('/certificate/{donor}', [DonorController::class, 'certificate'])->name('certificate');
        
        // Additional donor routes
        Route::get('/schedule', [DonorController::class, 'schedule'])->name('schedule');
        Route::post('/reschedule/{donor}', [DonorController::class, 'reschedule'])->name('reschedule');
    });
    
    // ========================================
    // USER EVENT ROUTES
    // ========================================
    Route::prefix('my-events')->name('my-events.')->group(function () {
        Route::get('/', [EventController::class, 'myEvents'])->name('index');
        Route::post('/{event}/join', [EventController::class, 'joinEvent'])->name('join');
        Route::delete('/{event}/leave', [EventController::class, 'leaveEvent'])->name('leave');
    });
});

// ========================================
// ADMIN ROUTES (AUTH + ADMIN MIDDLEWARE)
// ========================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Admin Dashboard
    Route::get('/dashboard', [HomeController::class, 'adminDashboard'])->name('dashboard');
    
    // ========================================
    // USER MANAGEMENT
    // ========================================
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/{id}/show', [ProfileController::class, 'show_admin'])->name('show');
        Route::get('/{id}/edit', [ProfileController::class, 'edit_admin'])->name('edit');
        Route::put('/{id}', [ProfileController::class, 'update_admin'])->name('update');
        Route::delete('/{id}', [ProfileController::class, 'destroy'])->name('destroy');
        Route::get('/export', [ProfileController::class, 'export'])->name('export');
        
        // Bulk actions
        Route::post('/bulk-delete', [ProfileController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('/bulk-export', [ProfileController::class, 'bulkExport'])->name('bulk-export');
    });
    
    Route::prefix('donors')->name('donors.')->group(function () {
        Route::get('/', [DonorController::class, 'adminIndex'])->name('index');
        Route::get('/export', [DonorController::class, 'adminExport'])->name('export');
        Route::get('/statistics', [DonorController::class, 'adminStatistics'])->name('statistics');
        Route::get('/{donor}', [DonorController::class, 'adminShow'])->name('show');
        Route::get('/{donor}/edit', [DonorController::class, 'adminEdit'])->name('edit');
        Route::put('/{donor}', [DonorController::class, 'adminUpdate'])->name('update');
        Route::put('/{donor}/status', [DonorController::class, 'adminUpdateStatus'])->name('updateStatus');
        Route::post('/{donor}/complete', [DonorController::class, 'adminComplete'])->name('complete');
        Route::post('/{donor}/approve', [DonorController::class, 'adminApprove'])->name('approve');
        Route::post('/{donor}/reject', [DonorController::class, 'adminReject'])->name('reject');
        Route::delete('/{donor}', [DonorController::class, 'adminDestroy'])->name('destroy');
        
        // Bulk actions for donors
        Route::post('/bulk-approve', [DonorController::class, 'bulkApprove'])->name('bulk-approve');
        Route::post('/bulk-reject', [DonorController::class, 'bulkReject'])->name('bulk-reject');
    });

    Route::prefix('events')->name('events.')->group(function () {
        Route::get('/', [AdminEventController::class, 'index'])->name('index');
        Route::get('/create', [AdminEventController::class, 'create'])->name('create');
        Route::post('/', [AdminEventController::class, 'store'])->name('store');
        Route::get('/{event}', [AdminEventController::class, 'show'])->name('show');
        Route::get('/{event}/edit', [AdminEventController::class, 'edit'])->name('edit');
        Route::put('/{event}', [AdminEventController::class, 'update'])->name('update');
        Route::delete('/{event}', [AdminEventController::class, 'destroy'])->name('destroy');
        
        // Event status management
        Route::post('/{event}/approve', [AdminEventController::class, 'approve'])->name('approve');
        Route::post('/{event}/reject', [AdminEventController::class, 'reject'])->name('reject');
        Route::post('/{event}/publish', [AdminEventController::class, 'publish'])->name('publish');
        Route::post('/{event}/unpublish', [AdminEventController::class, 'unpublish'])->name('unpublish');
        
        // Event participants
        Route::get('/{event}/participants', [AdminEventController::class, 'participants'])->name('participants');
        Route::post('/{event}/participants/export', [AdminEventController::class, 'exportParticipants'])->name('participants.export');
    });
    
    Route::prefix('lokasis')->name('lokasis.')->group(function () {
        Route::get('/', [LokasiController::class, 'index'])->name('index');
        Route::get('/create', [LokasiController::class, 'create'])->name('create');
        Route::post('/', [LokasiController::class, 'store'])->name('store');
        Route::get('/{lokasi}', [LokasiController::class, 'show'])->name('show');
        Route::get('/{lokasi}/edit', [LokasiController::class, 'edit'])->name('edit');
        Route::put('/{lokasi}', [LokasiController::class, 'update'])->name('update');
        Route::delete('/{lokasi}', [LokasiController::class, 'destroy'])->name('destroy');
        
        // Lokasi status management
        Route::post('/{lokasi}/activate', [LokasiController::class, 'activate'])->name('activate');
        Route::post('/{lokasi}/deactivate', [LokasiController::class, 'deactivate'])->name('deactivate');
        
        // Lokasi statistics
        Route::get('/{lokasi}/statistics', [LokasiController::class, 'statistics'])->name('statistics');
        
        // Bulk operations
        Route::post('/bulk-status', [LokasiController::class, 'bulkStatus'])->name('bulk-status');
        Route::delete('/bulk-delete', [LokasiController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('/{lokasi}/toggle-status', [LokasiController::class, 'toggleStatus'])->name('toggle-status');
    });
    
    Route::prefix('articles')->name('articles.')->group(function () {
        Route::get('/', [ArticleController::class, 'adminIndex'])->name('index');
        Route::get('/create', [ArticleController::class, 'create'])->name('create');
        Route::post('/', [ArticleController::class, 'store'])->name('store');
        Route::get('/{article}', [ArticleController::class, 'adminShow'])->name('show');
        Route::get('/{article}/edit', [ArticleController::class, 'edit'])->name('edit');
        Route::put('/{article}', [ArticleController::class, 'update'])->name('update');
        Route::delete('/{article}', [ArticleController::class, 'destroy'])->name('destroy');
        
        // Article status management
        Route::patch('/{article}/toggle-status', [ArticleController::class, 'toggleStatus'])->name('toggle-status');
        Route::patch('/{article}/toggle-featured', [ArticleController::class, 'toggleFeatured'])->name('toggle-featured');
        Route::post('/{article}/publish', [ArticleController::class, 'publish'])->name('publish');
        Route::post('/{article}/unpublish', [ArticleController::class, 'unpublish'])->name('unpublish');
        
        // Bulk actions for articles
        Route::post('/bulk-publish', [ArticleController::class, 'bulkPublish'])->name('bulk-publish');
        Route::post('/bulk-unpublish', [ArticleController::class, 'bulkUnpublish'])->name('bulk-unpublish');
        Route::post('/bulk-delete', [ArticleController::class, 'bulkDelete'])->name('bulk-delete');
    });
    
    Route::prefix('system')->name('system.')->group(function () {
        // Settings
        Route::get('/settings', function() {
            return view('admin.system.settings');
        })->name('settings');
        
        // Logs
        Route::get('/logs', function() {
            return view('admin.system.logs');
        })->name('logs');
        
        // Backup
        Route::get('/backup', function() {
            return view('admin.system.backup');
        })->name('backup');
        Route::post('/backup/create', function() {
            // Backup logic here
            return redirect()->back()->with('success', 'Backup berhasil dibuat');
        })->name('backup.create');
        
        // Cache management
        Route::post('/cache/clear', function() {
            \Artisan::call('cache:clear');
            \Artisan::call('config:clear');
            \Artisan::call('view:clear');
            \Artisan::call('route:clear');
            
            return redirect()->back()->with('success', 'Cache berhasil dibersihkan');
        })->name('cache.clear');
    });

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', function() {
            return view('admin.reports.index');
        })->name('index');
        
        Route::get('/users', [ProfileController::class, 'userReport'])->name('users');
        Route::get('/donors', [DonorController::class, 'donorReport'])->name('donors');
        Route::get('/events', [AdminEventController::class, 'eventReport'])->name('events');
        Route::get('/locations', [LokasiController::class, 'locationReport'])->name('locations');
        
        // Export reports
        Route::post('/export/users', [ProfileController::class, 'exportUserReport'])->name('export.users');
        Route::post('/export/donors', [DonorController::class, 'exportDonorReport'])->name('export.donors');
        Route::post('/export/events', [AdminEventController::class, 'exportEventReport'])->name('export.events');
    });
});

Route::prefix('ajax')->name('ajax.')->middleware('auth')->group(function () {
    // Profile related
    Route::get('/profile/{id}', [ProfileController::class, 'getProfile'])->name('profile.get');
    
    // Location related
    Route::get('/provinces', [DependentDropdownController::class, 'provinces'])->name('provinces');
    Route::get('/cities/{province}', [DependentDropdownController::class, 'cities'])->name('cities');
    Route::get('/districts/{city}', [DependentDropdownController::class, 'districts'])->name('districts');
    Route::get('/villages/{district}', [DependentDropdownController::class, 'villages'])->name('villages');
    
    // Search
    Route::get('/search/users', [ProfileController::class, 'searchUsers'])->name('search.users');
    Route::get('/search/donors', [DonorController::class, 'searchDonors'])->name('search.donors');
    Route::get('/search/events', [EventController::class, 'searchEvents'])->name('search.events');
});
