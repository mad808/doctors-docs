<?php

use App\Http\Controllers\Api\OtpGatewayController;
use Illuminate\Support\Facades\Route;

Route::prefix('otp-gateway')->group(function () {
    Route::get('pending', [OtpGatewayController::class, 'getPending']);
    Route::post('report', [OtpGatewayController::class, 'reportStatus']);
    Route::post('test', [OtpGatewayController::class, 'createTestMessage']);
});