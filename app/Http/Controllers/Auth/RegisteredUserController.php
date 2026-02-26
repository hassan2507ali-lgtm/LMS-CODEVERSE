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
            // 'is_admin' akan otomatis 0 (false) karena default di database
        ]);

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