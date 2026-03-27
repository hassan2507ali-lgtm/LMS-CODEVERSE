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
    {{-- 🔥 MAIN CONTENT --}}
    {{-- ========================================== --}}
    <main class="flex-1 md:ml-64 p-8">
        
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Kelola Practice 💻</h1>
                <p class="text-gray-500 mt-1">Buat dan atur materi latihan studi kasus interaktif untuk siswa.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm text-green-700 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md shadow-sm text-red-700 text-sm font-medium">
                {{ session('error') }}
            </div>
        @endif

        {{-- TABEL KELOLA PRACTICE --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-10">
            
            {{-- Header Tabel & Tombol Aksi --}}
            <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gray-50/50">
                <h2 class="text-lg font-bold text-gray-800">Daftar Materi Latihan</h2>
                
                <div class="flex gap-3">
                    <a href="{{ route('admin.practice.create') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-semibold rounded-lg shadow-sm transition">
                        + Tambah Manual
                    </a>
                    
                    <button onclick="document.getElementById('aiPracticeModal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:shadow-lg hover:opacity-90 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Generate dengan AI ✨
                    </button>
                </div>
            </div>

            {{-- Isi Tabel --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white border-b border-gray-100 text-gray-500 text-xs uppercase tracking-wider">
                            <th class="px-6 py-4 font-medium">Judul Practice</th>
                            <th class="px-6 py-4 font-medium">Kategori</th>
                            <th class="px-6 py-4 font-medium">Harga / Akses</th>
                            <th class="px-6 py-4 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($practices as $practice)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-800 text-base">{{ $practice->title }}</div>
                                    <div class="text-xs text-gray-400 mt-1 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        {{ $practice->exercises()->count() }} Exercises
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-md border border-gray-200">
                                        {{ $practice->category }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if(!$practice->is_free && $practice->price > 0)
                                        <div class="text-sm font-bold text-gray-800">Rp {{ number_format($practice->price, 0, ',', '.') }}</div>
                                        <div class="text-[11px] text-gray-500 mt-0.5">{{ $practice->free_exercises_count }} Soal Gratis</div>
                                    @else
                                        <span class="px-3 py-1 bg-green-50 text-green-600 text-xs font-bold rounded-full border border-green-100">Full Gratis</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.practice.exercises.manage', $practice->id) }}" class="px-3 py-1.5 bg-teal-50 text-teal-600 hover:bg-teal-500 hover:text-white rounded-md text-sm font-medium transition">Kelola Soal</a>
                                    <a href="{{ route('admin.practice.edit', $practice->id) }}" class="text-sm font-medium text-blue-500 hover:text-blue-700 transition">Edit Info</a>
                                    
                                    <form action="{{ route('admin.practice.destroy', $practice->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus materi latihan ini beserta seluruh soalnya?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm font-medium text-red-500 hover:text-red-700 transition">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-200">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    </div>
                                    <p class="text-gray-500 font-medium mb-1">Belum ada materi Practice.</p>
                                    <p class="text-gray-400 text-sm">Gunakan tombol AI untuk membuat materi pertama kamu dengan instan!</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
    </main>
</div>

{{-- 🔥 MODAL AI GENERATE MATERI UTAMA --}}
<div id="aiPracticeModal" class="fixed inset-0 z-50 hidden bg-gray-900/60 backdrop-blur-sm overflow-y-auto h-full w-full flex items-center justify-center">
    <div class="relative mx-auto p-8 border w-full max-w-lg shadow-2xl rounded-2xl bg-white">
        
        <div class="text-center mb-6">
            <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 font-mono">AI Practice Generator ✨</h3>
            <p class="text-sm text-gray-500 mt-2">Masukkan satu topik, dan biarkan AI membuatkan silabus lengkap beserta studi kasus dari level dasar hingga menengah.</p>
        </div>

        <form action="{{ route('admin.practice.generate-ai') }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Topik Materi Latihan</label>
                <input type="text" name="topic" required placeholder="Contoh: Belajar Python untuk Data Science" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 shadow-sm text-gray-800">
            </div>

            <div class="flex items-center gap-3 mt-8">
                <button type="button" onclick="document.getElementById('aiPracticeModal').classList.add('hidden')" class="w-1/2 px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition font-medium">Batal</button>
                <button type="submit" onclick="this.innerHTML='Generating... ⏳'; this.classList.add('opacity-70', 'cursor-not-allowed');" class="w-1/2 px-4 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:shadow-lg transition font-medium">Generate Silabus 🚀</button>
            </div>
        </form>
    </div>
</div>
@endsection