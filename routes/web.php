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
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Frontend\FrontendArticleController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\UserDashboardController;

// User routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/books', [HomeController::class, 'books'])->name('books.index');
Route::get('/books/{book}', [HomeController::class, 'show'])->name('books.show');

Route::get('/articles', [FrontendArticleController::class, 'index'])->name('frontend.articles');
Route::get('/articles/{slug}', [FrontendArticleController::class, 'show'])->name('frontend.articles.show');


Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');


Route::get('/checkout', [CheckoutController::class, 'checkoutIndex'])->name('checkout.index');
Route::post('/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');

Route::get('/orders/success/{order}', [CheckoutController::class, 'success'])->name('orders.success');

Route::get('/login', [AuthController::class, 'showAccountPage'])->name('user.auth.login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('user.auth.register');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');

Route::prefix('user')->middleware('user')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'userDashboard'])->name('user.dashboard');
    Route::get('/profile', [UserDashboardController::class, 'profileIndex'])->name('user.profile');
    Route::put('/profile', [UserDashboardController::class, 'updateProfile'])->name('profile.update');
    //order routes
    Route::get('/orders', [UserDashboardController::class, 'userOrders'])->name('user.orders.index');
    Route::get('/orders/{order}', [UserDashboardController::class, 'userOrderDetails'])->name('user.orders.details');
    Route::patch('/orders/{order}/cancel', [UserDashboardController::class, 'cancelOrder'])->name('user.orders.cancel');
    //track order
    Route::get('/track-order', [UserDashboardController::class, 'trackOrderForm'])->name('user.orders.track');
    Route::post('/track-order', [UserDashboardController::class, 'trackOrder'])->name('user.trackOrder.submit');
    //user.messages.index
    Route::get('/messages', [UserDashboardController::class, 'userMessages'])->name('user.messages.index');

    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

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
        Route::get('/categories/by-type/{type}', [CategoriesController::class, 'getByType'])->name('categories.byType');
        Route::get('get-subcategories', [SubcategoryController::class, 'getSubcategories'])->name('get.subcategories');
        
        Route::resource('products', ProductController::class);

        Route::resource('orders', OrderController::class);
        Route::patch('orders/{order}/status/{status}', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::get('/orders/{id}/generate-invoice', [OrderController::class, 'generateInvoice'])->name('admin.orders.invoice.generate');
        Route::get('/orders/{id}/download-invoice', [OrderController::class, 'downloadInvoice'])->name('admin.orders.invoice.download');

        Route::resource('/articles', ArticleController::class);
        

        Route::resource('users', UserController::class);
        Route::get('users/{user}/orders', [UserController::class, 'orders'])->name('users.orders');

        Route::get('/profile', [ProfileController::class, 'index'])->name('admin.profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
        Route::post('/logout', [AdminLogin::class, 'logout'])->name('admin.logout');
    });

    


});