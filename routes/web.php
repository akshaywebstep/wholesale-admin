<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Admin\OrderController;

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ShopController;
use App\Http\Controllers\Frontend\CustomerAuthController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\CustomerRegisterController;

// Direct root test route
Route::get('/test-direct', function () {
    return 'Direct test route working!';
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    // Guest Admin
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    // Authenticated Admin
    Route::middleware('auth:web')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('permission:Dashboard,VIEW');

        // ===== USERS =====
        Route::get('users', [UserController::class, 'index'])->name('users.index')->middleware('permission:User,VIEW');
        Route::get('users/create', [UserController::class, 'create'])->name('users.create')->middleware('permission:User,CREATE');
        Route::post('users', [UserController::class, 'store'])->name('users.store')->middleware('permission:User,CREATE');
        Route::get('users/{user}', [UserController::class, 'show'])->name('users.show')->middleware('permission:User,VIEW');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('permission:User,UPDATE');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('permission:User,UPDATE');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:User,DELETE');
        Route::post('users/{user}/approve', [UserController::class, 'approve'])->name('users.approve')->middleware('permission:User,UPDATE');
        Route::post('users/{user}/reject', [UserController::class, 'reject'])->name('users.reject')->middleware('permission:User,UPDATE');
        Route::get('ajax/states/{country}', [UserController::class, 'getStates'])->name('ajax.states');
        Route::get('ajax/cities/{state}', [UserController::class, 'getCities'])->name('ajax.cities');

        // ===== ROLES =====
        Route::get('roles', [RoleController::class, 'index'])->name('roles.index')->middleware('permission:Role,VIEW');
        Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create')->middleware('permission:Role,CREATE');
        Route::post('roles', [RoleController::class, 'store'])->name('roles.store')->middleware('permission:Role,CREATE');
        Route::get('roles/{role}', [RoleController::class, 'show'])->name('roles.show')->middleware('permission:Role,VIEW');
        Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit')->middleware('permission:Role,UPDATE');
        Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update')->middleware('permission:Role,UPDATE');
        Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy')->middleware('permission:Role,DELETE');
        Route::get('roles/{role}/permissions', [RoleController::class, 'permissions'])->name('roles.permissions')->middleware('permission:Role,UPDATE');
        Route::post('roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions.update')->middleware('permission:Role,UPDATE');

        // ===== CATEGORIES =====
        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index')->middleware('permission:Category,VIEW');
        Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create')->middleware('permission:Category,CREATE');
        Route::post('categories', [CategoryController::class, 'store'])->name('categories.store')->middleware('permission:Category,CREATE');
        Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show')->middleware('permission:Category,VIEW');
        Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit')->middleware('permission:Category,UPDATE');
        Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update')->middleware('permission:Category,UPDATE');
        Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy')->middleware('permission:Category,DELETE');

        // ===== PRODUCTS =====
        Route::get('products', [ProductController::class, 'index'])->name('products.index')->middleware('permission:Product,VIEW');
        Route::get('products/create', [ProductController::class, 'create'])->name('products.create')->middleware('permission:Product,CREATE');
        Route::post('products', [ProductController::class, 'store'])->name('products.store')->middleware('permission:Product,CREATE');
        Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show')->middleware('permission:Product,VIEW');
        Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit')->middleware('permission:Product,UPDATE');
        Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update')->middleware('permission:Product,UPDATE');
        Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy')->middleware('permission:Product,DELETE');

        // ===== PRODUCT SUB-RESOURCES (Nested under Product) =====
        Route::post('products/{product}/variants', [ProductController::class, 'storeVariant'])->name('products.variants.store')->middleware('permission:Product,CREATE');
        Route::delete('product-variants-item/{variant}', [ProductController::class, 'destroyVariant'])->name('products.variants.destroy')->middleware('permission:Product,DELETE');

        Route::post('products/{product}/images', [ProductController::class, 'storeImage'])->name('products.images.store')->middleware('permission:Product,CREATE');
        Route::delete('product-images/{image}', [ProductController::class, 'destroyImage'])->name('products.images.destroy')->middleware('permission:Product,DELETE');

        Route::post('products/{product}/price-tiers', [ProductController::class, 'storePriceTier'])->name('products.price-tiers.store')->middleware('permission:Product,CREATE');
        Route::delete('product-price-tiers/{priceTier}', [ProductController::class, 'destroyPriceTier'])->name('products.price-tiers.destroy')->middleware('permission:Product,DELETE');

        Route::post('products/{product}/stock', [ProductController::class, 'updateStock'])->name('products.stock.update')->middleware('permission:Product,UPDATE');

        // ===== WAREHOUSES =====
        Route::get('warehouses', [WarehouseController::class, 'index'])->name('warehouses.index')->middleware('permission:Warehouse,VIEW');
        Route::get('warehouses/create', [WarehouseController::class, 'create'])->name('warehouses.create')->middleware('permission:Warehouse,CREATE');
        Route::post('warehouses', [WarehouseController::class, 'store'])->name('warehouses.store')->middleware('permission:Warehouse,CREATE');
        Route::get('warehouses/{warehouse}', [WarehouseController::class, 'show'])->name('warehouses.show')->middleware('permission:Warehouse,VIEW');
        Route::get('warehouses/{warehouse}/edit', [WarehouseController::class, 'edit'])->name('warehouses.edit')->middleware('permission:Warehouse,UPDATE');
        Route::put('warehouses/{warehouse}', [WarehouseController::class, 'update'])->name('warehouses.update')->middleware('permission:Warehouse,UPDATE');
        Route::delete('warehouses/{warehouse}', [WarehouseController::class, 'destroy'])->name('warehouses.destroy')->middleware('permission:Warehouse,DELETE');

        // ===== ORDERS =====
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index')->middleware('permission:Order,VIEW');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show')->middleware('permission:Order,VIEW');
        Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus')->middleware('permission:Order,UPDATE');
        Route::get('orders/{order}/download-invoice', [OrderController::class, 'downloadInvoice'])->name('orders.downloadInvoice')->middleware('permission:Order,VIEW');
    });
});

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/category/{category}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/product/{id}', [ShopController::class, 'show'])->name('shop.product');
Route::get('/product/{id}/quick-view', [ShopController::class, 'quickView'])->name('shop.product.quickView');

// Customer Auth Routes
Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [CustomerAuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout')->middleware('auth:customer');

Route::get('/register', [CustomerRegisterController::class, 'showForm'])->name('register');
Route::post('/register', [CustomerRegisterController::class, 'store'])->name('register.store');
Route::get('/get-states/{country_id}', [CustomerRegisterController::class, 'getStates'])->name('get.states');
Route::get('/get-cities/{state_id}', [CustomerRegisterController::class, 'getCities'])->name('get.cities');

// Cart Routes
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::post('/remove', [CartController::class, 'remove'])->name('remove');
    Route::get('/count', [CartController::class, 'count'])->name('count');
});

// Checkout Routes
Route::prefix('checkout')->name('checkout.')->middleware('auth:customer')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/process', [CheckoutController::class, 'process'])->name('process');
    Route::get('/success/{id}', [CheckoutController::class, 'success'])->name('success');
});

// Customer Order History
Route::middleware('auth:customer')->prefix('my-orders')->name('customer.orders.')->group(function () {
    Route::get('/', [ShopController::class, 'orders'])->name('index');
    Route::get('/{id}', [ShopController::class, 'orderDetails'])->name('show');
    Route::get('/{id}/download-invoice', [ShopController::class, 'downloadInvoice'])->name('downloadInvoice');
});