<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Transaction; // <-- Tambahkan model Transaction
use Illuminate\Support\Facades\Auth;

class MyCourseController extends Controller
{
    public function index()
    {
        // 1. Ambil data Kelas (Courses) yang sudah di-enroll
        $enrollments = Enrollment::where('user_id', Auth::id())
                                 ->with('course') // Ambil juga detail kelasnya
                                 ->latest()
                                 ->get();

        // 2. Ambil data Latihan Premium (Practices) yang sudah dibeli
        $premiumPractices = Transaction::where('user_id', Auth::id())
                                 ->whereNotNull('practice_id') // Hanya ambil transaksi Practice
                                 ->where('status', 'success')  // Hanya yang pembayarannya sukses
                                 ->with('practice')            // Ambil detail practicenya
                                 ->latest()
                                 ->get();

        return view('courses.my-courses', compact('enrollments', 'premiumPractices'));
    }
}