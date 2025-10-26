
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CourseController; // <-- TAMBAHKAN INI

// Rute Landing Page (sudah ada)
Route::get('/views', [PageController::class, 'landing'])->name('landing');

// --- RUTE BARU UNTUK KURSUS ---
// URL: /courses -> Menjalankan CourseController method index
Route::get('/courses', [CourseController::class, 'index'])->name('courses.course');

// URL: /courses/{sesuatu} -> Menjalankan CourseController method show
// {slug} adalah parameter dinamis dari URL, akan dikirim ke method show()
Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');
// -----------------------------