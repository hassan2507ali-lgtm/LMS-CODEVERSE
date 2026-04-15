<?php

namespace App\Http\Controllers;

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

    public function index()
    {
        $courses = Course::latest()->get();
        return view('dashboard', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'is_free' => 'required|boolean',
            'thumbnail' => 'nullable|url',
        ]);
        $validatedData['slug'] = Str::slug($validatedData['title']);
        Course::create($validatedData);
        return redirect()->route('dashboard')->with('success', 'Kursus baru berhasil ditambahkan!');
    }

    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
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

    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();
        return redirect()->route('dashboard')->with('success', 'Kursus berhasil dihapus!');
    }

    // ===================================================================
    // === METODE UNTUK KELOLA KONTEN (MODUL & LESSON) ===
    // ===================================================================

    public function manageContent(Course $course)
    {
        $course->load('modules.lessons');
        return view('admin.courses.content', compact('course'));
    }

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

    public function editModule(Module $module)
    {
        return view('admin.modules.edit', compact('module'));
    }

    public function updateModule(Request $request, Module $module): RedirectResponse
    {
        $validatedData = $request->validate(['title' => 'required|string|max:255']);
        $module->update($validatedData);
        $courseId = $module->course_id;
        return redirect()->route('admin.courses.content', $courseId)->with('success', 'Modul berhasil diperbarui!');
    }

    public function destroyModule(Module $module): RedirectResponse
    {
        $courseId = $module->course_id;
        $module->delete();
        return redirect()->route('admin.courses.content', $courseId)->with('success', 'Modul berhasil dihapus!');
    }

    public function storeLesson(Request $request, Module $module): RedirectResponse
    {
        $validatedData = $request->validate(['lesson_title' => 'required|string|max:255']);
        $order = $module->lessons()->count() + 1;
        $module->lessons()->create([
            'title' => $validatedData['lesson_title'],
            'order' => $order,
            'content_type' => 'text',
            'content' => 'Konten pelajaran belum diisi.',
        ]);
        return back()->with('success', 'Lesson baru berhasil ditambahkan!');
    }

    public function editLesson(Lesson $lesson)
    {
        return view('admin.lessons.edit', compact('lesson'));
    }

    public function updateLesson(Request $request, Lesson $lesson): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content_type' => ['required', Rule::in(['video', 'text', 'image'])],
            'content' => 'required|string',
        ]);
        $lesson->update($validatedData);
        $courseId = $lesson->module->course_id;
        return redirect()->route('admin.courses.content', $courseId)->with('success', 'Lesson berhasil diperbarui!');
    }

    public function destroyLesson(Lesson $lesson): RedirectResponse
    {
        $courseId = $lesson->module->course_id;
        $lesson->delete();
        return redirect()->route('admin.courses.content', $courseId)->with('success', 'Lesson berhasil dihapus!');
    }

    // ==========================================
    // METHOD UNTUK LAPORAN TRANSAKSI (DENGAN FILTER)
    // ==========================================
    public function transactions(Request $request)
    {
        $query = \App\Models\Transaction::with(['user', 'course', 'practice']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->start_date . ' 00:00:00');
        }
        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }

        $totalRevenue = (clone $query)->where('status', 'success')->sum('amount');
        $totalSales = (clone $query)->where('status', 'success')->count();
        $transactions = $query->latest()->paginate(20)->withQueryString();

        return view('admin.transactions.index', compact('transactions', 'totalRevenue', 'totalSales'));
    }

    // ==========================================
    // 🔥 METHOD UNTUK SAKELAR AKSES (ON / OFF)
    // ==========================================
    public function toggleAccess(\App\Models\Transaction $transaction)
    {
        // 1. SKENARIO MEMATIKAN AKSES (Turn OFF)
        if ($transaction->status === 'success') {
            $transaction->update(['status' => 'revoked']);

            if ($transaction->course_id) {
                \App\Models\Enrollment::where('user_id', $transaction->user_id)
                    ->where('course_id', $transaction->course_id)
                    ->delete();
            }
            return back()->with('success', "Akses untuk {$transaction->user->name} berhasil DICABUT.");
        }

        // 2. SKENARIO MENGHIDUPKAN AKSES (Turn ON)
        $transaction->update(['status' => 'success']);

        if ($transaction->course_id) {
            \App\Models\Enrollment::firstOrCreate([
                'user_id' => $transaction->user_id,
                'course_id' => $transaction->course_id
            ]);
        }
        return back()->with('success', "Akses untuk {$transaction->user->name} berhasil DIBERIKAN.");
    }
}