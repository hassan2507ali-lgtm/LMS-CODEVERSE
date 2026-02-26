@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 py-12 pt-24">
    <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg max-w-4xl mx-auto border border-gray-200">

        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-r-lg shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="mb-6">
            <a href="{{ route('courses.course') }}" class="inline-flex items-center text-teal-500 hover:text-teal-700 font-semibold transition duration-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar Kursus
            </a>
        </div>

        <img src="{{ $course->thumbnail ?? 'https://placehold.co/600x400/cccccc/ffffff?text=No+Image' }}" alt="Thumbnail {{ $course->title }}" class="w-full h-64 md:h-80 object-cover rounded-lg mb-6 shadow-sm">
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-2">{{ $course->title }}</h1>

        <div class="mb-6">
            @if ($course->is_free || $course->price == 0)
                <span class="text-3xl font-bold text-teal-500">Gratis</span>
            @else
                <span class="text-3xl font-bold text-indigo-600">Rp{{ number_format($course->price, 0, ',', '.') }}</span>
            @endif
        </div>

        @php
            $isEnrolled = Auth::check() ? \App\Models\Enrollment::where('user_id', Auth::id())->where('course_id', $course->id)->exists() : false;
        @endphp

        <div class="mb-8">
            @if ($isEnrolled)
                <button class="w-full block text-center px-8 py-3 bg-gray-400 text-white rounded-md text-lg font-semibold cursor-not-allowed">
                    Kamu Sudah Terdaftar di Kelas Ini
                </button>
            @else
                @auth
                    <form action="{{ route('checkout.process', $course->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full block text-center px-8 py-3 bg-green-500 text-white rounded-md hover:bg-green-600 text-lg font-semibold transition duration-300">
                            {{ ($course->is_free || $course->price == 0) ? 'Daftar Kelas Gratis' : 'Beli Kelas Sekarang' }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="w-full block text-center px-8 py-3 bg-indigo-500 text-white rounded-md hover:bg-indigo-600 text-lg font-semibold transition duration-300">
                        Login untuk Mendaftar
                    </a>
                @endauth
            @endif
        </div>

        <div>
            <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b border-gray-200 pb-2">Deskripsi Kursus</h2>
            <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                {{ $course->description }}
            </p>
        </div>

      
        <div class="mt-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b border-gray-200 pb-2">Materi Pembelajaran</h2>
            <div class="space-y-6">
                @forelse ($course->modules as $module)
                <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
                    <div class="flex items-center p-4 bg-gray-100 border-b border-gray-200">
                        <svg class="w-6 h-6 text-indigo-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <h3 class="text-lg font-semibold text-gray-800">{{ $module->title }}</h3>
                    </div>

                    <ul class="divide-y divide-gray-200">
                        @forelse ($module->lessons as $lesson)
                        <li class="flex items-center justify-between p-4 hover:bg-gray-100 transition duration-150">
                            <div class="flex items-center">
                                @if ($lesson->content_type == 'video')
                                    <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                @elseif ($lesson->content_type == 'text')
                                    <svg class="w-5 h-5 text-blue-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                @else 
                                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                @endif
                                <span class="text-gray-700">{{ $lesson->title }}</span>
                            </div>
                            
                            @if ($isEnrolled)
                                <a href="{{ route('courses.learn', ['slug' => $course->slug, 'lesson' => $lesson->id]) }}" class="text-xs px-3 py-1 bg-teal-100 text-teal-700 rounded-full hover:bg-teal-200 font-semibold uppercase tracking-wider transition">Mulai Belajar</a>
                            @else
                                <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider cursor-not-allowed">Terkunci</span>
                            @endif
                        </li>
                        @empty
                        <li class="px-4 py-3 text-gray-500 italic text-sm">Belum ada pelajaran di modul ini.</li>
                        @endforelse
                    </ul>
                </div>
                @empty
                <p class="text-gray-500 italic">Belum ada modul untuk kursus ini.</p>
                @endforelse
            </div>
        </div>
        </div>
</div>
@endsection