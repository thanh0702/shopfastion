<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Middleware\EmployeeMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/test-mongo', function () {
    try {
        $client = new MongoDB\Client(env('DB_URI'));
        return 'Connected to MongoDB successfully!';
    } catch (Exception $e) {
        return $e->getMessage();
    }
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/category/{slug}', [HomeController::class, 'showCategory'])->name('category.show');
Route::get('/product/{slug}', [HomeController::class, 'showProduct'])->name('product.show');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::middleware('auth')->group(function () {
    Route::post('/cart/add', [App\Http\Controllers\HomeController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [App\Http\Controllers\HomeController::class, 'showCart'])->name('cart.show');
    Route::post('/cart/update', [App\Http\Controllers\HomeController::class, 'updateCart'])->name('cart.update');
    Route::post('/cart/delete', [App\Http\Controllers\HomeController::class, 'deleteCartItem'])->name('cart.delete');
    Route::post('/wishlist/toggle', [App\Http\Controllers\HomeController::class, 'toggleWishlist'])->name('wishlist.toggle');
    Route::get('/wishlist', [App\Http\Controllers\HomeController::class, 'showWishlist'])->name('wishlist.show');
    Route::get('/account', [App\Http\Controllers\HomeController::class, 'account'])->name('account');
    Route::get('/account/details', [App\Http\Controllers\HomeController::class, 'editAccount'])->name('account.details');
    Route::post('/account/details', [App\Http\Controllers\HomeController::class, 'updateAccount'])->name('account.details.update');
    Route::get('/account/addresses', [App\Http\Controllers\HomeController::class, 'addresses'])->name('account.addresses');
    Route::get('/account/addresses/create', [App\Http\Controllers\HomeController::class, 'createAddress'])->name('account.addresses.create');
    Route::post('/account/addresses', [App\Http\Controllers\HomeController::class, 'storeAddress'])->name('account.addresses.store');
    Route::get('/account/addresses/{address}/edit', [App\Http\Controllers\HomeController::class, 'editAddress'])->name('account.addresses.edit');
    Route::put('/account/addresses/{address}', [App\Http\Controllers\HomeController::class, 'updateAddress'])->name('account.addresses.update');
    Route::delete('/account/addresses/{address}', [App\Http\Controllers\HomeController::class, 'deleteAddress'])->name('account.addresses.delete');
    Route::get('/account/orders', [App\Http\Controllers\HomeController::class, 'orders'])->name('account.orders');
    Route::get('/account/orders/{order}', [App\Http\Controllers\HomeController::class, 'orderDetails'])->name('account.order.details');
    Route::delete('/account/orders/{order}', [App\Http\Controllers\HomeController::class, 'cancelOrder'])->name('account.order.cancel');
    Route::get('/checkout', [App\Http\Controllers\HomeController::class, 'checkout'])->name('checkout');
    Route::post('/payment', [App\Http\Controllers\HomeController::class, 'processPayment'])->name('payment.process');
    Route::post('/chat/save', [App\Http\Controllers\HomeController::class, 'saveChatMessage'])->name('chat.save');
    Route::post('/account/orders/{order}/receipt', [App\Http\Controllers\HomeController::class, 'saveReceipt'])->name('account.order.saveReceipt');

    Route::middleware([EmployeeMiddleware::class])->group(function () {
        Route::get('/employee/sales', [EmployeeController::class, 'salesPage'])->name('employee.sales');

        Route::get('/employee/cart', [EmployeeController::class, 'cartPage'])->name('employee.cart');
        Route::post('/employee/cart/add', [EmployeeController::class, 'addToCart'])->name('employee.cart.add');
        Route::get('/employee/cart/count', [EmployeeController::class, 'getCartCount'])->name('employee.cart.count');
        Route::post('/employee/cart/update', [EmployeeController::class, 'updateCartItem'])->name('employee.cart.update');
        Route::post('/employee/cart/delete', [EmployeeController::class, 'deleteCartItem'])->name('employee.cart.delete');
        
        Route::post('/employee/payment', [EmployeeController::class, 'processEmployeePayment'])->name('employee.payment.process');
        Route::get('/employee/orders', [EmployeeController::class, 'ordersList'])->name('employee.orders');
        Route::get('/employee/orders/{order}', [EmployeeController::class, 'orderDetails'])->name('employee.order.details');
        Route::put('/employee/orders/{order}/complete', [EmployeeController::class, 'completeOrder'])->name('employee.order.complete');
        Route::put('/employee/orders/{order}/cancel', [EmployeeController::class, 'cancelOrder'])->name('employee.order.cancel');
        Route::get('/employee/all-orders', [EmployeeController::class, 'allOrders'])->name('employee.all-orders');
        Route::get('/employee/orders/show/{order}', [EmployeeController::class, 'showOrder'])->name('employee.orders.show');
    });
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Employee user management
    Route::get('/admin/employees', [App\Http\Controllers\AdminEmployeeController::class, 'index'])->name('admin.employees.index');
    Route::get('/admin/employees/create', [App\Http\Controllers\AdminEmployeeController::class, 'create'])->name('admin.employees.create');
    Route::post('/admin/employees', [App\Http\Controllers\AdminEmployeeController::class, 'store'])->name('admin.employees.store');

    // Category management
    Route::get('/admin/categories', [App\Http\Controllers\AdminController::class, 'indexCategories'])->name('admin.categories.index');
    Route::get('/admin/categories/create', [App\Http\Controllers\AdminController::class, 'createCategory'])->name('admin.categories.create');
    Route::post('/admin/categories', [App\Http\Controllers\AdminController::class, 'storeCategory'])->name('admin.categories.store');

    // Admin user management
    Route::get('/admin/admins', [AdminUserController::class, 'index'])->name('admin.admins.index');
    Route::get('/admin/admins/create', [AdminUserController::class, 'create'])->name('admin.admins.create');
    Route::post('/admin/admins', [AdminUserController::class, 'store'])->name('admin.admins.store');
    Route::delete('/admin/admins/{admin}', [AdminUserController::class, 'destroy'])->name('admin.admins.destroy');

    Route::get('/admin/categories/{category}/edit', [App\Http\Controllers\AdminController::class, 'editCategory'])->name('admin.categories.edit');
    Route::put('/admin/categories/{category}', [App\Http\Controllers\AdminController::class, 'updateCategory'])->name('admin.categories.update');
    Route::delete('/admin/categories/{category}', [App\Http\Controllers\AdminController::class, 'deleteCategory'])->name('admin.categories.delete');

    // Product management
    Route::get('/admin/products', [App\Http\Controllers\AdminController::class, 'indexProducts'])->name('admin.products.index');
    Route::get('/admin/products/create', [App\Http\Controllers\AdminController::class, 'createProduct'])->name('admin.products.create');
    Route::post('/admin/products', [App\Http\Controllers\AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::get('/admin/products/{product}/edit', [App\Http\Controllers\AdminController::class, 'editProduct'])->name('admin.products.edit');
    Route::put('/admin/products/{product}', [App\Http\Controllers\AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::delete('/admin/products/{product}', [App\Http\Controllers\AdminController::class, 'deleteProduct'])->name('admin.products.delete');

    // Banner management
    Route::get('/admin/banners', [App\Http\Controllers\AdminController::class, 'indexBanners'])->name('admin.banners.index');
    Route::get('/admin/banners/create', [App\Http\Controllers\AdminController::class, 'createBanner'])->name('admin.banners.create');
    Route::post('/admin/banners', [App\Http\Controllers\AdminController::class, 'storeBanner'])->name('admin.banners.store');
    Route::get('/admin/banners/{banner}/edit', [App\Http\Controllers\AdminController::class, 'editBanner'])->name('admin.banners.edit');
    Route::put('/admin/banners/{banner}', [App\Http\Controllers\AdminController::class, 'updateBanner'])->name('admin.banners.update');
    Route::delete('/admin/banners/{banner}', [App\Http\Controllers\AdminController::class, 'deleteBanner'])->name('admin.banners.delete');

    // QR Code management
    Route::get('/admin/qr_codes', [App\Http\Controllers\AdminController::class, 'indexQrCodes'])->name('admin.qr_codes.index');
    Route::get('/admin/qr_codes/create', [App\Http\Controllers\AdminController::class, 'createQrCode'])->name('admin.qr_codes.create');
    Route::post('/admin/qr_codes', [App\Http\Controllers\AdminController::class, 'storeQrCode'])->name('admin.qr_codes.store');
    Route::get('/admin/qr_codes/{qrCode}/edit', [App\Http\Controllers\AdminController::class, 'editQrCode'])->name('admin.qr_codes.edit');
    Route::put('/admin/qr_codes/{qrCode}', [App\Http\Controllers\AdminController::class, 'updateQrCode'])->name('admin.qr_codes.update');
    Route::delete('/admin/qr_codes/{qrCode}', [App\Http\Controllers\AdminController::class, 'deleteQrCode'])->name('admin.qr_codes.delete');

    // Order management
    Route::get('/admin/orders', [App\Http\Controllers\AdminController::class, 'indexOrders'])->name('admin.orders.index');
    Route::get('/admin/orders/{order}', [App\Http\Controllers\AdminController::class, 'showOrder'])->name('admin.orders.show');
    Route::put('/admin/orders/{order}', [App\Http\Controllers\AdminController::class, 'updateOrder'])->name('admin.orders.update');

    // Chat Message management
    Route::get('/admin/chat_messages', [App\Http\Controllers\AdminController::class, 'indexChatMessages'])->name('admin.chat_messages.index');
    Route::get('/admin/chat_messages/{chatMessage}/edit', [App\Http\Controllers\AdminController::class, 'editChatMessage'])->name('admin.chat_messages.edit');
});
