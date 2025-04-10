<?php

use App\Http\Controllers\PoliController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentication Route
Route::middleware(['auth', 'json'])->prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->withoutMiddleware('auth');
    Route::delete('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
});

Route::prefix('setting')->group(function () {
    Route::get('', [SettingController::class, 'index']);
});

// Route::middleware(['auth', 'verified', 'json'])->group(function () {
    Route::prefix('setting')->middleware('can:setting')->group(function () {
        Route::post('', [SettingController::class, 'update']);
    });

    Route::prefix('master')->group(function () {
        Route::middleware('can:master-user')->group(function () {
            Route::get('users', [UserController::class, 'get']);
            Route::post('users', [UserController::class, 'index']);
            Route::post('users/store', [UserController::class, 'store']);
            Route::apiResource('users', UserController::class)
                ->except(['index', 'store'])->scoped(['user' => 'uuid']);
        });

        Route::middleware('can:master-role')->group(function () {
            Route::get('roles', [RoleController::class, 'get'])->withoutMiddleware('can:master-role');
            Route::post('roles', [RoleController::class, 'index']);
            Route::post('roles/store', [RoleController::class, 'store']);
            Route::apiResource('roles', RoleController::class)
                ->except(['index', 'store']);
        });

        Route::middleware('can:poli')->group(function () {
            Route::get('poli', [PoliController::class, 'get'])->withoutMiddleware('can:poli');
            Route::post('poli', [PoliController::class, 'index']);
            Route::post('poli/store', [PoliController::class, 'store']);
            Route::apiResource('poli', PoliController::class)
                ->except(['index', 'store']);
        });
        
        Route::middleware('can:ruangan')->group(function () {
            Route::get('ruangan', [RuanganController::class, 'get'])->withoutMiddleware('can:ruangan');
            Route::post('ruangan', [RuanganController::class, 'index']);
            Route::post('ruangan/store', [RuanganController::class, 'store']);
            Route::apiResource('ruangan', RuanganController::class)
                ->except(['index', 'store']);
        });

        // Route::middleware('can:master-dokter')->group(function () {
            Route::get('dokter', [DokterController::class, 'get']);
            Route::post('dokter', [DokterController::class, 'index']);
            // Route::post('dokter/store', [DokterController::class, 'store']);
            Route::apiResource('dokter', DokterController::class)
                ->except(['index', 'store']);
        // });
    });
// });
