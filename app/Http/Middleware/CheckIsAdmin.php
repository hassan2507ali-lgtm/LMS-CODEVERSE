<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    // Cek: Apakah pengguna sudah login DAN apakah dia admin (is_admin == true)
    if (auth()->check() && auth()->user()->is_admin) {
        // Jika ya, izinkan lanjut ke halaman yang dituju (misal: /dashboard)
        return $next($request);
    }

    // Jika tidak, usir dia. Redirect ke halaman utama.
    return redirect('/');
    // Atau tampilkan halaman "Akses Ditolak"
    // abort(403, 'Akses Ditolak: Anda bukan Admin.');
}
}
