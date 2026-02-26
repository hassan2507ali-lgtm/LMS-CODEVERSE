@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 py-12 pt-24">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Kelas Saya</h1>
        <p class="text-gray-600 mb-8">Lanjutkan proses belajarmu dan raih mimpimu.</p>

        @if ($enrollments->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($enrollments as $enrollment)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 hover:shadow-lg transition duration-300">
                        <img src="{{ $enrollment->course->thumbnail ?? 'https://placehold.co/600x400/cccccc/ffffff?text=No+Image' }}" alt="{{ $enrollment->course->title }}" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h2 class="text-xl font-bold text-gray-800 mb-2 line-clamp-2">{{ $enrollment->course->title }}</h2>
                            <p class="text-sm text-gray-500 mb-4">Terdaftar pada: {{ $enrollment->created_at->format('d M Y') }}</p>
                            
                            <a href="{{ route('courses.show', $enrollment->course->slug) }}" class="block w-full text-center px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition duration-300">
                                Lanjut Belajar
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                <h3 class="text-xl font-medium text-gray-800 mb-2">Kamu belum memiliki kelas</h3>
                <p class="text-gray-500 mb-6">Ayo jelajahi katalog kami dan mulai petualangan belajarmu hari ini!</p>
                <a href="{{ route('courses.course') }}" class="inline-block px-6 py-3 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition duration-300">
                    Cari Kelas
                </a>
            </div>
        @endif

    </div>
</div>
@endsection