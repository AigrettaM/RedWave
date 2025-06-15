<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DependentDropdownController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LokasiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [AuthController::class,'register'])->name('register');
    Route::post('/register', [AuthController::class,'registerPost'])->name('register');
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginPost'])->name('login');
});

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

// Route untuk lokasi (admin)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('lokasis', LokasiController::class);
});

// Atau jika ingin lebih spesifik:
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/lokasis', [LokasiController::class, 'index'])->name('lokasis.index');
    Route::get('/lokasis/create', [LokasiController::class, 'create'])->name('lokasis.create');
    Route::post('/lokasis', [LokasiController::class, 'store'])->name('lokasis.store');
    Route::get('/lokasis/{lokasi}/edit', [LokasiController::class, 'edit'])->name('lokasis.edit');
    Route::put('/lokasis/{lokasi}', [LokasiController::class, 'update'])->name('lokasis.update');
    Route::delete('/lokasis/{lokasi}', [LokasiController::class, 'destroy'])->name('lokasis.destroy');
});