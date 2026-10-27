<?php

use App\Http\Controllers\Front\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/auth/send-otp', [AuthController::class, 'sendOtp'])->name('auth.send-otp');
    Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp'])->name('auth.verify-otp');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');