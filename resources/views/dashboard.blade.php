@extends('layouts.app')

@section('content')

<style>
    footer, .footer, #footer { display: none !important; }
</style>

<div class="min-h-screen bg-slate-50 flex pt-16">

    {{-- ========================================== --}}
    {{-- 🔥 SIDEBAR ADMIN PINTAR --}}
    {{-- ========================================== --}}
    <aside class="w-64 bg-white border-r border-gray-200 shadow-sm hidden md:flex flex-col fixed h-[calc(100vh-4rem)] z-10">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Ruang Kendali</h2>
            <p class="text-lg font-bold text-gray-800 font-mono">Admin Panel</p>
        </div>

        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
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
        </nav>
    </aside>

    {{-- ========================================== --}}
    {{-- 🔥 MAIN CONTENT (Kanan) --}}
    {{-- ========================================== --}}
    <main class="flex-1 md:ml-64 p-8">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Selamat datang, Admin! 👋</h1>
            <p class="text-gray-500 mt-1">Ini adalah ringkasan platform Code Verse hari ini.</p>
        </div>

        {{-- WIDGET KARTU STATISTIK (Real-Time Data) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            {{-- Kartu 1: Total Kursus --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Total Kursus</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $courses->count() ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
            </div>

            {{-- Kartu 2: Total Practice --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Total Practice</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ \App\Models\Practice::count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-teal-50 text-teal-500 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                </div>
            </div>

            {{-- Kartu 3: Pendapatan --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Pendapatan</p>
                    <h3 class="text-3xl font-bold text-gray-800">
                        <span class="text-lg text-gray-400">Rp</span> 
                        {{ number_format(\App\Models\Transaction::where('status', 'success')->sum('amount'), 0, ',', '.') }}
                    </h3>
                </div>
                <div class="w-12 h-12 bg-green-50 text-green-500 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        {{-- TABEL KELOLA KURSUS --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-10">
            
            {{-- Header Tabel --}}
            <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Kelola Kursus</h2>
                    <p class="text-sm text-gray-500 mt-1">Daftar semua kursus video yang tersedia di platform.</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center px-4 py-2.5 bg-teal-500 hover:bg-teal-600 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Kursus
                    </a>
                </div>
            </div>

            {{-- Isi Tabel --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                            <th class="px-6 py-4 font-medium">Judul Kursus</th>
                            <th class="px-6 py-4 font-medium">Harga</th>
                            <th class="px-6 py-4 font-medium">Status</th>
                            <th class="px-6 py-4 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($courses as $course)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-800">{{ $course->title }}</div>
                                    <div class="text-xs text-gray-400 font-mono mt-0.5">{{ $course->slug }}</div>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-600">
                                    @if($course->price > 0)
                                        Rp {{ number_format($course->price, 0, ',', '.') }}
                                    @else
                                        <span class="text-green-600 font-bold">Gratis</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($course->price > 0)
                                        <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-xs font-bold rounded-full border border-indigo-100">Berbayar</span>
                                    @else
                                        <span class="px-3 py-1 bg-green-50 text-green-600 text-xs font-bold rounded-full border border-green-100">Gratis</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 flex items-center justify-end gap-4">
                                    <a href="{{ route('admin.courses.content', $course->id) }}" class="text-sm font-medium text-teal-600 hover:text-teal-800 transition">Manage Content</a>
                                    <a href="{{ route('admin.courses.edit', $course->id) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 transition">Edit</a>
                                    
                                    <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kursus ini secara permanen?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800 transition">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                    Belum ada kursus. Silakan tambah kursus baru!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
        </div>
        
    </main>
</div>
@endsection