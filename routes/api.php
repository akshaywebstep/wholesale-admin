<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AddressController;

// Public Endpoints (Guest Website Users)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);

// Protected Endpoints (Logged-in Users with Sanctum Token)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/verify-token', [AuthController::class, 'verifyToken']);
    Route::get('/user-profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Cart APIs
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::delete('/cart/item/{id}', [CartController::class, 'removeCartItem']);
    Route::delete('/cart/clear', [CartController::class, 'clearCart']);

    // Order Apis
    Route::get('/orders', [OrderController::class, 'index']);      
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/checkout', [OrderController::class, 'checkout']);
    Route::get('/addresses', [AddressController::class, 'index']);
    Route::post('/addresses/add', [AddressController::class, 'store']);
    // Invoice Download API Route
    Route::get('/orders/{id}/download-invoice', [OrderController::class, 'downloadInvoice']);
});