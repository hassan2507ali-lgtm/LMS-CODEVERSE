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
                <a href="{{ route('practice.show', $practice->slug) }}" class="block group">
                    {{-- DIV KARTU SEKARANG DI DALAM LINK --}}
                    <div class="bg-white rounded-lg shadow-md overflow-hidden group-hover:shadow-lg transition-shadow duration-300 border border-gray-100 flex flex-col h-full">
                        {{-- Display GitHub image or fallback --}}
                        <div class="relative">
                            <img src="{{ $practice->image_url }}" 
                                 alt="Thumbnail {{ $practice->title }}" 
                                 class="w-full h-40 object-cover"
                                 onerror="this.src='https://placehold.co/600x400/8b5cf6/ffffff?text={{ urlencode($practice->title) }}'">
                            
                            {{-- GitHub badge if github_link exists --}}
                            @if($practice->github_link)
                                <div class="absolute top-2 right-2 bg-gray-900 bg-opacity-80 text-white px-2 py-1 rounded-md text-xs flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                    </svg>
                                    <span>GitHub</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-4 flex flex-col flex-grow">
                            <p class="text-xs text-gray-500 uppercase mb-1">Tutorial</p>
                            <h3 class="text-md font-semibold text-gray-800 mb-3 flex-grow group-hover:text-teal-600 transition-colors">{{ $practice->title }}</h3>
                            
                            {{-- Display category badge --}}
                            @if($practice->category)
                                <div class="mb-2">
                                    <span class="px-2 py-1 bg-teal-100 text-teal-700 text-xs rounded-md font-medium">
                                        {{ $practice->category }}
                                    </span>
                                </div>
                            @endif
                            
                            {{-- Menampilkan Tags --}}
                            @if($practice->tags && count($practice->tags) > 0)
                                <div class="flex flex-wrap gap-2 mt-auto pt-3 border-t border-gray-100">
                                    @foreach ($practice->tags as $tag)
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-full font-medium">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            @endif
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
