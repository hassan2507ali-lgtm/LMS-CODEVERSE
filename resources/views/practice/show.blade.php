@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 py-12 pt-24"> {{-- pt-24 agar tidak tertutup navbar fixed --}}
    <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg max-w-4xl mx-auto border border-gray-200">

        <!-- Tombol Kembali -->
        <div class="mb-6">
            {{-- Link kembali ke halaman daftar practice --}}
            <a href="{{ route('practice.index') }}" class="inline-flex items-center text-teal-500 hover:text-teal-700 font-semibold transition duration-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar Practice
            </a>
        </div>

        <!-- Gambar Thumbnail & Judul -->
        <img src="{{ $practice['thumbnail'] }}" alt="Thumbnail {{ $practice['title'] }}" class="w-full h-64 md:h-80 object-cover rounded-lg mb-6 shadow-sm">
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-4">{{ $practice['title'] }}</h1>

         <!-- Tags -->
        <div class="flex flex-wrap gap-2 mb-6">
            @foreach ($practice['tags'] as $tag)
                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full font-medium">{{ $tag }}</span>
            @endforeach
        </div>

        <!-- Tombol Mulai (Contoh) -->
        <div class="mb-8">
            {{-- Tombol ini belum berfungsi, hanya tampilan --}}
            <a href="#" class="w-full block text-center px-8 py-3 bg-green-500 text-white rounded-md hover:bg-green-600 text-lg font-semibold transition duration-300">
                Mulai Latihan Ini
            </a>
        </div>

        <!-- Detail Latihan (Dari data palsu di Controller) -->
        <div>
            <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b border-gray-200 pb-2">Tentang Latihan Ini</h2>
            <div class="space-y-4 text-gray-700">
                <div>
                    <h3 class="font-semibold text-gray-800">Tujuan:</h3>
                    <p>{{ $practice['details']['goal'] }}</p>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Estimasi Waktu:</h3>
                    <p>{{ $practice['details']['estimated_time'] }}</p>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Langkah-langkah Umum:</h3>
                    <ul class="list-disc list-inside space-y-1 ml-4">
                        @foreach ($practice['details']['steps'] as $step)
                            <li>{{ $step }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection