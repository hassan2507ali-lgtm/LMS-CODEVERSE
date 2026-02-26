<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Transaction;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function process(Request $request, Course $course)
    {
        $user = Auth::user();

        // 1. Cek apakah user sudah punya akses (sudah enroll)
        $isEnrolled = Enrollment::where('user_id', $user->id)
                                ->where('course_id', $course->id)
                                ->exists();

        if ($isEnrolled) {
            return back()->with('error', 'Kamu sudah memiliki akses ke kelas ini.');
        }

        // 2. Jika kelas gratis, langsung beri akses tanpa Midtrans
        if ($course->is_free || $course->price == 0) {
            Enrollment::create([
                'user_id' => $user->id,
                'course_id' => $course->id
            ]);
            return back()->with('success', 'Berhasil mendaftar ke kelas gratis!');
        }

        // 3. Buat Transaksi Baru (Status: Pending)
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'reference_number' => 'TRX-' . strtoupper(Str::random(10)), // Contoh: TRX-A1B2C3D4E5
            'amount' => $course->price,
            'status' => 'pending',
        ]);

        // 4. Konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
        Config::$is3ds = env('MIDTRANS_IS_3DS', true);

        // 5. Siapkan data yang akan dikirim ke Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $transaction->reference_number,
                'gross_amount' => (int) $transaction->amount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'item_details' => [
                [
                    'id' => $course->id,
                    'price' => (int) $course->price,
                    'quantity' => 1,
                    'name' => substr($course->title, 0, 50), // Dibatasi 50 karakter agar Midtrans tidak error
                ]
            ]
        ];

        try {
            // ==========================================
            // MODE SIMULASI LOKAL (TANPA MIDTRANS DULU)
            // ==========================================
            
            // 1. Anggap saja pembayaran langsung sukses
            $transaction->update([
                'status' => 'success',
                'payment_url' => 'simulasi-lokal'
            ]);

            // 2. Berikan akses kelas ke user tersebut (Enrollment)
            Enrollment::create([
                'user_id' => $user->id,
                'course_id' => $course->id
            ]);

            // 3. Kembalikan ke halaman kursus dengan pesan sukses
            return back()->with('success', 'Simulasi Pembayaran Berhasil! Kelas sudah terbuka.');


            /* // ==========================================
            // KODE MIDTRANS ASLI (KITA KOMENTARI DULU)
            // ==========================================
            // 6. Dapatkan Link Pembayaran dari Midtrans
            $paymentUrl = Snap::createTransaction($params)->redirect_url;
            
            // Simpan Link tersebut ke database transaksi kita
            $transaction->update([
                'payment_url' => $paymentUrl
            ]);

            // 7. Arahkan user ke halaman Midtrans
            return redirect($paymentUrl);
            */
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }
}