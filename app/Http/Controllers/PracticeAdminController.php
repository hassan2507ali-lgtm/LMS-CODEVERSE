<?php

namespace App\Http\Controllers;

use App\Models\Practice;
use App\Models\PracticeExercise;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http; 

class PracticeAdminController extends Controller
{
    public function index(): View
    {
        $practices = Practice::latest()->get();
        return view('admin.practice.index', compact('practices'));
    }

    // ==========================================
    // METHOD UNTUK GENERATE MATERI DENGAN AI
    // ==========================================
    public function generateAi(Request $request)
    {
        $request->validate([
            'topic' => 'required|string|max:255'
        ]);

        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            return back()->with('error', 'API Key Gemini belum diatur di .env');
        }

        // 1. Siapkan Prompt (Instruksi) untuk AI 
        $prompt = "Kamu adalah Senior Curriculum Developer dan Instruktur Coding Expert. 
        Buatkan materi latihan coding berkonsep studi kasus untuk platform LMS dengan topik: '{$request->topic}'.
        
        SYARAT PENTING:
        Materi ini HARUS dirancang sangat progresif, dimulai dari level 0 (Absolute Beginner) hingga level Intermediate. 
        Penjelasan harus ramah pemula, tapi perlahan menantang logika user.

        Balas HANYA dengan format JSON mentah tanpa markdown. Struktur JSON wajib seperti ini:
        {
            \"title\": \"Judul Latihan (misal: SQL Part 1: Fundamental)\",
            \"category\": \"Kategori (contoh: Data Science, Web, Backend)\",
            \"description\": \"Deskripsi persuasif tentang alur belajar dari materi ini\",
            \"exercises\": [
                {
                    \"title\": \"Judul Soal (Mulai dari pengenalan dasar)\",
                    \"language\": \"javascript\", // (WAJIB SESUAIKAN DENGAN TOPIK: python, html, javascript, atau sql)
                    \"description\": \"Penjelasan teori super singkat sebelum praktek\",
                    \"instructions\": \"Instruksi step-by-step pengerjaan yang sangat jelas\",
                    \"starter_code\": \"// kode awal yang diberikan ke user\",
                    \"solution_code\": \"// kunci jawaban yang benar\",
                    \"hints\": \"Petunjuk teknis jika user kebingungan\",
                    \"difficulty\": \"easy\" // (pilih: easy, medium, hard)
                }
            ]
        }
        
        INSTRUKSI JUMLAH SOAL:
        Buatkan TEPAT 8 latihan (exercises). 
        Susun kurvanya secara bertahap: 
        - Soal 1-3: Difficulty 'easy' (Pengenalan sintaks dasar dari 0).
        - Soal 4-6: Difficulty 'medium' (Mulai menggabungkan 2-3 konsep).
        - Soal 7-8: Difficulty 'hard' (Studi kasus mini level intermediate).";

        try {
            $domain = "https://" . "generativelanguage.googleapis.com";
            $endpoint = "/v1beta/models/gemini-2.5-flash:generateContent?key=";
            $url = $domain . $endpoint . $apiKey;

            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post($url, [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ],
                'generationConfig' => [
                    'temperature' => 0.7, 
                ]
            ]);

            $result = $response->json();

            if (isset($result['error'])) {
                dd("PESAN ERROR DARI GOOGLE GEMINI: " . ($result['error']['message'] ?? json_encode($result['error'])));
            }

            if (!isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                return back()->with('error', 'Gagal mendapatkan respon yang valid dari AI. Cek koneksi atau kuota API.');
            }

            $aiText = $result['candidates'][0]['content']['parts'][0]['text'];
            $aiText = str_replace(['```json', '```'], '', $aiText);
            $aiData = json_decode(trim($aiText), true);

            if (!$aiData || !isset($aiData['title'])) {
                return back()->with('error', 'AI mengembalikan format data yang salah, silakan coba lagi.');
            }

            $practice = Practice::create([
                'title' => $aiData['title'],
                'slug' => Str::slug($aiData['title'] . '-' . Str::random(5)),
                'category' => $aiData['category'] ?? 'Uncategorized',
                'description' => $aiData['description'] ?? '',
                'is_free' => true, 
                'price' => 0,
                'free_exercises_count' => 0,
            ]);

            if (isset($aiData['exercises']) && is_array($aiData['exercises'])) {
                foreach ($aiData['exercises'] as $index => $ex) {
                    PracticeExercise::create([
                        'practice_id' => $practice->id,
                        'title' => $ex['title'] ?? "Soal " . ($index + 1),
                        'language' => $ex['language'] ?? "javascript",
                        'description' => $ex['description'] ?? "",
                        'instructions' => $ex['instructions'] ?? "",
                        'starter_code' => $ex['starter_code'] ?? "",
                        'solution_code' => $ex['solution_code'] ?? "",
                        'hints' => $ex['hints'] ?? "",
                        'difficulty' => $ex['difficulty'] ?? "easy",
                        'order' => $index + 1,
                    ]);
                }
            }

            return redirect()->route('admin.practice.edit', $practice->id)
                             ->with('success', '✨ Ajaib! Materi berhasil dibuat oleh AI. Silakan sesuaikan Harga, Akses Gembok, dan review detail soalnya.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function create(): View
    {
        return view('admin.practice.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|string',   
            'github_link' => 'nullable|url',    
            'tags' => 'nullable|string',
            'content' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'free_exercises_count' => 'nullable|integer|min:0',
        ]);

        $validatedData['slug'] = Str::slug($validatedData['title']);
        $validatedData['is_free'] = $request->boolean('is_free');
        
        if (!empty($validatedData['tags']) && is_string($validatedData['tags'])) {
            $validatedData['tags'] = array_map('trim', explode(',', $validatedData['tags']));
        } else {
            $validatedData['tags'] = null;
        }

        if ($validatedData['is_free']) {
            $validatedData['price'] = 0;
            $validatedData['free_exercises_count'] = 0;
        }

        Practice::create($validatedData);

        return redirect()->route('admin.practice.index')->with('success', 'Proyek latihan berhasil ditambahkan!');
    }

    public function edit(Practice $practice): View
    {
        return view('admin.practice.edit', compact('practice'));
    }

    public function update(Request $request, Practice $practice): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'github_link' => 'nullable|string',
            'tags' => 'nullable|string',
            'content' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'free_exercises_count' => 'nullable|integer|min:0',
        ]);

        $validatedData['is_free'] = $request->boolean('is_free');

        if ($validatedData['title'] !== $practice->title) {
            $validatedData['slug'] = Str::slug($validatedData['title']);
        }

        if (!empty($validatedData['tags'])) {
            $validatedData['tags'] = array_map('trim', explode(',', $validatedData['tags']));
        } else {
            $validatedData['tags'] = null;
        }

        if ($validatedData['is_free']) {
            $validatedData['price'] = 0;
            $validatedData['free_exercises_count'] = 0; 
        }

        $practice->update($validatedData);

        return redirect()->route('admin.practice.index')->with('success', 'Proyek latihan & Pengaturan Harga berhasil diperbarui!');
    }

    public function destroy(Practice $practice): RedirectResponse
    {
        $practice->delete();
        return redirect()->route('admin.practice.index')->with('success', 'Proyek latihan berhasil dihapus!');
    }

    // ==========================================
    // METHOD UNTUK MANAGE EXERCISES
    // ==========================================
    public function manageExercises(Practice $practice): View
    {
        // 🔥 Kelompokkan soal berdasarkan 'section_name' dan urutkan sesuai 'order'
        $groupedExercises = $practice->exercises()
                                     ->orderBy('order', 'asc')
                                     ->get()
                                     ->groupBy(function($exercise) {
                                         return $exercise->section_name ?: 'General Exercises';
                                     });

        return view('admin.practice.exercises', compact('practice', 'groupedExercises'));
    }

    public function storeExercise(Request $request, Practice $practice): RedirectResponse
    {
        $validatedData = $request->validate([
            'section_name' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'language' => 'required|in:python,html,javascript,sql,php',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'starter_code' => 'nullable|string',
            'solution_code' => 'nullable|string',
            'hints' => 'nullable|string',
            'difficulty' => 'required|in:easy,medium,hard',
        ]);

        $order = $practice->exercises()->count() + 1;
        $validatedData['order'] = $order;
        $validatedData['is_completed'] = false;

        $practice->exercises()->create($validatedData);

        return redirect()->route('admin.practice.exercises.manage', $practice->id)
            ->with('success', 'Exercise berhasil ditambahkan secara manual!');
    }

    public function editExercise(Practice $practice, PracticeExercise $exercise): View
    {
        return view('admin.practice.exercise-edit', compact('practice', 'exercise'));
    }

    public function updateExercise(Request $request, Practice $practice, PracticeExercise $exercise): RedirectResponse
    {
        $validatedData = $request->validate([
            'section_name' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'language' => 'required|in:python,html,javascript,sql,php',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'starter_code' => 'nullable|string',
            'solution_code' => 'nullable|string',
            'hints' => 'nullable|string',
            'difficulty' => 'required|in:easy,medium,hard',
        ]);

        $exercise->update($validatedData);

        return redirect()->route('admin.practice.exercises.manage', $practice->id)
            ->with('success', 'Exercise berhasil diperbarui!');
    }

    public function destroyExercise(Practice $practice, PracticeExercise $exercise): RedirectResponse
    {
        $exercise->delete();

        return redirect()->route('admin.practice.exercises.manage', $practice->id)
            ->with('success', 'Exercise berhasil dihapus!');
    }

    // ==========================================
    // METHOD AI: UNTUK MELANJUTKAN / NAMBAH MODUL DI PRACTICE YANG SAMA
    // ==========================================
    public function generateAiExercises(Request $request, Practice $practice)
    {
        $request->validate([
            'module_topic' => 'required|string|max:255',
            'language' => 'required|string|max:50'
        ]);

        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            return back()->with('error', 'API Key Gemini belum diatur di .env');
        }

        $prompt = "Kamu adalah Senior Curriculum Developer. Materi utama kita adalah '{$practice->title}'.
        Tugasmu adalah membuat SUB-MODUL LANJUTAN dengan topik spesifik: '{$request->module_topic}'.
        
        Balas HANYA dengan format JSON mentah tanpa markdown. Struktur JSON wajib seperti ini:
        {
            \"section_name\": \"Nama Modul (Contoh: Part 2: Joins & Aggregations)\",
            \"exercises\": [
                {
                    \"title\": \"Judul Soal Lanjutan\",
                    \"language\": \"{$request->language}\",
                    \"description\": \"Teori singkat untuk menyambung materi sebelumnya\",
                    \"instructions\": \"Instruksi step-by-step\",
                    \"starter_code\": \"// kode awal\",
                    \"solution_code\": \"// kunci jawaban\",
                    \"hints\": \"Petunjuk teknis\",
                    \"difficulty\": \"medium\"
                }
            ]
        }
        
        INSTRUKSI JUMLAH SOAL:
        Buatkan 5 sampai 8 soal yang berurutan tingkat kesulitannya untuk sub-modul ini.";

        try {
            $domain = "https://" . "generativelanguage.googleapis.com";
            $endpoint = "/v1beta/models/gemini-2.5-flash:generateContent?key=";
            $url = $domain . $endpoint . $apiKey;

            $response = Http::withHeaders(['Content-Type' => 'application/json'])->post($url, [
                'contents' => [['parts' => [['text' => $prompt]]]],
                'generationConfig' => ['temperature' => 0.7]
            ]);

            $result = $response->json();

            if (isset($result['error'])) {
                return back()->with('error', "Error Gemini: " . ($result['error']['message'] ?? json_encode($result['error'])));
            }

            if (!isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                return back()->with('error', 'Gagal mendapat respon AI.');
            }

            $aiText = str_replace(['```json', '```'], '', $result['candidates'][0]['content']['parts'][0]['text']);
            $aiData = json_decode(trim($aiText), true);

            if (!$aiData || !isset($aiData['exercises'])) {
                return back()->with('error', 'Format JSON dari AI rusak, silakan coba lagi.');
            }

            $currentMaxOrder = $practice->exercises()->max('order') ?? 0;

            foreach ($aiData['exercises'] as $index => $ex) {
                PracticeExercise::create([
                    'practice_id' => $practice->id,
                    'section_name' => $aiData['section_name'] ?? $request->module_topic, 
                    'title' => $ex['title'] ?? "Soal Lanjutan",
                    'language' => $ex['language'] ?? $request->language,
                    'description' => $ex['description'] ?? "",
                    'instructions' => $ex['instructions'] ?? "",
                    'starter_code' => $ex['starter_code'] ?? "",
                    'solution_code' => $ex['solution_code'] ?? "",
                    'hints' => $ex['hints'] ?? "",
                    'difficulty' => $ex['difficulty'] ?? "medium",
                    'order' => $currentMaxOrder + $index + 1, 
                ]);
            }

            return back()->with('success', '✨ Modul lanjutan berhasil ditambahkan ke materi ini!');

        } catch (\Exception $e) {
            return back()->with('error', 'Kesalahan sistem: ' . $e->getMessage());
        }
    }

    // ==========================================
    // METHOD UNTUK MENGUBAH NAMA MODUL (MASS UPDATE)
    // ==========================================
    public function updateModuleName(Request $request, Practice $practice): RedirectResponse
    {
        $request->validate([
            'old_name' => 'required|string',
            'new_name' => 'required|string|max:255',
        ]);

        $oldName = $request->old_name === 'General Exercises' ? null : $request->old_name;

        $practice->exercises()
                 ->where('section_name', $oldName)
                 ->update(['section_name' => $request->new_name]);

        return back()->with('success', '✨ Nama Modul berhasil diubah! Semua isi di dalamnya otomatis terupdate.');
    }

    // ==========================================
    // METHOD UNTUK MENYIMPAN URUTAN DRAG & DROP
    // ==========================================
    public function reorderExercises(Request $request, Practice $practice)
    {
        $items = $request->items; 
        
        if (!$items || !is_array($items)) {
            return response()->json(['success' => false, 'message' => 'Data tidak valid.']);
        }

        foreach ($items as $index => $item) {
            PracticeExercise::where('id', $item['id'])
                ->where('practice_id', $practice->id)
                ->update([
                    'order' => $index + 1, 
                    'section_name' => $item['section_name'] 
                ]);
        }

        return response()->json(['success' => true]);
    }
}