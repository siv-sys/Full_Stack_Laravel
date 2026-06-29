<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect("/admin/login");
});

// Admin auth
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login']);
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

/**
 * Admin routes group with prefix 'admin' and 'admin.' prefix for route names
 * All routes in this group require authentication and admin role middleware
 */
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Admin dashboard route
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Resource routes for categories (excluding show route)
    Route::resource('categories', CategoryController::class)->except('show');
    // Resource routes for products (excluding show route)
    Route::resource('products', ProductController::class)->except('show');



    // Order management routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index'); // Display all orders
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show'); // Display specific order
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus'); // Update order status



    // User management routes
    Route::resource('users', UserController::class); // Full resource for users
    Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole'); // Update user role
});
