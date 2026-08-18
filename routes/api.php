<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\IotAttendanceController;

Route::prefix('v1/device')->group(function () {
    Route::get('/status', [IotAttendanceController::class, 'checkActiveSession']);
    Route::post('/attendances', [IotAttendanceController::class, 'submitBatchAttendances']);
});
