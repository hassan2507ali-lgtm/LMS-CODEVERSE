<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // <-- Tambahkan ini untuk menerima request

class CourseController extends Controller
{
    // Data statis DENGAN KATEGORI
    private function getStaticCourses()
    {
        return [
            [
                'id' => 1, 'title' => 'Dasar-Dasar Laravel 11', 'slug' => 'dasar-dasar-laravel-11',
                'description' => 'Pelajari fundamental framework Laravel...',
                'full_description' => 'Dalam kursus ini...',
                'thumbnail' => 'https://placehold.co/600x400/6366f1/ffffff?text=Laravel+11',
                'price' => 0, 'is_free' => true, 'category' => 'Programming', // <-- Kategori
                'modules' => [['title' => 'Modul 1: Instalasi & Setup'], ['title' => 'Modul 2: Routing & Controller'], ['title' => 'Modul 3: Blade Template']]
            ],
            [
                'id' => 2, 'title' => 'Vue JS 3 untuk Pemula', 'slug' => 'vue-js-3-untuk-pemula',
                'description' => 'Kuasai Vue JS, framework JavaScript progresif...',
                'full_description' => 'Kursus ini dirancang...',
                'thumbnail' => 'https://placehold.co/600x400/41b883/ffffff?text=Vue+JS+3',
                'price' => 250000, 'is_free' => false, 'category' => 'Programming', // <-- Kategori
                'modules' => [['title' => 'Modul 1: Pengenalan Vue JS'], ['title' => 'Modul 2: Vue Components'], ['title' => 'Modul 3: State Management']]
            ],
            [
                'id' => 3, 'title' => 'Tailwind CSS dari A sampai Z', 'slug' => 'tailwind-css-dari-a-sampai-z',
                'description' => 'Membangun desain web yang responsif...',
                'full_description' => 'Lupakan penulisan CSS manual...',
                'thumbnail' => 'https://placehold.co/600x400/38bdf8/ffffff?text=Tailwind+CSS',
                'price' => 150000, 'is_free' => false, 'category' => 'UI/UX Design', // <-- Kategori
                'modules' => [['title' => 'Modul 1: Setup & Konfigurasi'], ['title' => 'Modul 2: Utility Classes'], ['title' => 'Modul 3: Desain Responsif']]
            ],
             [ // Kursus Tambahan untuk Kategori Lain
                'id' => 4, 'title' => 'Analisis Data dengan Python', 'slug' => 'analisis-data-dengan-python',
                'description' => 'Pelajari Pandas, NumPy, dan Matplotlib...',
                'full_description' => 'Kuasai alat-alat penting untuk analisis data...',
                'thumbnail' => 'https://placehold.co/600x400/f59e0b/ffffff?text=Data+Science',
                'price' => 300000, 'is_free' => false, 'category' => 'Data Science & Analytics', // <-- Kategori
                'modules' => [['title' => 'Modul 1: Pengenalan Pandas'], ['title' => 'Modul 2: Manipulasi Data'], ['title' => 'Modul 3: Visualisasi Data']]
            ],
        ];
    }

    // Daftar kategori statis (Nanti ini bisa dari database)
    private function getStaticCategories()
    {
        return ['Programming', 'Data Science & Analytics', 'UI/UX Design', 'Lainnya'];
    }

    /**
     * Method index: Menampilkan daftar kursus DENGAN FILTER KATEGORI.
     */
    public function index(Request $request) // <-- Tambahkan Request $request
    {
        $allCourses = $this->getStaticCourses();
        $allCategories = $this->getStaticCategories();

        // 1. Ambil nama kategori dari URL (query string ?category=...)
        $requestedCategory = $request->query('category'); // Misal: 'Programming' atau null

        // 2. Filter kursus berdasarkan kategori yang diminta
        if ($requestedCategory && in_array($requestedCategory, $allCategories)) {
            // Jika ada kategori yang valid diminta, filter kursus
            $courses = collect($allCourses)->where('category', $requestedCategory)->all();
            $activeCategory = $requestedCategory; // Tandai kategori ini sebagai aktif
        } else {
            // Jika tidak ada kategori diminta atau kategorinya tidak valid, tampilkan semua
            $courses = $allCourses;
            $activeCategory = 'Semua'; // Tandai 'Semua' sebagai aktif
        }

        // 3. Siapkan daftar kategori untuk ditampilkan di tab (termasuk 'Semua')
        $displayCategories = array_merge(['Semua'], $allCategories);

        // 4. Kirim data ke view
        return view('courses.course', compact('courses', 'displayCategories', 'activeCategory'));
        // Kita kirim: kursus yang sudah difilter, daftar kategori untuk tab, dan kategori yang aktif
    }

    /**
     * Method show: Menampilkan detail satu kursus (Tidak berubah)
     */
    public function show($slug)
    {
        $allCourses = $this->getStaticCourses();
        $course = collect($allCourses)->firstWhere('slug', $slug);

        if (!$course) {
            abort(404);
        }

        return view('courses.show', compact('course'));
    }
}