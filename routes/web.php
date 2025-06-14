<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DependentDropdownController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\BotManController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home route
Route::get('/', function () {
    return view('welcome');
});

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
    Route::get('/questions/{step}', [DonorController::class, 'questions'])
         ->name('questions')
         ->where('step', '[1-3]');
    Route::post('/questions/{step}', [DonorController::class, 'saveQuestions'])
         ->name('questions.save')
         ->where('step', '[1-3]');
    
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
        Route::put('/{donor}/status', [DonorController::class, 'adminUpdateStatus'])->name('status');
        Route::post('/{donor}/complete', [DonorController::class, 'adminComplete'])->name('complete');
    });
});
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

// Ganti route botman yang lama dengan ini:
Route::post('/botman', [BotManController::class, 'handle']);
