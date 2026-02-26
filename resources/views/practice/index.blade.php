@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 text-gray-800 pt-24 pb-16 font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-10">
            <h1 class="text-3xl md:text-5xl font-bold mb-4 font-mono tracking-wide text-gray-900">Project Tutorials</h1>
            <p class="text-gray-600 max-w-2xl mx-auto text-sm md:text-base">
                Ready to build? Explore dozens of easy-to-follow code walkthroughs designed to help you get started on your next project.
            </p>
        </div>

        @php
            // Memastikan variabel ada, mengambil dari request URL jika tidak ada
            $activeCat = $activeCategory ?? request('category', 'All');
            $searchKey = $searchKeyword ?? request('search', '');
            $categories = ['All', 'Python', 'HTML', 'JavaScript', 'React', 'Lua', 'Data Science'];
        @endphp

        <form action="{{ route('practice.index') }}" method="GET" class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-4 mb-12">
            
            <button type="submit" class="hidden"></button>

            <input type="hidden" name="category" id="categoryInput" value="{{ $activeCat }}">

            <div class="relative w-full md:w-64 group">
                <button type="submit" class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 hover:text-indigo-600 transition cursor-pointer z-10">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
                
                <input type="text" name="search" value="{{ $searchKey }}" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition shadow-sm" placeholder="Search...">
            </div>

            <div class="flex overflow-x-auto pb-2 md:pb-0 space-x-3 w-full scrollbar-hide">
                @foreach($categories as $category)
                    <button type="submit" 
                            onclick="document.getElementById('categoryInput').value = '{{ $category }}'; document.querySelector('input[name=search]').value = '';"
                            class="px-5 py-1.5 rounded-full border text-sm font-medium whitespace-nowrap transition duration-200 
                            {{ $activeCat == $category 
                                ? 'border-indigo-600 bg-indigo-600 text-white shadow-md' 
                                : 'border-gray-300 bg-white text-gray-600 hover:border-gray-400 hover:text-gray-800 hover:bg-gray-50' }}">
                        {{ $category }}
                    </button>
                @endforeach
            </div>
        </form>

        <div class="mb-6">
            <h2 class="text-xl md:text-2xl font-bold flex items-center mb-2 font-mono text-gray-900">
                <span class="mr-2 text-green-500">🌱</span> Beginner-friendly picks
            </h2>
            <p class="text-gray-600 text-sm md:text-base">
                Starting your very first project? Explore some of these project ideas designed to compliment just taking one of our HTML, Python, JS courses.
            </p>
        </div>

        @if ($practices->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($practices as $practice)
                    <a href="{{ route('practice.show', $practice->slug) }}" class="group flex flex-col bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-indigo-300 hover:shadow-lg transition duration-300 h-full">
                        
                        <div class="relative h-48 w-full bg-gray-100">
                            @if($practice->thumbnail)
                                <img src="{{ $practice->thumbnail }}" alt="{{ $practice->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                        </div>

                        <div class="p-5 flex-1 flex flex-col border-t border-gray-50">
                            <span class="text-[10px] font-bold tracking-widest text-indigo-500 uppercase mb-2">Tutorial</span>
                            
                            <h3 class="text-lg font-bold text-gray-800 group-hover:text-indigo-600 transition-colors mb-2 line-clamp-2">
                                {{ $practice->title }}
                            </h3>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $practices->appends(request()->query())->links() }}
            </div>

        @else
            <div class="text-center py-16 bg-white rounded-xl border border-gray-200 shadow-sm">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <h3 class="text-xl font-medium text-gray-800 mb-2">No Tutorials Found</h3>
                <p class="text-gray-500">Materi latihan dengan kriteria tersebut tidak ditemukan.</p>
                @if(request('search') || (request('category') && request('category') !== 'All'))
                    <a href="{{ route('practice.index') }}" class="mt-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Reset Filter</a>
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