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
        $allCategories = Course::whereNotNull('category')
                               ->where('category', '!=', '')
                               ->distinct()
                               ->pluck('category')
                               ->toArray();
                               
        $displayCategories = array_merge(['Semua'], $allCategories);

        $searchKeyword = $request->input('search');
        $requestedCategory = $request->input('category');

        $query = Course::query();
        $activeCategory = 'Semua';

        if ($requestedCategory && in_array($requestedCategory, $allCategories)) {
             $activeCategory = $requestedCategory;
             $query->where('category', $activeCategory);
        }

        if (!empty($searchKeyword)) {
            $query->where(function($q) use ($searchKeyword) {
                $q->where('title', 'like', '%' . $searchKeyword . '%')
                  ->orWhere('description', 'like', '%' . $searchKeyword . '%');
            });
        }

        $courses = $query->latest()->paginate(12);

        // 🔥 Cek ID kursus yang sudah dibeli untuk menampilkan Badge di UI
        $enrolledCourseIds = [];
        if (Auth::check()) {
            $enrolledCourseIds = Enrollment::where('user_id', Auth::id())
                                           ->pluck('course_id')
                                           ->toArray();
        }

        return view('courses.course', compact('courses', 'displayCategories', 'activeCategory', 'searchKeyword', 'enrolledCourseIds'));
    }

    public function show($slug)
    {
        $course = Course::where('slug', $slug)
                        ->with('modules.lessons') 
                        ->firstOrFail(); 

        // 🔥 Ambil 3 kursus kategori serupa untuk fitur Rekomendasi (Cross-Selling)
        $recommendations = Course::where('category', $course->category)
                                 ->where('id', '!=', $course->id)
                                 ->inRandomOrder()
                                 ->take(3)
                                 ->get();

        return view('courses.show', compact('course', 'recommendations'));
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