<?php

namespace App\Http\Controllers;

use App\Models\Practice;
use App\Models\PracticeExercise;
use Illuminate\Http\Request;

class PracticeController extends Controller
{
    // 1. Menampilkan daftar semua topik latihan dengan Fitur Search & Filter
    public function index(Request $request)
    {
        // Mulai membuat query untuk mengambil data Practice
        $query = Practice::query();

        // Ambil input dari URL (jika kosong, set default)
        $searchKeyword = $request->input('search', '');
        $activeCategory = $request->input('category', 'All');

        // Fitur Pencarian (Mencari di judul atau deskripsi)
        if (!empty($searchKeyword)) {
            $query->where(function($q) use ($searchKeyword) {
                $q->where('title', 'like', '%' . $searchKeyword . '%')
                  ->orWhere('description', 'like', '%' . $searchKeyword . '%');
            });
        }

        // Fitur Filter Kategori
        if ($activeCategory !== 'All') {
            // Logika ini akan mencari data berdasarkan kolom 'category' di tabel practices
            $query->where('category', $activeCategory); 
        }

        // Ambil data yang sudah disaring beserta jumlah soalnya, urutkan dari yang terbaru
        $practices = $query->withCount('exercises')->latest()->paginate(12);

        // Kirim data ke tampilan (view)
        return view('practice.index', compact('practices', 'activeCategory', 'searchKeyword'));
    }

    // 2. Menampilkan detail satu topik latihan dan daftar soalnya
    public function show($slug)
    {
        $practice = Practice::where('slug', $slug)->firstOrFail();

        // 🔥 FITUR BARU: Mengelompokkan soal berdasarkan 'section_name' (Nama Modul)
        $groupedExercises = $practice->exercises()
                                     ->orderBy('order', 'asc')
                                     ->get()
                                     ->groupBy(function($exercise) {
                                         // Jika ada soal yang belum dikasih nama modul, masuk ke "General Exercises"
                                         return $exercise->section_name ?: 'General Exercises';
                                     });

        return view('practice.show', compact('practice', 'groupedExercises'));
    }

    // 3. Halaman ruang mengerjakan soal (Ruang Praktik)
    public function startExercise($slug, PracticeExercise $exercise)
    {
        $practice = Practice::where('slug', $slug)->firstOrFail();

        // Keamanan: Pastikan soal yang dibuka memang milik topik latihan ini
        if ($exercise->practice_id !== $practice->id) {
            abort(404);
        }

        // --- FITUR FREEMIUM GUARD ---
        $isPremium = !$practice->is_free;
        // Asumsi urutan soal berdasarkan $exercise->order
        $isFreeExercise = $exercise->order <= $practice->free_exercises_count;
        
        // Cek ke tabel transaksi
        $hasBought = \App\Models\Transaction::where('user_id', auth()->id())
                        ->where('practice_id', $practice->id)
                        ->where('status', 'success')
                        ->exists();

        // Jika ini soal premium, BUKAN jatah gratis, dan user BELUM beli -> TENDANG KEMBALI!
        if ($isPremium && !$isFreeExercise && !$hasBought) {
            return redirect()->route('practice.show', $practice->slug)
                             ->with('error', '🔒 Soal ini adalah fitur Premium. Silakan beli akses untuk melanjutkan.');
        }
        // -----------------------------

        return view('practice.exercise', compact('practice', 'exercise'));
    }

    // 4. FITUR BARU: Menerima sinyal dari Auto-Grader untuk menandai selesai
    public function markCompleted(Request $request, PracticeExercise $exercise)
    {
        $user = $request->user();
        
        // Cek apakah user belum pernah menyelesaikan soal ini sebelumnya
        if (!$user->completedExercises->contains($exercise->id)) {
            // Jika belum, stempel/simpan progressnya ke pivot table
            $user->completedExercises()->attach($exercise->id);
        }

        // Kembalikan respon sukses secara rahasia (tanpa reload halaman)
        return response()->json(['success' => true]);
    }
}