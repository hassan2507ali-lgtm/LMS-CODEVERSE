@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 py-12 pt-24">
    <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg max-w-4xl mx-auto border border-gray-200">

        <!-- Tombol Kembali -->
        <div class="mb-6">
            <a href="{{ route('courses.course') }}" class="inline-flex items-center text-teal-500 hover:text-teal-700 font-semibold transition duration-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar Kursus
            </a>
        </div>

        <!-- Gambar Thumbnail & Judul -->
        <img src="{{ $course->thumbnail ?? 'https://placehold.co/600x400/cccccc/ffffff?text=No+Image' }}" alt="Thumbnail {{ $course->title }}" class="w-full h-64 md:h-80 object-cover rounded-lg mb-6 shadow-sm">
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-2">{{ $course->title }}</h1>

        <!-- Harga -->
        <div class="mb-6">
            @if ($course->is_free)
                <span class="text-3xl font-bold text-teal-500">Gratis</span>
            @else
                <span class="text-3xl font-bold text-indigo-600">Rp{{ number_format($course->price, 0, ',', '.') }}</span>
            @endif
        </div>

        <!-- Tombol Aksi -->
        <div class="mb-8">
            <a href="#" class="w-full block text-center px-8 py-3 bg-green-500 text-white rounded-md hover:bg-green-600 text-lg font-semibold transition duration-300">
                Daftar Kursus Ini
            </a>
        </div>

        <!-- Deskripsi Lengkap Kursus -->
        <div>
            <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b border-gray-200 pb-2">Deskripsi Kursus</h2>
            <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                {{ $course->description }} {{-- Atau gunakan kolom deskripsi panjang jika ada --}}
            </p>
        </div>

        <!-- === BAGIAN DAFTAR MODUL (DIPERBARUI) === -->
        <div class="mt-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b border-gray-200 pb-2">Materi Pembelajaran</h2>
            <ul class="space-y-3">
                {{-- Loop melalui relasi $course->modules --}}
                {{-- Menggunakan @forelse untuk menangani jika tidak ada modul --}}
                @forelse ($course->modules as $module)
                <li class="flex items-center p-4 bg-gray-50 rounded-md border border-gray-200">
                    <svg class="w-6 h-6 text-teal-500 mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{-- Akses title sebagai properti objek --}}
                    <span class="text-gray-800">{{ $module->title }}</span>
                    {{-- Nanti bisa ditambahkan link ke lesson atau detail modul --}}
                </li>
                @empty
                {{-- Tampil jika $course->modules kosong --}}
                <li class="text-gray-500 italic">Belum ada modul untuk kursus ini.</li>
                @endforelse
            </ul>
        </div>
        <!-- === AKHIR BAGIAN DAFTAR MODUL === -->

    </div>
</div>
@endsection