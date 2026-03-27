@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 text-gray-800 pt-24 pb-16 font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-10">
            <h1 class="text-3xl md:text-5xl font-bold mb-4 font-mono tracking-wide text-gray-900">Jelajahi Semua Kursus</h1>
            <p class="text-gray-600 max-w-2xl mx-auto text-sm md:text-base">
                Temukan kursus yang tepat untuk memulai atau melanjutkan perjalanan belajar Anda. Dari pemula hingga mahir, kami siapkan semuanya.
            </p>
        </div>

        @php
            // Memastikan variabel activeCategory ada, default ke 'Semua'
            $activeCat = $activeCategory ?? request('category', 'Semua');
            $searchKey = request('search', '');
        @endphp

        <form action="{{ route('courses.course') }}" method="GET" class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-4 mb-12">
            
            <button type="submit" class="hidden"></button>

            <input type="hidden" name="category" id="categoryInput" value="{{ $activeCat === 'Semua' ? '' : $activeCat }}">

            <div class="relative w-full md:w-64 group">
                <button type="submit" class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 hover:text-indigo-600 transition cursor-pointer z-10">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
                
                <input type="text" name="search" value="{{ $searchKey }}" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition shadow-sm" placeholder="Cari kursus...">
            </div>

            <div class="flex overflow-x-auto pb-2 md:pb-0 space-x-3 w-full scrollbar-hide">
                @foreach ($displayCategories as $category)
                    @php
                        // Value untuk input hidden. Jika 'Semua', kosongkan value-nya
                        $categoryValue = ($category === 'Semua') ? '' : $category;
                        $isActive = ($activeCat === $category);
                    @endphp

                    <button type="button" 
                            onclick="document.getElementById('categoryInput').value = '{{ $categoryValue }}'; document.querySelector('input[name=search]').value = ''; this.form.submit();"
                            class="px-5 py-1.5 rounded-full border text-sm font-medium whitespace-nowrap transition duration-200 
                            {{ $isActive 
                                ? 'border-indigo-600 bg-indigo-600 text-white shadow-md' 
                                : 'border-gray-300 bg-white text-gray-600 hover:border-gray-400 hover:text-gray-800 hover:bg-gray-50' }}">
                        {{ $category }}
                    </button>
                @endforeach
            </div>
        </form>
        @if (count($courses) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($courses as $course)
                    <a href="{{ route('courses.show', $course['slug']) }}" class="group flex flex-col bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-indigo-300 hover:shadow-lg transition duration-300 h-full">
                        
                        <div class="relative h-48 w-full bg-gray-100">
                            <img src="{{ $course['thumbnail'] }}" alt="Thumbnail {{ $course['title'] }}" class="w-full h-full object-cover">
                            
                            @if ($course['is_free'])
                                <div class="absolute top-3 right-3 bg-green-500 text-white text-xs font-black px-3 py-1 rounded-full shadow-md uppercase tracking-wide">Gratis</div>
                            @endif
                        </div>

                        <div class="p-5 flex-1 flex flex-col border-t border-gray-50">
                            <span class="text-[10px] font-bold tracking-widest text-indigo-500 uppercase mb-2">Kursus Utama</span>
                            
                            <h3 class="text-lg font-bold text-gray-800 group-hover:text-indigo-600 transition-colors mb-2 line-clamp-2">
                                {{ $course['title'] }}
                            </h3>
                            
                            <p class="text-gray-500 text-sm flex-grow line-clamp-2 mb-4">
                                {{ $course['description'] }}
                            </p>

                            <div class="mt-auto pt-4 border-t border-gray-100 flex justify-between items-center">
                                <div>
                                    @if ($course['is_free'])
                                        <span class="text-lg font-black text-green-500">FREE</span>
                                    @else
                                        <span class="text-lg font-bold text-gray-900">Rp{{ number_format($course['price'], 0, ',', '.') }}</span>
                                    @endif
                                </div>
                                <span class="text-sm font-semibold text-indigo-600 group-hover:text-indigo-800 transition-colors">
                                    Lihat Detail &rarr;
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-xl border border-gray-200 shadow-sm">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <h3 class="text-xl font-medium text-gray-800 mb-2">Kursus Tidak Ditemukan</h3>
                <p class="text-gray-500">Kami tidak menemukan kursus yang sesuai dengan pencarian atau kategori ini.</p>
                
                @if(request('search') || (request('category') && request('category') !== 'Semua'))
                    <a href="{{ route('courses.course') }}" class="mt-4 inline-block px-5 py-2 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700 transition shadow-sm">
                        Reset Pencarian
                    </a>
                @endif
            </div>
        @endif

    </div>
</div>

<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endsection