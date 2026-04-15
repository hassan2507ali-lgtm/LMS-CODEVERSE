<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Mail;
use App\Mail\AnnouncementMail;

class GoogleAuthController extends Controller
{
    // 1. Fungsi melempar user ke halaman Login Google
    public function redirect()
    {
        // Memaksa Google menampilkan layar pilih akun agar memudahkan testing
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    // 2. Fungsi menangkap data user setelah berhasil login di Google
    public function callback()
    {
        try {
            // Ambil data user dari Google
            $googleUser = Socialite::driver('google')->user();

            // Cek apakah user sudah ada di database berdasarkan google_id atau email
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if (!$user) {
                // Jika user belum ada sama sekali, buatkan akun baru (Register Otomatis)
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => Hash::make(Str::random(16)), // Password acak aman
                ]);

                // 🔥 FITUR BARU: OTOMATIS KIRIM WELCOME EMAIL UNTUK MURID BARU
                $judul = "Selamat Datang di Code Verse Academy, " . $user->name . "! 🚀";
                $pesan = "Akun kamu berhasil dibuat menggunakan Google. Sekarang kamu resmi menjadi bagian dari komunitas programmer masa depan. Yuk, langsung pilih kelas pertamamu dan mulai belajar!";

                try {
                    Mail::to($user->email)->send(new AnnouncementMail($judul, $pesan));
                } catch (\Exception $e) {
                    // Log error jika pengiriman email gagal (misal limit Gmail habis)
                    // Agar user tetap bisa login meski email gagal terkirim
                    \Log::error("Gagal kirim welcome email Google: " . $e->getMessage());
                }

            } else {
                // Jika user sudah pernah daftar manual, lalu sekarang login pakai Google
                // Update google_id agar kedua akun tersebut tersambung (sinkron)
                $user->update([
                    'google_id' => $googleUser->id
                ]);
            }

            // Login-kan user ke sistem secara otomatis
            Auth::login($user);

            // Arahkan ke dashboard
            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            // Jika terjadi gangguan teknis, kembalikan ke login dengan pesan error
            return redirect('/login')->with('error', 'Login dengan Google gagal atau dibatalkan.');
        }
    }
}