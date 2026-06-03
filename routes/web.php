<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\PPDBController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\PublicPageController;

use App\Http\Controllers\DashboardController;

Route::get('/', [PublicPageController::class, 'index']);
Route::get('/profil', [PublicPageController::class, 'profil'])->name('profil');
Route::get('/galeri', [PublicPageController::class, 'galeri'])->name('galeri');
Route::get('/media', [PublicPageController::class, 'media'])->name('media');
Route::get('/informasi', [PublicPageController::class, 'informasi'])->name('informasi');
Route::get('/kontak', [PublicPageController::class, 'kontak'])->name('kontak');
Route::get('/sambutan', [PublicPageController::class, 'sambutan'])->name('sambutan');
Route::get('/login', function() { return view('pages.login'); })->name('login');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

// Auth Routes
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Google Auth
    Route::get('/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('/google/callback', [AuthController::class, 'handleGoogleCallback']);
});

// Admin Only Routes
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/users', [AdminController::class, 'usersPage'])->name('admin.users');
    Route::get('/articles', [AdminController::class, 'articlesPage'])->name('admin.articles');
    Route::get('/gallery', [AdminController::class, 'galleryPage'])->name('admin.gallery');
    Route::get('/agenda', [AdminController::class, 'agendaPage'])->name('admin.agenda');
    Route::get('/ppdb-manage', [AdminController::class, 'ppdbPage'])->name('admin.ppdb');
    Route::post('/settings/update', [AdminController::class, 'updateSettings']);
    
    Route::get('/stats', [AdminController::class, 'stats']);
    Route::get('/users/data', [AdminController::class, 'users']);
    Route::post('/users', [AdminController::class, 'storeUser']);
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser']);
    Route::delete('/ppdb/{id}', [PPDBController::class, 'destroy']);
    Route::put('/users/{id}/role', [AdminController::class, 'updateRole']);
    Route::put('/users/{id}', [AdminController::class, 'updateUser']);
    Route::post('/guru', [AdminController::class, 'addGuru']);
    Route::post('/ppdb/{id}/convert', [AdminController::class, 'convertToStudent']);
});

// Admin & Guru Shared Routes
Route::prefix('cms')->middleware(['auth', 'role:admin,guru'])->group(function () {
    // Gallery
    Route::post('/gallery/upload', [GalleryController::class, 'upload']);
    Route::put('/gallery/{id}', [GalleryController::class, 'update']);
    Route::delete('/gallery/{id}', [GalleryController::class, 'delete']);
    Route::post('/gallery/{id}/file', [GalleryController::class, 'upload']);

    // Articles
    Route::post('/articles', [ArticleController::class, 'store']);
    Route::put('/articles/{id}', [ArticleController::class, 'update']);
    Route::delete('/articles/{id}', [ArticleController::class, 'destroy']);

    // Information
    Route::post('/information', [InformationController::class, 'store']);
    Route::put('/information/{id}', [InformationController::class, 'update']);
    Route::delete('/information/{id}', [InformationController::class, 'destroy']);

    // Agenda
    Route::post('/agenda/create', [AgendaController::class, 'store']);
    Route::put('/agenda/{id}', [AgendaController::class, 'update']);
    Route::delete('/agenda/{id}', [AgendaController::class, 'destroy']);
});

// Legacy Admin Ping Placeholder (Moved to shared for convenience)
Route::get('/admin/ping', function() { return response()->json(['status' => 'ok']); })->middleware('auth');

// Public Data Routes
Route::get('/gallery/data', [GalleryController::class, 'index']);
Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{slug}', [ArticleController::class, 'show']);
Route::get('/information', [InformationController::class, 'index']);
Route::get('/information/{slug}', [InformationController::class, 'show']);
Route::get('/agenda/data', [AgendaController::class, 'index']);

// PPDB Routes
Route::get('/ppdb', [PPDBController::class, 'create'])->name('ppdb');
Route::post('/ppdb/submit', [PPDBController::class, 'submit']);
Route::get('/ppdb/data', [PPDBController::class, 'index']);
Route::put('/ppdb/update', [PPDBController::class, 'update']);

// Legacy Fallback
Route::get('/{page}.html', function($page) {
    if (view()->exists('pages.' . $page)) {
        return view('pages.' . $page);
    }
    abort(404);
});
