@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 py-12 pt-24"> {{-- pt-24 agar tidak tertutup navbar fixed --}}

    <!-- Judul Halaman & Search Bar -->
    <section class="mb-10 text-center">
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-4">Explore Practice Projects</h1>
        <p class="text-gray-600 mb-6 max-w-2xl mx-auto">Explore dozens of easy-to-follow code walkthroughs designed to help you get started on your next project.</p>
        {{-- Form Pencarian --}}
        <form action="{{ route('practice.index') }}" method="GET" class="max-w-xl mx-auto">
            <div class="relative">
                <input type="search" name="search" placeholder="Search projects..."
                       value="{{ $searchQuery ?? '' }}" {{-- Menampilkan query pencarian sebelumnya --}}
                       class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
            {{-- Jika ada filter aktif, tambahkan sebagai input hidden agar tidak hilang saat search --}}
            @if (isset($activeFilter) && $activeFilter !== 'All')
                <input type="hidden" name="filter" value="{{ $activeFilter }}">
            @endif
        </form>
    </section>

    <!-- Filter Kategori/Tag -->
    <section class="mb-10 overflow-x-auto pb-4">
        <div class="flex space-x-2 border-b border-gray-200 max-w-3xl mx-auto justify-center flex-wrap">
             {{-- Loop melalui filter yang dikirim dari controller --}}
             @foreach ($allFilters as $filter)
                @php
                    $filterSlug = ($filter === 'All') ? null : $filter;
                    $isActive = ($filter === $activeFilter);
                @endphp
                 {{-- Link untuk setiap filter --}}
                 <a href="{{ route('practice.index', ['filter' => $filterSlug, 'search' => $searchQuery ?? null]) }}" {{-- Pertahankan search query saat ganti filter --}}
                    class="px-3 py-2 text-sm font-medium rounded-t-md whitespace-nowrap mb-[-1px] {{-- mb-[-1px] untuk efek border bawah --}}
                           {{ $isActive ? 'border-b-2 border-teal-500 text-teal-600 bg-white' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                     {{ $filter }}
                 </a>
             @endforeach
        </div>
    </section>

    <!-- Daftar Proyek Latihan -->
    <section class="max-w-7xl mx-auto">
        {{-- Menampilkan judul dinamis berdasarkan filter --}}
        <h2 class="text-2xl font-semibold text-gray-700 mb-6">
            @if(isset($activeFilter) && $activeFilter !== 'All')
                Projects in: <span class="text-teal-600">{{ $activeFilter }}</span>
            @elseif(isset($searchQuery) && $searchQuery)
                Search results for: <span class="text-teal-600">"{{ $searchQuery }}"</span>
            @else
                All Projects
            @endif
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            {{-- Loop melalui $filteredPractices --}}
            @forelse ($filteredPractices as $practice)
                {{-- BUNGKUS DENGAN LINK ke halaman detail --}}
                <a href="{{ route('practice.show', $practice['slug']) }}" class="block group">
                    {{-- DIV KARTU SEKARANG DI DALAM LINK --}}
                    <div class="bg-white rounded-lg shadow-md overflow-hidden group-hover:shadow-lg transition-shadow duration-300 border border-gray-100 flex flex-col h-full">
                        <img src="{{ $practice['thumbnail'] }}" alt="Thumbnail {{ $practice['title'] }}" class="w-full h-40 object-cover">
                        <div class="p-4 flex flex-col flex-grow">
                            <p class="text-xs text-gray-500 uppercase mb-1">Tutorial</p>
                            <h3 class="text-md font-semibold text-gray-800 mb-3 flex-grow group-hover:text-teal-600 transition-colors">{{ $practice['title'] }}</h3>
                            {{-- Menampilkan Tags --}}
                            <div class="flex flex-wrap gap-2 mt-auto pt-3 border-t border-gray-100">
                                @foreach ($practice['tags'] as $tag)
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-full font-medium">{{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                {{-- Pesan jika tidak ada hasil --}}
                <div class="col-span-1 sm:col-span-2 lg:col-span-4 text-center text-gray-500 py-16">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-lg">No practice projects found.</p>
                    @if(isset($searchQuery) && $searchQuery || isset($activeFilter) && $activeFilter !== 'All')
                        <p class="text-sm mt-2">Try adjusting your search or filter.</p>
                        <a href="{{ route('practice.index') }}" class="mt-4 inline-block text-teal-600 hover:underline">Clear Filters & Search</a>
                    @endif
                </div>
            @endforelse

        </div>
    </section>

</div>
@endsection
