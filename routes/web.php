
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PracticeController;

// Rute Landing Page (sudah ada)
Route::get('/views', [PageController::class, 'landing'])->name('landing');

// --- RUTE BARU UNTUK KURSUS ---
Route::get('/courses', [CourseController::class, 'index'])->name('courses.course');
Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');

// --- RUTE BARU UNTUK KURSUS ---
Route::get('/practice', [PracticeController::class, 'index'])->name('practice.index');
Route::get('/practice/{slug}', [PracticeController::class, 'show'])->name('practice.show');