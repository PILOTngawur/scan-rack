<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RakApiController;
use App\Http\Controllers\Api\SlotApiController;

// ─── Public ──────────────────────────────────────────────────────────────────
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/master-students/by-nis/{nis}', [AuthController::class, 'masterStudentByNis']);
Route::get('/classes', [RakApiController::class, 'classes']);

// ─── Protected ───────────────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::patch('/me/device-name', [AuthController::class, 'updateDeviceName']);

    // Rak
    Route::get('/rak', [RakApiController::class, 'index']);
    Route::get('/rak/{qrCode}/scan', [RakApiController::class, 'scanQr']);

    // Slot (simpan/ambil handphone)
    Route::get('/slot/me', [SlotApiController::class, 'mySlot']);
    Route::post('/slot/checkin', [SlotApiController::class, 'checkIn']);
    Route::post('/slot/checkout', [SlotApiController::class, 'checkOut']);
});
