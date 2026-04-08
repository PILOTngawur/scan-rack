<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RakController;
use App\Http\Controllers\Admin\UserController;

// ─── Auth ────────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ─── Admin ───────────────────────────────────────────────────────────────────
Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/', fn() => redirect()->route('admin.dashboard'));

        // Rak (Class/Rack management)
        Route::resource('rak', RakController::class);
        Route::post('rak/{rak}/generate-qr', [RakController::class, 'generateQr'])->name('rak.generate-qr');

        // User management
        Route::get('/user', [UserController::class, 'index'])->name('user.index');
        Route::delete('/user/admin/{user}', [UserController::class, 'destroyAdmin'])->name('user.destroy-admin');
        Route::delete('/user/student/{user}', [UserController::class, 'destroyStudent'])->name('user.destroy-student');
        Route::get('/user/admin/create', [UserController::class, 'createAdmin'])->name('user.create-admin');
        Route::post('/user/admin', [UserController::class, 'storeAdmin'])->name('user.store-admin');
        Route::get('/user/student/create', [UserController::class, 'createStudent'])->name('user.create-student');
        Route::post('/user/student', [UserController::class, 'storeStudent'])->name('user.store-student');
        Route::get('/user/nis/create', [UserController::class, 'createNis'])->name('user.create-nis');
        Route::post('/user/nis', [UserController::class, 'storeNis'])->name('user.store-nis');
        Route::delete('/user/nis/{masterStudent}', [UserController::class, 'destroyNis'])->name('user.destroy-nis');
    });

// Redirect root to login
Route::get('/', fn() => redirect()->route('login'));
