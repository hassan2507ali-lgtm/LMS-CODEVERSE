<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    private function getStaticCategories()
    {
        return ['Programming', 'Data Science & Analytics', 'UI/UX Design', 'Lainnya'];
    }

    public function index(Request $request)
    {
        $allCategories = $this->getStaticCategories();
        $requestedCategory = $request->query('category');
        $query = Course::query();
        $activeCategory = 'Semua';

        if ($requestedCategory && in_array($requestedCategory, $allCategories)) {
             $activeCategory = $requestedCategory;
        }

        $courses = $query->latest()->paginate(12);
        $displayCategories = array_merge(['Semua'], $allCategories);

        return view('courses.course', compact('courses', 'displayCategories', 'activeCategory'));
    }

    public function show($slug)
    {
        $course = Course::where('slug', $slug)
                        ->with('modules.lessons') 
                        ->firstOrFail(); 

        return view('courses.show', compact('course'));
    }

    // === METHOD BARU UNTUK RUANG BELAJAR ===
    public function learn($slug, $lessonId)
    {
        // 1. Ambil data course dan lesson yang sedang dibuka
        $course = Course::where('slug', $slug)->with('modules.lessons')->firstOrFail();
        $currentLesson = Lesson::findOrFail($lessonId);

        // 2. Keamanan: Cek apakah user sudah login dan benar-benar sudah beli kelasnya
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $isEnrolled = Enrollment::where('user_id', Auth::id())
                                ->where('course_id', $course->id)
                                ->exists();

        // Jika nekat buka URL padahal belum beli, tendang balik ke halaman detail kelas
        if (!$isEnrolled) {
            return redirect()->route('courses.show', $course->slug)
                             ->with('error', 'Akses ditolak. Kamu harus membeli kelas ini terlebih dahulu.');
        }

        // 3. Tampilkan halaman ruang belajar
        return view('courses.learn', compact('course', 'currentLesson'));
    }
}