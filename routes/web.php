<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PracticeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sinilah Anda dapat mendaftarkan rute web untuk aplikasi Anda.
|
*/

// --- Rute Publik (Bisa diakses siapa saja) ---
// (Ini rute-rute yang sudah kita buat sebelumnya)
Route::get('/', [PageController::class, 'landing'])->name('landing');
Route::get('/courses', [CourseController::class, 'index'])->name('courses.course');
Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/practice', [PracticeController::class, 'index'])->name('practice.index');
Route::get('/practice/{slug}', [PracticeController::class, 'show'])->name('practice.show');


// --- Rute Autentikasi (Bawaan Breeze) ---
// Rute ini penting, jangan dihapus. Ini menangani /login, /register, dll.
require __DIR__.'/auth.php';


// --- Rute yang Dilindungi (Hanya bisa diakses setelah Login) ---
// Contoh: Halaman Dashboard bawaan Breeze
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Nanti kita akan tambahkan rute /admin di sini
// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
// });