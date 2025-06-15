<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DependentDropdownController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\BotManController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminEventController;
use App\Http\Controllers\LokasiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home route
Route::get('/', function () {
    return view('welcome');
});

// Public Article routes (bisa diakses semua orang)
Route::get('/article', [ArticleController::class, 'index'])->name('article.index');
Route::get('/article/{slug}', [ArticleController::class, 'show'])->name('article.show');

// Public Contact route
Route::get('/contact', function() {
    return view('informasi.contact');
})->name('contact');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// Guest routes (belum login)
Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [AuthController::class,'register'])->name('register');
    Route::post('/register', [AuthController::class,'registerPost'])->name('register');
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginPost'])->name('login');
});

// Authenticated routes (sudah login)
Route::group(['middleware'=> 'auth'], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/form', [ProfileController::class, 'form'])->name('profile.form');
    Route::post('/profile/save', [ProfileController::class, 'save'])->name('profile.save');
});

// Admin - Manajemen Semua Data User (Profile)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/data-user', [ProfileController::class, 'index'])->name('profiles.index');
    Route::delete('/data-user/{id}', [ProfileController::class, 'destroy'])->name('profiles.destroy');
});



Route::get('/provinces', [DependentDropdownController::class, 'provinces'])->name('provinces');
Route::get('/cities', [DependentDropdownController::class, 'cities'])->name('cities');
Route::get('/districts', [DependentDropdownController::class, 'districts'])->name('districts');
Route::get('/villages', [DependentDropdownController::class, 'villages'])->name('villages');

Route::middleware('auth')->prefix('donor')->name('donor.')->group(function () {
    
    // Main donor flow
    Route::get('/', [DonorController::class, 'index'])->name('index');
    Route::post('/start', [DonorController::class, 'start'])->name('start');
    Route::post('/cancel', [DonorController::class, 'cancel'])->name('cancel');
    
    // Location
    Route::get('/location/{donor}', [DonorController::class, 'location'])->name('location');
    Route::post('/location/{donor}', [DonorController::class, 'saveLocation'])->name('location.save');
    
    // Health questions (3 steps)
    Route::get('/questions/{step}', [DonorController::class, 'questions'])->name('questions')->where('step', '[1-3]');
    Route::post('/questions/{step}', [DonorController::class, 'saveQuestions'])->name('questions.save')->where('step', '[1-3]');
    
    // Informed consent (only for eligible donors)
    Route::get('/consent', [DonorController::class, 'consent'])->name('consent');
    Route::post('/consent', [DonorController::class, 'saveConsent'])->name('consent.save');
    
    // Success page (for all results)
    Route::get('/success/{donor}', [DonorController::class, 'success'])->name('success');
    
    // Donor history and details
    Route::get('/history', [DonorController::class, 'history'])->name('history');
    Route::get('/detail/{donor}', [DonorController::class, 'detail'])->name('detail');
    Route::get('/certificate/{donor}', [DonorController::class, 'certificate'])->name('certificate');
});



Route::prefix('informasi')->group(function () {
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
});


Route::get('/botman/iframe', function() {
    return view('botman.iframe');
});

Route::post('/botman', [BotManController::class, 'handle']);


Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function() {
        return view('admin.admin');
    })->name('dashboard');
    
    // Donor Management
    Route::prefix('donors')->name('donors.')->group(function () {
        Route::get('/', [DonorController::class, 'adminIndex'])->name('index');
        Route::get('/export/data', [DonorController::class, 'adminExport'])->name('export');
        Route::get('/{donor}', [DonorController::class, 'adminShow'])->name('show');
        Route::put('/{donor}/status', [DonorController::class, 'adminUpdateStatus'])->name('status');
        Route::post('/{donor}/complete', [DonorController::class, 'adminComplete'])->name('complete');
    });
    
    // Events Management
    Route::resource('events', AdminEventController::class);
    Route::post('events/{event}/approve', [AdminEventController::class, 'approve'])->name('events.approve');
    Route::post('events/{event}/reject', [AdminEventController::class, 'reject'])->name('events.reject');
    
    // Lokasi Management
    Route::prefix('lokasis')->name('lokasis.')->group(function () {
        Route::get('/', [LokasiController::class, 'index'])->name('index');
        Route::get('/create', [LokasiController::class, 'create'])->name('create');
        Route::post('/', [LokasiController::class, 'store'])->name('store');
        Route::get('/{lokasi}/edit', [LokasiController::class, 'edit'])->name('edit');
        Route::put('/{lokasi}', [LokasiController::class, 'update'])->name('update');
        Route::delete('/{lokasi}', [LokasiController::class, 'destroy'])->name('destroy');
    });
    
    // Articles Management
    Route::prefix('articles')->name('articles.')->group(function () {
        Route::get('/', [ArticleController::class, 'adminIndex'])->name('index');
        Route::get('/create', [ArticleController::class, 'create'])->name('create');
        Route::post('/', [ArticleController::class, 'store'])->name('store');
        Route::get('/{article}', [ArticleController::class, 'adminShow'])->name('show');
        Route::get('/{article}/edit', [ArticleController::class, 'edit'])->name('edit');
        Route::put('/{article}', [ArticleController::class, 'update'])->name('update');
        Route::delete('/{article}', [ArticleController::class, 'destroy'])->name('destroy');
        Route::patch('/{article}/toggle-status', [ArticleController::class, 'toggleStatus'])->name('toggle-status');
        Route::patch('/{article}/toggle-featured', [ArticleController::class, 'toggleFeatured'])->name('toggle-featured');
    });
});