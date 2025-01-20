<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CreateSaleController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'LoginForm'])->name('loginForm');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get("/register", [AuthController::class, "registerForm"]);
    Route::post("/register", [AuthController::class, "register"])->name("register");

    Route::prefix('password')->group(function () {
        Route::get('/send-otp', [PasswordController::class, 'showSendOtpForm'])->name('password.send-otp.form');
        Route::post('/send-otp', [PasswordController::class, 'sendOtp'])->name('password.send-otp');
        Route::get('/verify-otp', [PasswordController::class, 'showVerifyOtpForm'])->name('password.verify-otp.form');
        Route::post('/verify-otp', [PasswordController::class, 'verifyOtp'])->name('password.verify-otp');
        Route::get('/reset', [PasswordController::class, 'showPasswrodResetForm'])->name('password.reset.form');
        Route::post('/reset', [PasswordController::class, 'passwordReset'])->name('password.reset');
    });

});

Route::middleware('auth')->group(function () {
    Route::get("/", [DashboardController::class, 'index'])->name('dashboard');
    Route::get("/customer", [CustomerController::class, 'index']);
    Route::post("/customer", [CustomerController::class, 'store'])->name('customer');
    Route::put("/customer", [CustomerController::class, 'update'])->name('customer.update');
    Route::delete("/customer", [CustomerController::class, 'delete'])->name('customer.delete');
    Route::get("/category", [CategoryController::class, 'index']);
    Route::get("/category-list", [CategoryController::class, 'getcategory'])->name('categorylist');
    Route::post("/category", [CategoryController::class, 'store'])->name('category');
    Route::get("/products", [ProductController::class, 'index']);
    Route::post("/products", [ProductController::class, 'store'])->name('products');
    Route::get("/sale", [CreateSaleController::class, 'index']);
    Route::get('searchcustomer/{query}', [CreateSaleController::class, 'searchCustomer'])->name('searchcustomer');
    Route::post("/sale", [CreateSaleController::class, 'sellProducts'])->name('sellProducts');
    Route::get("/invoice", [InvoiceController::class, 'index'])->name('invoice');
    Route::get("/invoice_info/{invoice_id}", [InvoiceController::class, 'invoice_info'])->name('invoiceinfo');
    Route::post("/logout", [AuthController::class, 'logout'])->name('logout');

});
