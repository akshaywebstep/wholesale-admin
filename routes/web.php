<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\OrderController;

// Direct root test route
Route::get('/test-direct', function () {
    return 'Direct test route working!';
});
Route::prefix('admin')->name('admin.')->group(function () {

    // Guest routes (login)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    });

    // Authenticated routes
    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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

        // ===== PRODUCT VARIANTS =====
        Route::get('product-variants', [ProductVariantController::class, 'index'])->name('productVariants.index')->middleware('permission:Product,VIEW');
        Route::get('product-variants/create', [ProductVariantController::class, 'create'])->name('productVariants.create')->middleware('permission:Product,CREATE');
        Route::post('product-variants', [ProductVariantController::class, 'store'])->name('productVariants.store')->middleware('permission:Product,CREATE');
        Route::get('product-variants/{product_variant}', [ProductVariantController::class, 'show'])->name('productVariants.show')->middleware('permission:Product,VIEW');
        Route::get('product-variants/{product_variant}/edit', [ProductVariantController::class, 'edit'])->name('productVariants.edit')->middleware('permission:Product,UPDATE');
        Route::put('product-variants/{product_variant}', [ProductVariantController::class, 'update'])->name('productVariants.update')->middleware('permission:Product,UPDATE');
        Route::delete('product-variants/{product_variant}', [ProductVariantController::class, 'destroy'])->name('productVariants.destroy')->middleware('permission:Product,DELETE');


        Route::post('products/{product}/variants', [ProductController::class, 'storeVariant'])->name('products.variants.store')->middleware('permission:Product,CREATE');
        Route::delete('product-variants-item/{variant}', [ProductController::class, 'destroyVariant'])->name('products.variants.destroy')->middleware('permission:Product,DELETE');

        Route::post('products/{product}/images', [ProductController::class, 'storeImage'])->name('products.images.store')->middleware('permission:Product,CREATE');
        Route::delete('product-images/{image}', [ProductController::class, 'destroyImage'])->name('products.images.destroy')->middleware('permission:Product,DELETE');

        Route::post('products/{product}/price-tiers', [ProductController::class, 'storePriceTier'])->name('products.price-tiers.store')->middleware('permission:Product,CREATE');
        Route::delete('product-price-tiers/{priceTier}', [ProductController::class, 'destroyPriceTier'])->name('products.price-tiers.destroy')->middleware('permission:Product,DELETE');

        // ===== WAREHOUSES =====
        Route::get('warehouses', [WarehouseController::class, 'index'])->name('warehouses.index')->middleware('permission:Warehouse,VIEW');
        Route::get('warehouses/create', [WarehouseController::class, 'create'])->name('warehouses.create')->middleware('permission:Warehouse,CREATE');
        Route::post('warehouses', [WarehouseController::class, 'store'])->name('warehouses.store')->middleware('permission:Warehouse,CREATE');
        Route::get('warehouses/{warehouse}', [WarehouseController::class, 'show'])->name('warehouses.show')->middleware('permission:Warehouse,VIEW');
        Route::get('warehouses/{warehouse}/edit', [WarehouseController::class, 'edit'])->name('warehouses.edit')->middleware('permission:Warehouse,UPDATE');
        Route::put('warehouses/{warehouse}', [WarehouseController::class, 'update'])->name('warehouses.update')->middleware('permission:Warehouse,UPDATE');
        Route::delete('warehouses/{warehouse}', [WarehouseController::class, 'destroy'])->name('warehouses.destroy')->middleware('permission:Warehouse,DELETE');

        // ===== STOCK =====
        Route::get('stock', [StockController::class, 'index'])->name('stock.index')->middleware('permission:Stock,VIEW');
        Route::get('stock/create', [StockController::class, 'create'])->name('stock.create')->middleware('permission:Stock,CREATE');
        Route::post('stock', [StockController::class, 'store'])->name('stock.store')->middleware('permission:Stock,CREATE');
        Route::get('stock/{stock}', [StockController::class, 'show'])->name('stock.show')->middleware('permission:Stock,VIEW');
        Route::get('stock/{stock}/edit', [StockController::class, 'edit'])->name('stock.edit')->middleware('permission:Stock,UPDATE');
        Route::put('stock/{stock}', [StockController::class, 'update'])->name('stock.update')->middleware('permission:Stock,UPDATE');
        Route::delete('stock/{stock}', [StockController::class, 'destroy'])->name('stock.destroy')->middleware('permission:Stock,DELETE');

        // ===== ORDERS (Admin Read & Status Management Only) =====
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index')->middleware('permission:Order,VIEW');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show')->middleware('permission:Order,VIEW');
        Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus')->middleware('permission:Order,UPDATE');
        Route::get('orders/{order}/download-invoice', [OrderController::class, 'downloadInvoice'])->name('orders.downloadInvoice')->middleware('permission:Order,VIEW');
    });
});
