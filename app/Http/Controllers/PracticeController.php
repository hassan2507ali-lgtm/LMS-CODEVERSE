<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str; // Helper untuk manipulasi string (opsional)

class PracticeController extends Controller
{
    // Data statis untuk proyek latihan
    private function getStaticPractices()
    {
        return [
            [
                'id' => 1,
                'title' => 'Create a GIF with Python',
                'slug' => 'create-a-gif-with-python',
                'thumbnail' => 'https://placehold.co/600x400/8b5cf6/ffffff?text=Python+GIF', // Ganti dengan gambar Nyan Cat jika ada
                'tags' => ['Python', 'Beginner', 'Image Processing'],
                'category' => 'Python',
            ],
            [
                'id' => 2,
                'title' => 'Build a Word Guessing Game',
                'slug' => 'build-a-word-guessing-game',
                'thumbnail' => 'https://placehold.co/600x400/ec4899/ffffff?text=Word+Game', // Ganti dengan gambar Wordle jika ada
                'tags' => ['Python', 'Beginner', 'Game'],
                'category' => 'Python',
            ],
            [
                'id' => 3,
                'title' => 'Set up a GUI with Java',
                'slug' => 'set-up-a-gui-with-java',
                'thumbnail' => 'https://placehold.co/600x400/f87171/ffffff?text=Java+GUI', // Ganti dengan gambar GUI Profile
                'tags' => ['Java', 'Intermediate', 'GUI'],
                'category' => 'Java', // Kategori baru
            ],
            [
                'id' => 4,
                'title' => 'Animate Images with CSS Keyframes',
                'slug' => 'animate-images-with-css-keyframes',
                'thumbnail' => 'https://placehold.co/600x400/22d3ee/ffffff?text=CSS+Animation', // Ganti dengan gambar Sims
                'tags' => ['HTML', 'CSS', 'Beginner', 'Animation'],
                'category' => 'HTML', // Kategori baru
            ],
             [
                'id' => 5,
                'title' => 'Build a Simple React Counter',
                'slug' => 'build-a-simple-react-counter',
                'thumbnail' => 'https://placehold.co/600x400/60a5fa/ffffff?text=React+Counter',
                'tags' => ['JavaScript', 'React', 'Beginner'],
                'category' => 'React', // Kategori baru
            ],
             [
                'id' => 6,
                'title' => 'Data Visualization with Python',
                'slug' => 'data-visualization-with-python',
                'thumbnail' => 'https://placehold.co/600x400/f59e0b/ffffff?text=Data+Viz',
                'tags' => ['Python', 'Data Science', 'Intermediate'],
                'category' => 'Data Science', // Kategori baru
            ],
        ];
    }

    // Daftar kategori/filter statis
    private function getStaticFilters()
    {
        // Mengambil semua kategori unik dari data proyek
        $practices = $this->getStaticPractices();
        $categories = collect($practices)->pluck('category')->unique()->sort()->values()->toArray();
        // Bisa juga ditambahkan tag populer seperti 'Beginner'
        return array_merge(['All'], $categories, ['Beginner']);
    }

    /**
     * Method index: Menampilkan daftar proyek latihan DENGAN FILTER.
     */
    public function index(Request $request)
    {
        $allPractices = $this->getStaticPractices();
        $allFilters = $this->getStaticFilters(); // Daftar filter untuk tab

        // Ambil filter yang diminta dari URL (?filter=...)
        $requestedFilter = $request->query('filter');
        $searchQuery = $request->query('search'); // Ambil query pencarian

        $practices = collect($allPractices);
        $activeFilter = 'All'; // Default filter aktif

        // 1. Filter berdasarkan Kategori/Tag
        if ($requestedFilter && in_array($requestedFilter, $allFilters) && $requestedFilter !== 'All') {
            $practices = $practices->filter(function ($practice) use ($requestedFilter) {
                // Cek apakah filter cocok dengan kategori ATAU salah satu tag
                return $practice['category'] === $requestedFilter || in_array($requestedFilter, $practice['tags']);
            });
            $activeFilter = $requestedFilter;
        }

        // 2. Filter berdasarkan Pencarian (jika ada query)
        if ($searchQuery) {
             $practices = $practices->filter(function ($practice) use ($searchQuery) {
                // Cari di judul atau tags (case-insensitive)
                $searchLower = Str::lower($searchQuery);
                $titleLower = Str::lower($practice['title']);
                $tagsStringLower = Str::lower(implode(' ', $practice['tags'])); // Gabungkan tags jadi string

                return Str::contains($titleLower, $searchLower) || Str::contains($tagsStringLower, $searchLower);
             });
             // Jika ada pencarian, reset filter aktif ke 'All' agar tidak membingungkan
             // kecuali jika filter kategori juga aktif
             if ($activeFilter === 'All'){
                 // Biarkan All
             } elseif (!$requestedFilter) { // Jika hanya ada search, bukan filter
                 $activeFilter = 'All';
             }
        }


        // Ambil hasil filter
        $filteredPractices = $practices->all();

        // Kirim data ke view
        return view('practice.index', compact('filteredPractices', 'allFilters', 'activeFilter', 'searchQuery'));
    }
    // ... (method index() tetap ada di atas sini)

    /**
     * Method show: Menampilkan halaman detail satu proyek latihan berdasarkan slug.
     */
    public function show($slug) // Menerima $slug dari URL
    {
        // 1. Ambil semua data proyek statis
        $allPractices = $this->getStaticPractices();

        // 2. Cari satu proyek yang slug-nya cocok
        $practice = collect($allPractices)->firstWhere('slug', $slug);

        // 3. Jika tidak ditemukan, tampilkan 404 Not Found
        if (!$practice) {
            abort(404);
        }

        // 4. Tambahkan data detail palsu (nanti ini bisa lebih kompleks)
        $practice['details'] = [
            'goal' => 'Tujuan utama dari latihan ini adalah...',
            'steps' => [
                'Langkah 1: Persiapan lingkungan.',
                'Langkah 2: Tulis kode awal.',
                'Langkah 3: Uji coba dan debugging.',
                'Langkah 4: Refaktor kode.',
            ],
            'estimated_time' => 'Sekitar 1 - 2 jam',
        ];

        // 5. Kirim data proyek yang ditemukan ke view 'practice.show'
        return view('practice.show', compact('practice'));
    }
} // Akhir dari class PracticeController


