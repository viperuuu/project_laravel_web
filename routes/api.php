<?php

use App\Http\Controllers\MediaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserCollectionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - FlipPlay REST API (Laravel 12)
|--------------------------------------------------------------------------
| Khusus Tugas Demo Modul 4: Lanjutan Pengembangan FlipPlay (Anime & Manga)
*/

// =========================================================================
// 1. KATALOG MEDIA FLIPPLAY (CRUD STANDAR - MediaController)
// =========================================================================
Route::apiResource('media', MediaController::class);

// =========================================================================
// 2. DATA PENGGUNA FLIPPLAY (UserController)
// =========================================================================
Route::get('users', [UserController::class, 'index']);
Route::get('users/{user}', [UserController::class, 'show']);

// =========================================================================
// 3. KOLEKSI PENGGUNA (CUSTOM ACTIONS ATTACH / DETACH - UserCollectionController)
// =========================================================================
Route::get('users/{user}/collections', [UserCollectionController::class, 'index']);
Route::post('users/{user}/collections', [UserCollectionController::class, 'store']);
Route::delete('users/{user}/collections/{medium}', [UserCollectionController::class, 'destroy']);
