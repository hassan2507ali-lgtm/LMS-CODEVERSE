<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class MyCourseController extends Controller
{
    public function index()
    {
        // Ambil data kelas yang sudah dibeli oleh user yang sedang login
        $enrollments = Enrollment::where('user_id', Auth::id())
                                 ->with('course') // Ambil juga detail kelasnya
                                 ->latest()
                                 ->get();

        return view('courses.my-courses', compact('enrollments'));
    }
}