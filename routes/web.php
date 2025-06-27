<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Filament\Pages\DashboardAnalytics;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Halaman utama, menampilkan daftar post
Route::get('/', [PostController::class, 'index'])->name('posts.index');

// Route /dashboard menggunakan middleware auth & verified, return view dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Grup route yang memerlukan autentikasi
Route::middleware('auth')->group(function () {
    // Profile management routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Resource route untuk posts
    Route::resource('posts', PostController::class);

    // Routes untuk comments (CRUD yang spesifik)
    Route::post('comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

// Route toggle like, dengan middleware auth
Route::middleware('auth')->post('/likes/toggle', [LikeController::class, 'toggleLike'])->name('likes.toggle');

// Route untuk dashboard analytics, hanya untuk admin dengan middleware role:admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard-analytics', function () {
        // Render halaman DashboardAnalytics Filament Page secara langsung
        return app(DashboardAnalytics::class)->render();
    })->name('dashboard-analytics');
});

require __DIR__.'/auth.php';







