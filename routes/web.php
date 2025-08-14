<?php

use Illuminate\Support\Facades\Route;
use App\Models\Subcategory;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLogin;
use App\Http\Controllers\Admin\Auth\ProfileController;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\users\UserController;
use App\Http\Controllers\Admin\Product\CategoriesController;
use App\Http\Controllers\Admin\Product\SubcategoryController;
use App\Http\Controllers\Admin\Product\ProductController;
use App\Http\Controllers\Admin\Order\OrderController;

// User routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Admin auth routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminLogin::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminLogin::class, 'login'])->name('admin.login.submit');
    

    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        Route::get('category-subcategory', [CategoriesController::class, 'index'])->name('category_subcategory.index');
        Route::resource('categories', CategoriesController::class)->only(['store', 'destroy']);
        Route::resource('subcategories', SubcategoryController::class)->only(['store', 'destroy']);

        // Add route for getting subcategories (e.g., for AJAX dependent dropdowns)
        Route::get('get-subcategories', [SubcategoryController::class, 'getSubcategories'])->name('get.subcategories');



        Route::resource('products', ProductController::class);

        Route::resource('orders', OrderController::class);
        Route::patch('orders/{order}/status/{status}', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');


        

        Route::get('users', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('users/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('users', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

        Route::get('/profile', [ProfileController::class, 'index'])->name('admin.profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
        Route::post('/logout', [AdminLogin::class, 'logout'])->name('admin.logout');
    });

    


});