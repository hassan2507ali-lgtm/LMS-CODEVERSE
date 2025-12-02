<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PracticeAdminController; // <-- Pastikan ini di-impor

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 1. Rute Publik (Bisa diakses siapa saja) ---
Route::get('/', [PageController::class, 'landing'])->name('landing');
Route::get('/courses', [CourseController::class, 'index'])->name('courses.course');
Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/practice', [PracticeController::class, 'index'])->name('practice.index');
Route::get('/practice/{slug}', [PracticeController::class, 'show'])->name('practice.show');
Route::get('/practice/{slug}/exercise/{exercise}', [PracticeController::class, 'startExercise'])->name('practice.exercise.start');


// --- 2. Rute Autentikasi (Bawaan Breeze) ---
require __DIR__.'/auth.php';


// --- 3. Rute Panel Admin (Dilindungi 'auth', 'verified', dan 'admin') ---
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    
    // Rute Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // === Grup Rute CRUD Kursus ===
    Route::prefix('admin/courses')->name('admin.courses.')->group(function () {
        Route::get('/create', [AdminController::class, 'create'])->name('create');
        Route::post('/', [AdminController::class, 'store'])->name('store');
        Route::get('/{course}/edit', [AdminController::class, 'edit'])->name('edit');
        Route::put('/{course}', [AdminController::class, 'update'])->name('update');
        Route::delete('/{course}', [AdminController::class, 'destroy'])->name('destroy');
        Route::get('/{course}/content', [AdminController::class, 'manageContent'])->name('content');
    });

    // === Grup Rute CRUD Modul ===
    Route::prefix('admin/modules')->name('admin.modules.')->group(function () {
        Route::post('/{course}', [AdminController::class, 'storeModule'])->name('store');
        Route::get('/{module}/edit', [AdminController::class, 'editModule'])->name('edit');
        Route::put('/{module}', [AdminController::class, 'updateModule'])->name('update');
        Route::delete('/{module}', [AdminController::class, 'destroyModule'])->name('destroy');
    });

    // === Grup Rute CRUD Lesson ===
    Route::prefix('admin/lessons')->name('admin.lessons.')->group(function () {
        Route::post('/{module}', [AdminController::class, 'storeLesson'])->name('store');
        Route::get('/{lesson}/edit', [AdminController::class, 'editLesson'])->name('edit');
        Route::put('/{lesson}', [AdminController::class, 'updateLesson'])->name('update');
        Route::delete('/{lesson}', [AdminController::class, 'destroyLesson'])->name('destroy');
    });

    // === Grup Rute CRUD Practice ===
    Route::prefix('admin/practice')->name('admin.practice.')->group(function () {
        Route::get('/', [PracticeAdminController::class, 'index'])->name('index');
        Route::get('/create', [PracticeAdminController::class, 'create'])->name('create');
        Route::post('/', [PracticeAdminController::class, 'store'])->name('store');
        Route::get('/{practice}/edit', [PracticeAdminController::class, 'edit'])->name('edit');
        Route::put('/{practice}', [PracticeAdminController::class, 'update'])->name('update');
        Route::delete('/{practice}', [PracticeAdminController::class, 'destroy'])->name('destroy');
        
        // Manage Exercises
        Route::get('/{practice}/exercises', [PracticeAdminController::class, 'manageExercises'])->name('exercises.manage');
        Route::post('/{practice}/exercises', [PracticeAdminController::class, 'storeExercise'])->name('exercises.store');
        Route::get('/{practice}/exercises/{exercise}/edit', [PracticeAdminController::class, 'editExercise'])->name('exercises.edit');
        Route::put('/{practice}/exercises/{exercise}', [PracticeAdminController::class, 'updateExercise'])->name('exercises.update');
        Route::delete('/{practice}/exercises/{exercise}', [PracticeAdminController::class, 'destroyExercise'])->name('exercises.destroy');
    });

});