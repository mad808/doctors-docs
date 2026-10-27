<?php

use App\Http\Controllers\Front\ClinicController;
use App\Http\Controllers\Front\DoctorController;
use App\Http\Controllers\Front\DoctorPortalController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\PatientPortalController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

Route::match(['get', 'post'], '/language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'tk', 'ru'])) {
        session()->put('locale', $locale);
        cookie()->queue(cookie()->forever('locale', $locale));
    }
    return redirect()->back();
})->name('locale.switch');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('doctors')->name('doctors.')->group(function () {
    Route::get('/', [DoctorController::class, 'index'])->name('index');
    Route::get('/{doctor}/booked-slots', [DoctorController::class, 'getBookedSlots'])->name('booked-slots');
    Route::get('/{doctor}', [DoctorController::class, 'show'])->name('show');
});

Route::prefix('clinics')->name('clinics.')->group(function () {
    Route::get('/', [ClinicController::class, 'index'])->name('index');
    Route::get('/{clinic}', [ClinicController::class, 'show'])->name('show');
});

Route::middleware('auth')->group(function () {
    // Patient Portal
    Route::prefix('patient')->name('patient.portal.')->group(function () {
        Route::get('/dashboard', [PatientPortalController::class, 'dashboard'])->name('dashboard');
        Route::post('/appointments', [PatientPortalController::class, 'storeAppointment'])->name('appointments.store');
        Route::post('/reviews', [PatientPortalController::class, 'storeReview'])->name('reviews.store');
    });

    // Doctor Portal
    Route::prefix('doctor')->name('doctor.portal.')->group(function () {
        Route::get('/dashboard', [DoctorPortalController::class, 'dashboard'])->name('dashboard');
        Route::patch('/appointments/{appointment}', [DoctorPortalController::class, 'updateAppointment'])->name('appointments.update');
    });
});