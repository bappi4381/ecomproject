<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\Auth\LoginController as AdminLogin;
use App\Http\Controllers\Admin\Auth\ProfileController;
use App\Http\Controllers\Admin\Dashboard\dashboardController;
use App\Http\Controllers\Admin\users\UserController;

// User routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Admin auth routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminLogin::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminLogin::class, 'login'])->name('admin.login.submit');
    

    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [dashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('/profile', [ProfileController::class, 'index'])->name('admin.profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
        Route::post('/logout', [AdminLogin::class, 'logout'])->name('admin.logout');
    });
});