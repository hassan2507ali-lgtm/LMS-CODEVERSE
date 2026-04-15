<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use App\Mail\AnnouncementMail;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        // Tambahkan kode ini:
        $judul = "Selamat Datang di Code Verse Academy, " . $user->name . "! 🚀";
        $pesan = "Akun kamu sudah aktif. Sekarang kamu resmi menjadi bagian dari komunitas programmer masa depan. Yuk, langsung pilih kelas pertamamu dan mulai belajar!";
        
        try {
            Mail::to($user->email)->send(new AnnouncementMail($judul, $pesan));
        } catch (\Exception $e) {
            \Log::error("Gagal kirim welcome email: " . $e->getMessage());
        }

        event(new Registered($user));

        Auth::login($user);

        // --- INI BAGIAN YANG KITA UBAH ---
        // Cek apakah pengguna yang baru daftar adalah admin
        if ($user->is_admin) {
            // Jika admin, arahkan ke dashboard
            return redirect(route('dashboard', absolute: false));
        }

        // Jika user biasa, arahkan ke halaman utama (landing)
        return redirect(route('landing', absolute: false));
        // --- AKHIR PERUBAHAN ---
    }
}