<?php

namespace App\Http\Controllers; // Pastikan namespace benar

use App\Models\Course; // Impor Model Course
use Illuminate\Http\Request;
// use App\Http\Controllers\Controller; // Controller dasar biasanya otomatis dikenali

class CourseController extends Controller // Pastikan extends Controller
{
    // Daftar kategori statis (Sementara)
    private function getStaticCategories()
    {
        return ['Programming', 'Data Science & Analytics', 'UI/UX Design', 'Lainnya'];
        // return Course::select('category')->whereNotNull('category')->distinct()->pluck('category')->sort()->values()->toArray();
    }

    /**
     * Method index: Menampilkan daftar kursus DARI DATABASE dengan filter.
     */
    public function index(Request $request)
    {
        $allCategories = $this->getStaticCategories();
        $requestedCategory = $request->query('category');
        $query = Course::query();
        $activeCategory = 'Semua';

        if ($requestedCategory && in_array($requestedCategory, $allCategories)) {
             $activeCategory = $requestedCategory;
             // Nanti tambahkan: $query->where('category', $requestedCategory);
        }

        $courses = $query->latest()->paginate(12);
        $displayCategories = array_merge(['Semua'], $allCategories);

        // Mengarah ke view 'course' sesuai nama file Anda
        return view('courses.course', compact('courses', 'displayCategories', 'activeCategory'));
    }

    /**
     * Method show: Menampilkan detail satu kursus DARI DATABASE berdasarkan slug.
     * --- PERUBAHAN DI METHOD INI ---
     */
    public function show($slug)
    {
        // Cari kursus berdasarkan slug, DAN sertakan relasi 'modules' yang sudah diurutkan
        // Kita menggunakan 'with' untuk Eager Loading
        $course = Course::where('slug', $slug)
                        ->with('modules') // Meminta Laravel untuk mengambil modul terkait
                        ->firstOrFail(); // Otomatis 404 jika tidak ditemukan

        // Variabel $course sekarang sudah berisi informasi kursus DAN daftar modulnya ($course->modules)

        // Kirim data kursus (yang sudah termasuk modul) ke view
        return view('courses.show', compact('course'));
    }
} // <-- Pastikan kurung kurawal tutup ini ada