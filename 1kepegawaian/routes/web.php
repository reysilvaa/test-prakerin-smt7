<?php


use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PegawaiDashboardController;
use App\Http\Controllers\AdminDashboardController;
use Illuminate\Support\Facades\Route;



Auth::routes();

// Redirect default /home route berdasarkan role
Route::get('/', function() {
    if (auth()->check()) {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('pegawai.dashboard');
        }
    }
    return redirect('/login');
})->name('home');

// Routes dengan autentikasi
Route::middleware(['auth'])->group(function () {
    // Admin routes
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('departemens', DepartemenController::class);
        Route::resource('pegawais', PegawaiController::class);
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    });
    
    // Pegawai routes
    Route::middleware(['role:pegawai'])->group(function () {
        Route::get('/dashboard', [PegawaiDashboardController::class, 'index'])->name('pegawai.dashboard');
    });
});
Auth::routes();

Route::get('/home', action: [App\Http\Controllers\HomeController::class, 'index'])->name('home');

