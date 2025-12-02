@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 py-12 pt-24">
    <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg max-w-4xl mx-auto border border-gray-200">

        <!-- Tombol Kembali -->
        <div class="mb-6">
            <a href="{{ route('practice.index') }}" class="inline-flex items-center text-teal-500 hover:text-teal-700 font-semibold transition duration-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Practice List
            </a>
        </div>

        <!-- Gambar Thumbnail dari GitHub -->
        <div class="relative mb-6">
            <img src="{{ $practice->image_url }}" 
                 alt="Thumbnail {{ $practice->title }}" 
                 class="w-full h-64 md:h-80 object-cover rounded-lg shadow-sm"
                 onerror="this.src='https://placehold.co/1200x600/8b5cf6/ffffff?text={{ urlencode($practice->title) }}'">
            
            {{-- GitHub badge overlay --}}
            @if($practice->github_link)
                <div class="absolute top-4 right-4 bg-gray-900 bg-opacity-90 text-white px-3 py-2 rounded-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                    </svg>
                    <span class="font-medium">{{ $practice->github_repo }}</span>
                </div>
            @endif
        </div>

        <!-- Judul & Kategori -->
        <div class="mb-6">
            <div class="flex items-center gap-3 mb-3">
                @if($practice->category)
                    <span class="px-3 py-1 bg-teal-100 text-teal-700 text-sm rounded-md font-semibold">
                        {{ $practice->category }}
                    </span>
                @endif
                <span class="text-sm text-gray-500">
                    {{ $practice->created_at->diffForHumans() }}
                </span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-4">{{ $practice->title }}</h1>
        </div>

        <!-- Tags -->
        @if($practice->tags && count($practice->tags) > 0)
            <div class="flex flex-wrap gap-2 mb-6">
                @foreach ($practice->tags as $tag)
                    <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full font-medium">{{ $tag }}</span>
                @endforeach
            </div>
        @endif

        <!-- GitHub Link Button -->
        @if($practice->github_link)
            <div class="mb-8">
                <a href="{{ $practice->github_link }}" 
                   target="_blank"
                   class="w-full flex items-center justify-center gap-2 px-8 py-3 bg-gray-900 text-white rounded-md hover:bg-gray-800 text-lg font-semibold transition duration-300">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                    </svg>
                    View on GitHub
                </a>
            </div>
        @endif

        <!-- Description -->
        @if($practice->description)
            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b border-gray-200 pb-2">About This Project</h2>
                <div class="prose max-w-none text-gray-700">
                    <p>{{ $practice->description }}</p>
                </div>
            </div>
        @endif

        <!-- Content -->
        @if($practice->content)
            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b border-gray-200 pb-2">Project Details</h2>
                <div class="prose max-w-none text-gray-700">
                    {!! nl2br(e($practice->content)) !!}
                </div>
            </div>
        @endif

        <!-- Exercises Section -->
        @if($practice->exercises && $practice->exercises->count() > 0)
            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6 border-b border-gray-200 pb-2">
                    📚 Exercises
                </h2>
                
                <div class="space-y-4">
                    @foreach($practice->exercises as $index => $exercise)
                        <div class="bg-gradient-to-r from-gray-50 to-white rounded-lg border-2 border-gray-200 hover:border-teal-400 transition-all duration-300 overflow-hidden">
                            <div class="p-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="flex items-center justify-center w-10 h-10 rounded-full bg-teal-100 text-teal-700 font-bold text-lg">
                                                {{ $index + 1 }}
                                            </span>
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-800">{{ $exercise->title }}</h3>
                                                @if($exercise->difficulty)
                                                    <span class="inline-block px-2 py-1 text-xs rounded-full
                                                        {{ $exercise->difficulty === 'easy' ? 'bg-green-100 text-green-700' : '' }}
                                                        {{ $exercise->difficulty === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                                        {{ $exercise->difficulty === 'hard' ? 'bg-red-100 text-red-700' : '' }}">
                                                        {{ ucfirst($exercise->difficulty) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        @if($exercise->description)
                                            <p class="text-gray-600 text-sm ml-13">{{ $exercise->description }}</p>
                                        @endif
                                    </div>
                                    
                                    <div class="ml-4">
                                        @if($exercise->is_completed)
                                            <button disabled class="px-6 py-2 bg-green-500 text-white rounded-lg font-semibold flex items-center gap-2 opacity-75 cursor-not-allowed">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Completed
                                            </button>
                                        @else
                                            <a href="{{ route('practice.exercise.start', [$practice->slug, $exercise->id]) }}" 
                                               class="px-6 py-2 bg-teal-500 hover:bg-teal-600 text-white rounded-lg font-semibold transition-colors duration-300 inline-block">
                                                Start
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Additional Info -->
        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Getting Started</h3>
            <div class="space-y-3 text-gray-700">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-teal-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p>Clone or download the repository from GitHub</p>
                </div>
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-teal-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p>Follow the README instructions in the repository</p>
                </div>
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-teal-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p>Experiment and learn by modifying the code</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
