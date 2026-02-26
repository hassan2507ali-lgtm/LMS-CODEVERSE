@extends('layouts.app')

@section('content')
<div class="py-12 pt-24">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <!-- Tombol Kembali ke Dashboard -->
        <div class="mb-4">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-teal-500 hover:text-teal-700 font-semibold transition duration-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Dashboard
            </a>
        </div>

        <!-- Judul Halaman -->
        <h2 class="text-3xl font-semibold mb-6 text-gray-800">
            Kelola Konten: <span class="font-bold text-indigo-600">{{ $course->title }}</span>
        </h2>

        <!-- Pesan Sukses (jika ada) -->
        @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Validasi (jika ada) -->
        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <!-- Form Tambah Modul Baru (Sudah Berfungsi) -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 mb-6">
            <div class="p-6 text-gray-900">
                <h3 class="text-xl font-semibold mb-4">Tambah Modul Baru ("Bab" Baru)</h3>
                <form action="{{ route('admin.modules.store', $course->id) }}" method="POST">
                    @csrf
                    <div class="flex items-center space-x-4">
                        <div class="flex-grow">
                            <label for="title" class="sr-only">Judul Modul</label>
                            <input type="text" name="title" id="title" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" placeholder="Contoh: Modul 1 - Pengenalan" required>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 font-semibold">
                            Simpan Modul
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Daftar Modul & Lesson yang Sudah Ada -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
            <div class="p-6 text-gray-900">
                <h3 class="text-xl font-semibold mb-4">Daftar Materi Pembelajaran</h3>
                <div class="space-y-6">

                    {{-- Loop melalui modul ($course->modules sudah di-load oleh Controller) --}}
                    @forelse ($course->modules as $module)
                    <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
                        {{-- Judul Modul --}}
                        <div class="flex items-center justify-between p-4 bg-gray-100 border-b border-gray-200">
                            <h4 class="text-lg font-semibold text-gray-800">{{ $module->title }}</h4>
                            <div class="flex space-x-2">
                                {{-- Tombol Edit Modul (Sudah Berfungsi) --}}
                                <a href="{{ route('admin.modules.edit', $module->id) }}" class="text-xs text-indigo-600 hover:text-indigo-900 font-semibold">EDIT</a>
                                
                                {{-- Form Hapus Modul (Sudah Berfungsi) --}}
                                <form action="{{ route('admin.modules.destroy', $module->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus modul ini? SEMUA lesson di dalamnya akan ikut terhapus!');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:text-red-900 font-semibold">HAPUS</button>
                                </form>
                            </div>
                        </div>

                        {{-- Daftar Lesson di dalam Modul ($module->lessons sudah di-load) --}}
                        <ul class="divide-y divide-gray-200">
                            @forelse ($module->lessons as $lesson)
                            <li class="flex items-center justify-between p-3 pl-10">
                                <span class="text-gray-700 text-sm">{{ $lesson->title }} ({{ $lesson->content_type }})</span>
                                <div class="flex space-x-2">
                                    
                                    {{-- Link Edit Lesson (Sudah Berfungsi) --}}
                                    <a href="{{ route('admin.lessons.edit', $lesson->id) }}" class="text-xs text-indigo-600 hover:text-indigo-900 font-semibold">Edit</a>
                                    
                                    {{-- INI BAGIAN YANG DIPERBAIKI (HAPUS LESSON) --}}
                                    <form action="{{ route('admin.lessons.destroy', $lesson->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Anda yakin ingin menghapus lesson ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 hover:text-red-900 font-semibold">Hapus</button>
                                    </form>

                                </div>
                            </li>
                            @empty
                            <li class="px-4 py-3 text-gray-500 italic text-sm pl-10">Belum ada pelajaran di modul ini.</li>
                            @endforelse
                        </ul>

                        {{-- Form Tambah Lesson Baru (Sudah Berfungsi) --}}
                        <div class="p-4 bg-gray-100 border-t border-gray-200">
                            <form action="{{ route('admin.lessons.store', $module->id) }}" method="POST" class="flex items-center space-x-2">
                                @csrf
                                <input type="text" name="lesson_title" class="flex-grow px-3 py-1.5 border border-gray-300 rounded-md text-sm" placeholder="Tambah lesson baru..." required>
                                <button type="submit" class="px-3 py-1.5 bg-indigo-500 text-white rounded-md hover:bg-indigo-600 text-sm font-semibold">Simpan Lesson</button>
                            </form>
                        </div>

                    </div>
                    @empty
                    <p class="text-gray-500 italic">Belum ada modul untuk kursus ini.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection