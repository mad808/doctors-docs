<?php

use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ClinicController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SpecialtyController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('locations', LocationController::class)->except(['show']);
Route::resource('specialties', SpecialtyController::class)->except(['show']);
Route::resource('clinics', ClinicController::class)->except(['show']);
Route::get('doctors/{doctor}/schedule', [DoctorController::class, 'schedule'])->name('doctors.schedule');
Route::resource('doctors', DoctorController::class)->except(['show']);
Route::resource('patients', PatientController::class)->except(['show']);
Route::resource('banners', BannerController::class)->except(['show']);

Route::prefix('reviews')->name('reviews.')->group(function () {
    Route::get('/', [ReviewController::class, 'index'])->name('index');
    Route::get('/{review}', [ReviewController::class, 'show'])->name('show');
    Route::patch('/{review}/status', [ReviewController::class, 'updateStatus'])->name('update-status');
    Route::post('/{review}/reply', [ReviewController::class, 'reply'])->name('reply');
    Route::delete('/{review}', [ReviewController::class, 'destroy'])->name('destroy');
});

Route::prefix('appointments')->name('appointments.')->group(function () {
    Route::get('/', [AppointmentController::class, 'index'])->name('index');
    Route::patch('/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('update-status');
    Route::delete('/{appointment}', [AppointmentController::class, 'destroy'])->name('destroy');
});

Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [SettingController::class, 'index'])->name('index');
    Route::put('/', [SettingController::class, 'update'])->name('update');
});