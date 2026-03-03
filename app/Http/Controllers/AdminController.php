<?php

namespace App\Http\Controllers;

// --- Impor yang Dibutuhkan ---
use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    // ===================================================================
    // === METODE UNTUK CRUD KURSUS (BUKU) ===
    // ===================================================================

    /**
     * Menampilkan halaman utama dashboard admin (daftar kursus).
     */
    public function index()
    {
        $courses = Course::latest()->get();
        return view('dashboard', compact('courses'));
    }

    /**
     * Menampilkan form untuk membuat kursus baru.
     */
    public function create()
    {
        return view('admin.courses.create');
    }

    /**
     * Menyimpan data kursus baru dari form ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255', // <-- Validasi Kategori
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'is_free' => 'required|boolean',
            'thumbnail' => 'nullable|url',
        ]);
        $validatedData['slug'] = Str::slug($validatedData['title']);
        Course::create($validatedData);
        return redirect()->route('dashboard')->with('success', 'Kursus baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit kursus yang sudah ada.
     */
    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    /**
     * Meng-update data kursus yang ada di database.
     */
    public function update(Request $request, Course $course): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255', // <-- Validasi Kategori
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'is_free' => 'required|boolean',
            'thumbnail' => 'nullable|url',
        ]);
        if ($validatedData['title'] !== $course->title) {
            $validatedData['slug'] = Str::slug($validatedData['title']);
        }
        $course->update($validatedData);
        return redirect()->route('dashboard')->with('success', 'Kursus berhasil diperbarui!');
    }

    /**
     * Menghapus kursus dari database.
     */
    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();
        return redirect()->route('dashboard')->with('success', 'Kursus berhasil dihapus!');
    }

    // ===================================================================
    // === METODE UNTUK KELOLA KONTEN (MODUL & LESSON) ===
    // ===================================================================

    /**
     * Menampilkan halaman "Kelola Konten" (daftar modul & lesson).
     */
    public function manageContent(Course $course)
    {
        // Eager load relasi modules, dan di dalam modules, load relasi lessons
        $course->load('modules.lessons');
        return view('admin.courses.content', compact('course'));
    }

    // ===================================================================
    // === METODE UNTUK CRUD MODUL (BAB) ===
    // ===================================================================

    /**
     * Menyimpan modul baru ke kursus yang ditentukan.
     */
    public function storeModule(Request $request, Course $course): RedirectResponse
    {
        $validatedData = $request->validate(['title' => 'required|string|max:255']);
        $order = $course->modules()->count() + 1;
        $course->modules()->create([
            'title' => $validatedData['title'],
            'order' => $order,
        ]);
        return back()->with('success', 'Modul baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit modul.
     */
    public function editModule(Module $module)
    {
        return view('admin.modules.edit', compact('module'));
    }

    /**
     * Menyimpan perubahan dari form edit modul.
     */
    public function updateModule(Request $request, Module $module): RedirectResponse
    {
        $validatedData = $request->validate(['title' => 'required|string|max:255']);
        $module->update($validatedData);
        $courseId = $module->course_id;
        return redirect()->route('admin.courses.content', $courseId)
                         ->with('success', 'Modul berhasil diperbarui!');
    }

    /**
     * Menghapus modul dari database (dan semua lesson di dalamnya).
     */
    public function destroyModule(Module $module): RedirectResponse
    {
        $courseId = $module->course_id;
        $module->delete();
        return redirect()->route('admin.courses.content', $courseId)
                         ->with('success', 'Modul berhasil dihapus!');
    }

    // ===================================================================
    // === METODE UNTUK CRUD LESSON (MATERI) ===
    // ===================================================================

    /**
     * Menyimpan lesson baru ke modul yang ditentukan.
     */
    public function storeLesson(Request $request, Module $module): RedirectResponse
    {
        $validatedData = $request->validate(['lesson_title' => 'required|string|max:255']);
        $order = $module->lessons()->count() + 1;
        $module->lessons()->create([
            'title' => $validatedData['lesson_title'],
            'order' => $order,
            'content_type' => 'text', // Default sementara
            'content' => 'Konten pelajaran belum diisi.', // Default sementara
        ]);
        return back()->with('success', 'Lesson baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit lesson (isi materi).
     */
    public function editLesson(Lesson $lesson)
    {
        return view('admin.lessons.edit', compact('lesson'));
    }

    /**
     * Menyimpan perubahan (isi materi) dari form edit lesson.
     */
    public function updateLesson(Request $request, Lesson $lesson): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content_type' => ['required', Rule::in(['video', 'text', 'image'])],
            'content' => 'required|string',
        ]);
        $lesson->update($validatedData);
        $courseId = $lesson->module->course_id;
        return redirect()->route('admin.courses.content', $courseId)
                         ->with('success', 'Lesson berhasil diperbarui!');
    }

    /**
     * Menghapus lesson dari database.
     * (INI ADALAH METHOD YANG HILANG DARI KODE ANDA)
     */
    public function destroyLesson(Lesson $lesson): RedirectResponse
    {
        // Simpan course_id sebelum dihapus, untuk redirect
        $courseId = $lesson->module->course_id;

        // 1. Hapus lesson
        $lesson->delete();

        // 2. Kembali ke halaman manage content
        return redirect()->route('admin.courses.content', $courseId)
                         ->with('success', 'Lesson berhasil dihapus!');
    }

}