<?php

namespace App\Http\Controllers;

use App\Models\Practice;
use App\Models\PracticeExercise;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\Storage;

class PracticeAdminController extends Controller
{
    public function index(): View
    {
        $practices = Practice::latest()->get();
        return view('admin.practice.index', compact('practices'));
    }

    // ==========================================
    // 🔥 METHOD GENERATE MATERI DENGAN AI (FULL UPDATE: SCHEMA READER)
    // ==========================================
    public function generateAi(Request $request)
    {
        $request->validate([
            'topic' => 'required|string|max:255',
            'database_file' => 'nullable|file|max:20480', // Max 20MB
        ]);

        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            return back()->with('error', 'API Key Gemini belum diatur di .env');
        }

        $tableSchemaInfo = "";
        $dbPath = null;

        // 1. LOGIKA: Intip isi SQLite jika ada file yang di-upload sebelum kirim ke AI
        if ($request->hasFile('database_file')) {
            $file = $request->file('database_file');
            $tempPath = $file->getRealPath();

            try {
                // Membuka koneksi database SQLite sementara
                $db = new \PDO("sqlite:$tempPath");
                $tables = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'")->fetchAll(\PDO::FETCH_COLUMN);
                
                foreach ($tables as $table) {
                    $cols = $db->query("PRAGMA table_info($table)")->fetchAll(\PDO::FETCH_ASSOC);
                    $colNames = implode(', ', array_column($cols, 'name'));
                    $tableSchemaInfo .= "Tabel '$table' memiliki kolom: [$colNames]. ";
                }
                
                // Simpan file secara permanen ke storage
                $dbPath = $file->store('practice_databases', 'public');
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal membaca struktur database: ' . $e->getMessage());
            }
        }

        // 2. Siapkan Prompt untuk AI (Menyertakan Schema jika ada)
        $prompt = "Kamu adalah Senior Curriculum Developer dan Instruktur Coding Expert. 
        Buatkan materi latihan coding berkonsep studi kasus untuk platform LMS dengan topik: '{$request->topic}'.\n";
        
        if (!empty($tableSchemaInfo)) {
            $prompt .= "KONTEKS DATASET (PENTING): User telah mengupload database SQLite dengan struktur berikut: {$tableSchemaInfo}. 
            Kamu WAJIB membuat soal-soal SQL yang HANYA menggunakan tabel dan kolom tersebut agar query valid saat dijalankan.\n";
        }

        $prompt .= "Balas HANYA dengan format JSON mentah tanpa markdown. Struktur JSON wajib seperti ini:
        {
            \"title\": \"Judul Latihan\",
            \"category\": \"Kategori\",
            \"description\": \"Deskripsi materi\",
            \"exercises\": [
                {
                    \"title\": \"Judul Soal\",
                    \"language\": \"sql\",
                    \"description\": \"Penjelasan teori singkat\",
                    \"instructions\": \"Instruksi step-by-step\",
                    \"starter_code\": \"-- tulis query di sini\",
                    \"solution_code\": \"SELECT * FROM ...\",
                    \"hints\": \"Petunjuk teknis\",
                    \"difficulty\": \"easy\"
                }
            ]
        }
        Buatkan TEPAT 8 latihan (exercises) yang progresif (3 easy, 3 medium, 2 hard).";

        try {
            $domain = "https://generativelanguage.googleapis.com";
            $endpoint = "/v1beta/models/gemini-2.5-flash:generateContent?key=" . $apiKey;

            $response = Http::withHeaders(['Content-Type' => 'application/json'])->post($domain . $endpoint, [
                'contents' => [['parts' => [['text' => $prompt]]]],
                'generationConfig' => ['temperature' => 0.7]
            ]);

            $result = $response->json();

            if (!isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                return back()->with('error', 'Gagal mendapatkan respon AI.');
            }

            $aiText = $result['candidates'][0]['content']['parts'][0]['text'];
            $aiText = str_replace(['```json', '```'], '', $aiText);
            $aiData = json_decode(trim($aiText), true);

            // 3. Simpan materi Practice Utama
            $practice = Practice::create([
                'title' => $aiData['title'],
                'slug' => Str::slug($aiData['title'] . '-' . Str::random(5)),
                'category' => $aiData['category'] ?? 'Uncategorized',
                'description' => $aiData['description'] ?? '',
                'database_file' => $dbPath, // Simpan path database
                'is_free' => true, 
                'price' => 0,
                'free_exercises_count' => 0,
            ]);

            // 4. Simpan Soal-soal (Exercises)
            if (isset($aiData['exercises'])) {
                foreach ($aiData['exercises'] as $index => $ex) {
                    PracticeExercise::create([
                        'practice_id' => $practice->id,
                        'title' => $ex['title'],
                        'language' => $ex['language'] ?? "sql",
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
                             ->with('success', '✨ AI berhasil membuat materi sesuai dataset kamu!');

        } catch (\Exception $e) {
            return back()->with('error', 'Kesalahan sistem: ' . $e->getMessage());
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
            'database_file' => 'nullable|file|max:20480', 
        ]);

        $validatedData['slug'] = Str::slug($validatedData['title']);
        $validatedData['is_free'] = $request->boolean('is_free');
        
        if (!empty($validatedData['tags'])) {
            $validatedData['tags'] = array_map('trim', explode(',', $validatedData['tags']));
        }

        if ($validatedData['is_free']) {
            $validatedData['price'] = 0;
            $validatedData['free_exercises_count'] = 0;
        }

        // Upload Database jika ada saat create manual
        if ($request->hasFile('database_file')) {
            $validatedData['database_file'] = $request->file('database_file')->store('practice_databases', 'public');
        }

        Practice::create($validatedData);
        return redirect()->route('admin.practice.index')->with('success', 'Practice berhasil ditambahkan!');
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
            'database_file' => 'nullable|file|max:20480',
        ]);

        $validatedData['is_free'] = $request->boolean('is_free');

        if ($validatedData['title'] !== $practice->title) {
            $validatedData['slug'] = Str::slug($validatedData['title']);
        }

        if (!empty($validatedData['tags']) && is_string($validatedData['tags'])) {
            $validatedData['tags'] = array_map('trim', explode(',', $validatedData['tags']));
        }

        // Logic Update File Database
        if ($request->hasFile('database_file')) {
            if ($practice->database_file) {
                Storage::disk('public')->delete($practice->database_file);
            }
            $validatedData['database_file'] = $request->file('database_file')->store('practice_databases', 'public');
        }

        $practice->update($validatedData);
        return redirect()->route('admin.practice.index')->with('success', 'Update berhasil!');
    }

    public function destroy(Practice $practice): RedirectResponse
    {
        if ($practice->database_file) {
            Storage::disk('public')->delete($practice->database_file);
        }
        $practice->delete();
        return redirect()->route('admin.practice.index')->with('success', 'Practice dihapus!');
    }

    // ==========================================
    // METHOD UNTUK MANAGE EXERCISES
    // ==========================================
    public function manageExercises(Practice $practice): View
    {
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

        $validatedData['order'] = $practice->exercises()->count() + 1;
        $validatedData['is_completed'] = false;

        $practice->exercises()->create($validatedData);
        return redirect()->route('admin.practice.exercises.manage', $practice->id)->with('success', 'Exercise ditambahkan!');
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
        return redirect()->route('admin.practice.exercises.manage', $practice->id)->with('success', 'Exercise diperbarui!');
    }

    public function destroyExercise(Practice $practice, PracticeExercise $exercise): RedirectResponse
    {
        $exercise->delete();
        return redirect()->route('admin.practice.exercises.manage', $practice->id)->with('success', 'Exercise dihapus!');
    }

    // ==========================================
    // AI UNTUK MODUL LANJUTAN
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

        // 🔥 LOGIKA BARU: BACA DATASET YANG SUDAH TERSIMPAN DI STORAGE
        $tableSchemaInfo = "";
        if ($practice->database_file) {
            try {
                // Ambil path fisik file dari storage Laravel
                $tempPath = storage_path('app/public/' . $practice->database_file);
                
                if (file_exists($tempPath)) {
                    $db = new \PDO("sqlite:$tempPath");
                    $tables = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'")->fetchAll(\PDO::FETCH_COLUMN);
                    
                    foreach ($tables as $table) {
                        $cols = $db->query("PRAGMA table_info($table)")->fetchAll(\PDO::FETCH_ASSOC);
                        $colNames = implode(', ', array_column($cols, 'name'));
                        $tableSchemaInfo .= "Tabel '$table' memiliki kolom: [$colNames]. ";
                    }
                }
            } catch (\Exception $e) {
                // Jika gagal baca, biarkan string kosong agar AI tetap jalan (fallback)
            }
        }

        $prompt = "Kamu adalah Senior Curriculum Developer. Materi utama kita adalah '{$practice->title}'.\n";
        $prompt .= "Tugasmu adalah membuat SUB-MODUL LANJUTAN dengan topik spesifik: '{$request->module_topic}'.\n";

        // 🔥 INGATKAN AI TENTANG STRUKTUR TABELNYA LAGI
        if (!empty($tableSchemaInfo)) {
            $prompt .= "KONTEKS DATASET (PENTING): Materi ini menggunakan database SQLite dengan struktur berikut: {$tableSchemaInfo}. 
            Kamu WAJIB membuat soal SQL yang HANYA menggunakan tabel dan kolom tersebut agar query valid saat dijalankan.\n";
        }

        $prompt .= "Balas HANYA dengan format JSON mentah tanpa markdown. Struktur JSON wajib seperti ini:
        {
            \"section_name\": \"Nama Modul (Contoh: Part 2: Filtering & Sorting)\",
            \"exercises\": [
                {
                    \"title\": \"Judul Soal Lanjutan\",
                    \"language\": \"{$request->language}\",
                    \"description\": \"Teori singkat untuk menyambung materi sebelumnya\",
                    \"instructions\": \"Instruksi step-by-step pengerjaan\",
                    \"starter_code\": \"-- tulis query di sini\",
                    \"solution_code\": \"SELECT ...\",
                    \"hints\": \"Petunjuk teknis\",
                    \"difficulty\": \"medium\"
                }
            ]
        }
        
        INSTRUKSI JUMLAH SOAL:
        Buatkan TEPAT 5 soal yang berurutan tingkat kesulitannya untuk sub-modul ini.";

        try {
            $domain = "https://generativelanguage.googleapis.com";
            $endpoint = "/v1beta/models/gemini-2.5-flash:generateContent?key=" . $apiKey;

            $response = Http::withHeaders(['Content-Type' => 'application/json'])->post($domain . $endpoint, [
                'contents' => [['parts' => [['text' => $prompt]]]],
                'generationConfig' => ['temperature' => 0.7]
            ]);

            $result = $response->json();

            if (isset($result['error'])) {
                return back()->with('error', "Error Gemini: " . ($result['error']['message'] ?? json_encode($result['error'])));
            }

            if (!isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                return back()->with('error', 'Gagal mendapat respon AI. Coba lagi.');
            }

            $aiText = str_replace(['```json', '```'], '', $result['candidates'][0]['content']['parts'][0]['text']);
            $aiData = json_decode(trim($aiText), true);

            // Validasi jika AI ngeyel gak ngasih format JSON yang bener
            if (!$aiData || !isset($aiData['exercises'])) {
                return back()->with('error', 'Format JSON dari AI rusak atau kosong, silakan coba tekan Generate lagi.');
            }

            $currentMaxOrder = $practice->exercises()->max('order') ?? 0;

            // Simpan soal-soal baru ke database
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

            return back()->with('success', '✨ Modul lanjutan berhasil ditambahkan dan disesuaikan dengan dataset!');

        } catch (\Exception $e) {
            return back()->with('error', 'Kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function updateModuleName(Request $request, Practice $practice): RedirectResponse
    {
        $request->validate(['old_name' => 'required', 'new_name' => 'required']);
        $oldName = $request->old_name === 'General Exercises' ? null : $request->old_name;
        $practice->exercises()->where('section_name', $oldName)->update(['section_name' => $request->new_name]);
        return back()->with('success', 'Nama modul diperbarui!');
    }

    public function reorderExercises(Request $request, Practice $practice)
    {
        foreach ($request->items as $index => $item) {
            PracticeExercise::where('id', $item['id'])->update([
                'order' => $index + 1, 
                'section_name' => $item['section_name'] 
            ]);
        }
        return response()->json(['success' => true]);
    }
}