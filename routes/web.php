<?php

use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\SyncController;
use App\Http\Controllers\CookieConsentController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

// Главная и каталог
Route::get('/', [ProductController::class, 'index'])->name('home');

// Замени существующий маршрут catalog на:
Route::get('/catalog', [ProductController::class, 'catalog'])->name('catalog');
Route::get('/catalog/category/{slug}', [ProductController::class, 'category'])->name('catalog.category');
Route::get('/catalog/filter', [ProductController::class, 'filter'])->name('catalog.filter');
Route::get('/catalog/categories', [ProductController::class, 'allCategories'])->name('catalog.categories');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product');

// Аутентификация
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Корзина (только авторизованные)
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/add-ajax/{product}', [CartController::class, 'addAjax'])->name('cart.addAjax');
    Route::post('/cart/buy-now/{product}', [CartController::class, 'buyNow'])->name('cart.buyNow');
    Route::put('/cart/update/{item}/{quantity}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{item}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Заказы
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/order/success/{order}', [OrderController::class, 'success'])->name('order.success');
    Route::get('/orders', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/order/{order}', [OrderController::class, 'show'])->name('order.show');
});

// Админка
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/products/no-category', [CategoryController::class, 'noCategory'])->name('admin.no-category');
    Route::get('/products/with-category', [CategoryController::class, 'withCategory'])->name('admin.with-category');
    Route::post('/products/{id}/category', [CategoryController::class, 'updateCategory'])->name('admin.update-category');
    Route::post('/products/bulk-update', [CategoryController::class, 'bulkUpdate'])->name('admin.bulk-update');
    Route::get('/sync', [SyncController::class, 'sync'])->name('admin.sync');
});

// Личный кабинет
Route::middleware('auth')->prefix('profile')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('products', ProductController::class);

    // Маршруты для изображений
    Route::prefix('products/{product}/images')->name('products.images.')->group(function () {
        Route::get('/', [ProductImageController::class, 'index'])->name('index');
        Route::post('/', [ProductImageController::class, 'store'])->name('store');
        Route::put('/{image}/set-main', [ProductImageController::class, 'setMain'])->name('set-main');
        Route::delete('/{image}', [ProductImageController::class, 'destroy'])->name('destroy');
    });
});

Route::get('/admin/products/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
Route::put('/admin/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');

Route::middleware(['auth'])->group(function () {
    Route::get('/product/{product}/reviews', [ReviewController::class, 'show'])->name('product.reviews');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');});

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::patch('/reviews/{review}/status', [AdminReviewController::class, 'updateStatus'])->name('reviews.update-status');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/my-reviews', [ReviewController::class, 'myReviews'])->name('my.reviews');
});



Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/delivery', [PageController::class, 'delivery'])->name('delivery');
Route::get('/return', [PageController::class, 'return'])->name('return');
Route::get('/contacts', [PageController::class, 'contacts'])->name('contacts');
Route::post('/contact/send', [PageController::class, 'sendContact'])->name('contact.send');


Route::post('/cookie-consent', [CookieConsentController::class, 'consent'])->name('cookie.consent');

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy.policy');



