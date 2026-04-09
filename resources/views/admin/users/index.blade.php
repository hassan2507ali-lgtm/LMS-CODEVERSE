@extends('layouts.app')

@section('content')

{{-- 🔥 MANTRA MENGHILANGKAN PADDING & GRADASI BAWAAN LAYOUT KHUSUS ADMIN --}}
<style>
    footer, .footer, #footer { display: none !important; }
    .py-12, .py-8 { padding-top: 0 !important; padding-bottom: 0 !important; }
    body { background-color: #ffffff !important; background-image: none !important; }
</style>

<div class="flex items-start bg-white min-h-screen w-full">

    {{-- 🔥 SIDEBAR ADMIN PINTAR --}}
    <aside class="w-64 bg-white border-r border-gray-200 shadow-sm hidden md:flex flex-col sticky top-0 h-screen z-10 overflow-y-auto">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Ruang Kendali</h2>
            <p class="text-lg font-bold text-gray-800 font-mono">Admin Panel</p>
        </div>

        <nav class="flex-1 p-4 space-y-1">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition {{ request()->routeIs('dashboard') ? 'bg-teal-50 text-teal-600' : 'text-gray-600 hover:bg-gray-50 hover:text-teal-600' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>

            <a href="{{ route('admin.practice.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition {{ request()->routeIs('admin.practice.*') ? 'bg-teal-50 text-teal-600' : 'text-gray-600 hover:bg-gray-50 hover:text-teal-600' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                Kelola Practice
            </a>

            <a href="{{ route('admin.transactions') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition {{ request()->routeIs('admin.transactions') ? 'bg-teal-50 text-teal-600' : 'text-gray-600 hover:bg-gray-50 hover:text-teal-600' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path></svg>
                Data Transaksi
            </a>

            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition {{ request()->routeIs('admin.users.*') ? 'bg-teal-50 text-teal-600' : 'text-gray-600 hover:bg-gray-50 hover:text-teal-600' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Kelola User
            </a>
        </nav>
    </aside>

    {{-- 🔥 MAIN CONTENT --}}
    <main class="flex-1 p-8 min-h-screen">
        
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Kelola User 👥</h1>
                <p class="text-gray-500 mt-1">Pantau, cari, dan atur peran seluruh pengguna platform.</p>
            </div>
            <div class="bg-teal-50 px-4 py-2 rounded-xl border border-teal-100 flex items-center gap-3">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-teal-600 shadow-sm font-bold">
                    {{ $users->total() }}
                </div>
                <span class="text-sm font-bold text-teal-800">Total Pengguna</span>
            </div>
        </div>

        {{-- Notifikasi --}}
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm text-green-700 text-sm font-bold">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm text-red-700 text-sm font-bold">
                {{ session('error') }}
            </div>
        @endif

        {{-- 🔥 FORM PENCARIAN & FILTER --}}
        <form action="{{ route('admin.users.index') }}" method="GET" class="mb-6 bg-gray-50 p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-4 items-end">
            
            <div class="flex-1 w-full">
                <label class="block text-sm font-semibold text-gray-600 mb-1.5">Cari Pengguna</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama atau email..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 outline-none text-sm shadow-sm">
                </div>
            </div>

            <div class="w-full md:w-48">
                <label class="block text-sm font-semibold text-gray-600 mb-1.5">Filter Role</label>
                <select name="role" class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 outline-none text-sm shadow-sm text-gray-600">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin Saja</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User Biasa Saja</option>
                </select>
            </div>

            <div class="flex gap-2 w-full md:w-auto mt-4 md:mt-0">
                <button type="submit" class="px-5 py-2.5 bg-gray-800 text-white rounded-xl hover:bg-gray-900 text-sm font-bold shadow-sm w-full md:w-auto transition">
                    Cari Data
                </button>
                @if(request()->anyFilled(['search', 'role']))
                    <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 bg-white text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-100 text-sm font-bold shadow-sm w-full md:w-auto text-center transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        {{-- TABEL KELOLA USER --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-10">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 text-xs uppercase tracking-wider">
                            <th class="px-6 py-4 font-bold">Profil & Nama</th>
                            <th class="px-6 py-4 font-bold">Email</th>
                            <th class="px-6 py-4 font-bold text-center">Status / Role</th>
                            <th class="px-6 py-4 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($users as $user)
                            <tr class="hover:bg-gray-50/50 transition">
                                
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="relative w-10 h-10">
                                            <div class="w-full h-full rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-bold shadow-sm border border-teal-200">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            @if($user->isOnline())
                                                <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full shadow-sm" title="Online"></span>
                                            @else
                                                <span class="absolute bottom-0 right-0 w-3 h-3 bg-gray-300 border-2 border-white rounded-full shadow-sm" title="Offline"></span>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-800 text-base">{{ $user->name }}</div>
                                            @if($user->isOnline())
                                                <div class="text-[10px] font-bold text-green-500 uppercase tracking-wider">Online</div>
                                            @else
                                                <div class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Offline</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-600 font-medium">
                                    {{ $user->email }}<br>
                                    <span class="text-[10px] text-gray-400">Join: {{ $user->created_at->format('d M Y') }}</span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($user->is_admin)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-50 text-purple-700 text-xs font-bold rounded-full border border-purple-100 shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                            Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-600 text-xs font-bold rounded-full border border-blue-100">
                                            User
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 flex items-center justify-end gap-2">
                                    {{-- Tombol Toggle Admin --}}
                                    @if(Auth::id() !== $user->id)
                                        <form action="{{ route('admin.users.toggle-admin', $user->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-3 py-1.5 text-xs font-bold rounded-lg border {{ $user->is_admin ? 'bg-orange-50 text-orange-600 border-orange-200 hover:bg-orange-100' : 'bg-indigo-50 text-indigo-600 border-indigo-200 hover:bg-indigo-100' }} transition">
                                                {{ $user->is_admin ? 'Turunkan User' : 'Jadikan Admin' }}
                                            </button>
                                        </form>

                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('PERINGATAN! Yakin ingin menghapus pengguna {{ $user->name }} beserta seluruh data transaksinya secara permanen?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white rounded-lg border border-red-100 transition" title="Hapus Akun">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs font-medium text-gray-400 italic">Akun Anda</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center text-gray-500">
                                    Data pengguna tidak ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Links --}}
            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
        
    </main>
</div>
@endsection