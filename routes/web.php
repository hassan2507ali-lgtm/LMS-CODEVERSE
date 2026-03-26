<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PracticeAdminController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MyCourseController; 

// --- Rute Pancingan (Taruh di luar agar bisa dites siapa saja) ---
Route::get('/tes-halaman', function () {
    return 'Halo! File web.php ini berhasil terbaca.';
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 1. Rute Publik (Bisa diakses siapa saja) ---
Route::get('/', [PageController::class, 'landing'])->name('landing');
Route::get('/courses', [CourseController::class, 'index'])->name('courses.course');
Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');
// Halaman Katalog Practice (Bisa dilihat publik agar tertarik)
Route::get('/practice', [PracticeController::class, 'index'])->name('practice.index');
// 🔥 RUTE BARU: Pintu Masuk Khusus Midtrans (TIDAK BOLEH DI-AUTH)
Route::post('/midtrans/callback', [\App\Http\Controllers\CallbackController::class, 'midtransCallback']);

// --- 2. Rute Autentikasi (Bawaan Breeze) ---
require __DIR__.'/auth.php';


// --- 3. Rute Pengguna Terdaftar (Wajib Login) ---
Route::middleware(['auth'])->group(function () {
    
    // === Rute Latihan / Practice (Wajib Login) ===
    // Halaman daftar soal (show)
    Route::get('/practice/{slug}', [PracticeController::class, 'show'])->name('practice.show');
    // Halaman ruang kerja coding (exercise)
    Route::get('/practice/{slug}/exercise/{exercise}', [PracticeController::class, 'startExercise'])->name('practice.exercise.start');
    
    // ROUTE BARU: Untuk menandai soal selesai secara diam-diam (AJAX)
    Route::post('/practice/exercise/{exercise}/complete', [PracticeController::class, 'markCompleted'])->name('practice.exercise.complete');

    // Rute untuk memproses pembelian/checkout kelas menggunakan Midtrans (atau simulasi)
    Route::post('/checkout/{course}', [CheckoutController::class, 'process'])->name('checkout.process');
    
    // 🔥 ROUTE BARU: Rute untuk memproses pembelian Practice (Freemium)
    Route::post('/checkout/practice/{practice}', [CheckoutController::class, 'processPractice'])->name('checkout.practice');
    
    // Rute Halaman Kelas Saya 
    Route::get('/my-courses', [MyCourseController::class, 'index'])->name('my-courses');

    // Rute Ruang Belajar (HARUS LOGIN & PUNYA KELAS)
    Route::get('/courses/{slug}/learn/{lesson}', [CourseController::class, 'learn'])->name('courses.learn');
});


// --- 4. Rute Panel Admin (Dilindungi 'auth', 'verified', dan 'admin') ---
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    
    // Rute Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    //  ROUTE BARU: Laporan Transaksi
    Route::get('/transactions', [AdminController::class, 'transactions'])->name('admin.transactions');
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