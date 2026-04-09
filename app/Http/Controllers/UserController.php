<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // 1. Siapkan kerangka query
        $query = User::query();

        // 2. Jika ada pencarian (Nama atau Email)
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // 3. Jika ada filter Role (Admin / User)
        if ($request->filled('role')) {
            if ($request->role == 'admin') {
                $query->where('is_admin', true);
            } elseif ($request->role == 'user') {
                $query->where('is_admin', false);
            }
        }

        // 4. Eksekusi dengan Pagination (10 data per halaman)
        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function toggleAdmin(User $user)
    {
        // Keamanan: Jangan biarkan admin mengubah status dirinya sendiri
        if (Auth::id() === $user->id) {
            return back()->with('error', 'Akses ditolak! Anda tidak dapat mengubah status admin akun Anda sendiri.');
        }

        $user->update(['is_admin' => !$user->is_admin]);
        
        $status = $user->is_admin ? 'diangkat menjadi Admin' : 'diturunkan menjadi User Biasa';
        return back()->with('success', "Berhasil! Pengguna {$user->name} telah {$status}.");
    }

    public function destroy(User $user)
    {
        // Keamanan: Jangan biarkan admin menghapus dirinya sendiri
        if (Auth::id() === $user->id) {
            return back()->with('error', 'Akses ditolak! Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return back()->with('success', "Akun {$user->name} berhasil dihapus secara permanen.");
    }
}