<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserCollectionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - FlipPlay REST API (Laravel 12)
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. BAGIAN DEMO MODUL 4: FLIPPLAY REST API
// ==========================================

// CRUD Lengkap untuk Katalog Media FlipPlay (Anime & Manga)
// - GET    /api/media          (Index + Search ?title=... & Filter ?type=anime/manga)
// - POST   /api/media          (Store Media Baru)
// - GET    /api/media/{medium} (Detail Media + Users yang Mengoleksi)
// - PUT    /api/media/{medium} (Update Keseluruhan Media)
// - PATCH  /api/media/{medium} (Update Sebagian Media)
// - DELETE /api/media/{medium} (Hapus Media)
Route::apiResource('media', MediaController::class);

// Endpoint Daftar Pengguna (User)
Route::get('users', [UserController::class, 'index']);
Route::get('users/{user}', [UserController::class, 'show']);

// Custom Actions: Manajemen Koleksi Pengguna (Attach & Detach Relasi Many-to-Many)
// - GET    /api/users/{user}/collections         -> Tampilkan seluruh media yang dikoleksi user
// - POST   /api/users/{user}/collections         -> Tambah media ke koleksi (Attach relasi)
// - DELETE /api/users/{user}/collections/{medium}-> Hapus media dari koleksi (Detach relasi)
Route::get('users/{user}/collections', [UserCollectionController::class, 'index']);
Route::post('users/{user}/collections', [UserCollectionController::class, 'store']);
Route::delete('users/{user}/collections/{medium}', [UserCollectionController::class, 'destroy']);


// ==========================================
// 2. BAGIAN CODELAB MODUL 4 (Perpustakaan)
// ==========================================
Route::apiResource('books', BookController::class);
Route::apiResource('members', MemberController::class);
Route::post('members/{member}/borrow-book', [MemberController::class, 'borrowBook']);
Route::patch('members/{member}/return-book/{book}', [MemberController::class, 'returnBook']);
