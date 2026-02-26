@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 py-12 pt-24">

    <!-- Judul Halaman -->
    <section class="text-center mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-800">Jelajahi Semua Kursus Kami</h1>
        <p class="text-gray-600 mt-2">Temukan kursus yang tepat untuk memulai atau melanjutkan perjalanan belajar Anda.</p>
    </section>

    <!-- === BAGIAN TAB KATEGORI BARU === -->
    <section class="mb-10 overflow-x-auto pb-4">
        <div class="flex space-x-4 border-b border-gray-200 max-w-xl mx-auto justify-center">
            {{-- Loop melalui kategori yang dikirim dari controller --}}
            @foreach ($displayCategories as $category)
                @php
                    // Buat slug kategori untuk URL (misal: "Data Science & Analytics" -> "Data Science & Analytics")
                    // Untuk 'Semua', slugnya kosong
                    $categorySlug = ($category === 'Semua') ? null : $category;

                    // Cek apakah tab ini adalah tab yang sedang aktif
                    $isActive = ($category === $activeCategory);
                @endphp

                {{-- Link untuk setiap tab kategori --}}
                <a href="{{ route('courses.course', ['category' => $categorySlug]) }}"
                   class="px-4 py-2 text-sm font-medium whitespace-nowrap
                          {{ $isActive ? 'border-b-2 border-teal-500 text-teal-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    {{ $category }}
                </a>
            @endforeach
        </div>
    </section>
    <!-- === AKHIR BAGIAN TAB KATEGORI === -->

    <!-- Daftar Kursus -->
    <section class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            {{-- Sekarang $courses sudah berisi kursus yang difilter oleh controller --}}
            @forelse ($courses as $course)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 flex flex-col border border-gray-200">
                    <img src="{{ $course['thumbnail'] }}" alt="Thumbnail {{ $course['title'] }}" class="w-full h-48 object-cover">
                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $course['title'] }}</h3>
                        <p class="text-gray-600 text-sm mb-4 flex-grow">{{ $course['description'] }}</p>
                        <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-100">
                            @if ($course['is_free'])
                                <span class="text-2xl font-bold text-teal-500">Gratis</span>
                            @else
                                <span class="text-2xl font-bold text-indigo-600">Rp{{ number_format($course['price'], 0, ',', '.') }}</span>
                            @endif
                            <a href="{{ route('courses.show', $course['slug']) }}" class="px-4 py-2 bg-green-500 text-white text-sm rounded-md hover:bg-green-600 font-semibold transition duration-300">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                {{-- Pesan jika tidak ada kursus di kategori ini --}}
                <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center text-gray-500 py-10">
                    <p>Belum ada kursus dalam kategori ini.</p>
                </div>
            @endforelse

        </div>
    </section>

</div>
@endsection