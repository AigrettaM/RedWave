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


Route::get('/article', [ArticleController::class, 'index'])->name('article.index');
Route::get('/article/{slug}', [ArticleController::class, 'show'])->name('article.show');


// Guest routes (belum login)
Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [AuthController::class,'register'])->name('register');
    Route::post('/register', [AuthController::class,'registerPost'])->name('register');
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginPost'])->name('login');
});

// Authenticated routes (sudah login)
Route::group(['middleware'=> 'auth'], function () {
    Route::get('/home', [HomeController::class, 'index']);
    Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/form', [ProfileController::class, 'form'])->name('profile.form');
    Route::post('/profile/save', [ProfileController::class, 'save'])->name('profile.save');
});

// Dependent dropdown routes
Route::get('/provinces', [DependentDropdownController::class, 'provinces'])->name('provinces');
Route::get('/cities', [DependentDropdownController::class, 'cities'])->name('cities');
Route::get('/districts', [DependentDropdownController::class, 'districts'])->name('districts');
Route::get('/villages', [DependentDropdownController::class, 'villages'])->name('villages');

Route::middleware('auth')->prefix('donor')->name('donor.')->group(function () {
    
    // Main donor flow
    Route::get('/', [DonorController::class, 'index'])->name('index');
    Route::post('/start', [DonorController::class, 'start'])->name('start');
    
    // Health questions (3 steps)
    Route::get('/questions/{step}', [DonorController::class, 'questions'])->name('questions')->where('step', '[1-3]');
    Route::post('/questions/{step}', [DonorController::class, 'saveQuestions'])->name('questions.save')->where('step', '[1-3]');
    
    // Informed consent (only for eligible donors)
    Route::get('/consent', [DonorController::class, 'consent'])->name('consent');
    Route::post('/consent', [DonorController::class, 'saveConsent'])->name('consent.save');
    
    // Success page (for all results)
    Route::get('/success/{donor}', [DonorController::class, 'success'])->name('success');
    
    // Cancel process
    Route::get('/cancel', [DonorController::class, 'cancel'])->name('cancel');
    
    // Donor history and details
    Route::get('/history', [DonorController::class, 'history'])->name('history');
    Route::get('/detail/{donor}', [DonorController::class, 'detail'])->name('detail');
    Route::get('/certificate/{donor}', [DonorController::class, 'certificate'])->name('certificate');
});

// Admin Donor Routes (optional)
Route::middleware(['auth', 'admin'])->prefix('admin/donors')->name('admin.donors.')->group(function () {
    Route::get('/', [DonorController::class, 'adminIndex'])->name('index');
    Route::get('/{donor}', [DonorController::class, 'adminShow'])->name('show');
    Route::put('/{donor}/status', [DonorController::class, 'adminUpdateStatus'])->name('status');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Donor Management
    Route::prefix('donors')->name('donors.')->group(function () {
        Route::get('/export/data', [DonorController::class, 'adminExport'])->name('export');
        Route::get('/', [DonorController::class, 'adminIndex'])->name('index');
        Route::get('/{donor}', [DonorController::class, 'adminShow'])->name('show');
        Route::put('/admin/donors/{donor}/status', [AdminDonorController::class, 'adminUpdateStatus'])->name('admin.donors.updateStatus');
        Route::post('/{donor}/complete', [DonorController::class, 'adminComplete'])->name('complete');
    });
});

// Admin - Manajemen Semua Data User (Profile)
Route::get('/data-user', [ProfileController::class, 'index'])->name('profiles.index');
Route::delete('/data-user/{id}', [ProfileController::class, 'destroy'])->name('profiles.destroy');

// Public routes (bisa diakses semua orang)
Route::get('/contact', function() {
    return view('informasi.contact');
})->name('contact');

// BotMan routes
Route::get('/botman/iframe', function() {
    return view('botman.iframe');
});

Route::post('/botman', function() {
    $botman = app('botman');
    
    // Di sini Anda bisa menambah/edit respons chatbot
    $botman->hears('kata_kunci', function ($botman) {
        $botman->reply('Respons Anda');
    });
    
    $botman->listen();
});

// Route botman
Route::post('/botman', [BotManController::class, 'handle']);

// Frontend Routes - event
Route::prefix('informasi')->group(function () {
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
});

// Admin Routes (with auth middleware)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('events', AdminEventController::class);
    Route::post('events/{event}/approve', [AdminEventController::class, 'approve'])->name('events.approve');
    Route::post('events/{event}/reject', [AdminEventController::class, 'reject'])->name('events.reject');
});

// Route untuk lokasi (admin)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('lokasis', LokasiController::class);
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/lokasis', [LokasiController::class, 'index'])->name('lokasis.index');
    Route::get('/lokasis/create', [LokasiController::class, 'create'])->name('lokasis.create');
    Route::post('/lokasis', [LokasiController::class, 'store'])->name('lokasis.store');
    Route::get('/lokasis/{lokasi}/edit', [LokasiController::class, 'edit'])->name('lokasis.edit');
    Route::put('/lokasis/{lokasi}', [LokasiController::class, 'update'])->name('lokasis.update');
    Route::delete('/lokasis/{lokasi}', [LokasiController::class, 'destroy'])->name('lokasis.destroy');
});