<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil daftar kategori dinamis langsung dari tabel courses!
        // (Sekaligus mencegah kategori kosong ("") ikut tampil)
        $allCategories = Course::whereNotNull('category')
                               ->where('category', '!=', '')
                               ->distinct()
                               ->pluck('category')
                               ->toArray();
                               
        $displayCategories = array_merge(['Semua'], $allCategories);

        // 2. Tangkap parameter pencarian dari URL
        $searchKeyword = $request->input('search');
        $requestedCategory = $request->input('category');

        $query = Course::query();
        $activeCategory = 'Semua';

        // 3. Logika Filter Kategori
        if ($requestedCategory && in_array($requestedCategory, $allCategories)) {
             $activeCategory = $requestedCategory;
             $query->where('category', $activeCategory);
        }

        // 4. Logika Search Bar
        if (!empty($searchKeyword)) {
            $query->where(function($q) use ($searchKeyword) {
                $q->where('title', 'like', '%' . $searchKeyword . '%')
                  ->orWhere('description', 'like', '%' . $searchKeyword . '%');
            });
        }

        // 5. Eksekusi Query dan tampilkan
        $courses = $query->latest()->paginate(12);

        return view('courses.course', compact('courses', 'displayCategories', 'activeCategory', 'searchKeyword'));
    }

    public function show($slug)
    {
        $course = Course::where('slug', $slug)
                        ->with('modules.lessons') 
                        ->firstOrFail(); 

        return view('courses.show', compact('course'));
    }

    public function learn($slug, $lessonId)
    {
        $course = Course::where('slug', $slug)->with('modules.lessons')->firstOrFail();
        $currentLesson = Lesson::findOrFail($lessonId);

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $isEnrolled = Enrollment::where('user_id', Auth::id())
                                ->where('course_id', $course->id)
                                ->exists();

        if (!$isEnrolled) {
            return redirect()->route('courses.show', $course->slug)
                             ->with('error', 'Akses ditolak. Kamu harus membeli kelas ini terlebih dahulu.');
        }

        return view('courses.learn', compact('course', 'currentLesson'));
    }
}