@extends('layouts.app')

@section('content')
<div class="pt-16 min-h-screen bg-gray-50 flex flex-col lg:flex-row font-sans">
    
    <div class="flex-1 p-4 sm:p-6 lg:p-8">
        
        <a href="{{ route('courses.show', $course->slug) }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-indigo-600 mb-6 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Detail Kelas
        </a>

        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6">{{ $currentLesson->title }}</h1>

        @if($currentLesson->content_type === 'video')
            <div class="aspect-video w-full rounded-xl overflow-hidden shadow-md border border-gray-200 bg-slate-900 mb-8 relative">
                @if($currentLesson->embed_url)
                    <iframe 
                        class="absolute top-0 left-0 w-full h-full" 
                        src="{{ $currentLesson->embed_url }}" 
                        title="{{ $currentLesson->title }}"
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>
                @else
                    <div class="flex flex-col items-center justify-center w-full h-full text-gray-400">
                        <svg class="w-12 h-12 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        <p class="font-mono text-sm">URL Video belum dimasukkan / tidak valid</p>
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-10 prose prose-indigo max-w-none text-gray-700">
                {!! nl2br(e($currentLesson->content)) !!}
            </div>
        @endif

    </div>

    <div class="w-full lg:w-96 bg-white border-l border-gray-200 lg:sticky lg:top-16 lg:h-[calc(100vh-4rem)] flex flex-col shadow-[-4px_0_15px_-3px_rgba(0,0,0,0.02)] z-10">
        
        <div class="p-6 border-b border-gray-100 bg-gray-50 flex-shrink-0">
            <h2 class="text-lg font-bold text-gray-900 line-clamp-2 leading-tight">{{ $course->title }}</h2>
            <p class="text-sm text-gray-500 mt-2 font-medium tracking-wide uppercase">Daftar Materi</p>
        </div>

        <div class="overflow-y-auto flex-1 pb-10">
            @foreach($course->modules as $module)
                <div class="mb-2">
                    <div class="px-6 py-4 bg-white text-sm font-bold text-gray-800 flex items-center sticky top-0 border-b border-gray-50 shadow-sm z-10">
                        <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        {{ $module->title }}
                    </div>
                    
                    <ul class="flex flex-col bg-white">
                        @foreach($module->lessons as $lesson)
                            @php 
                                $isActive = $lesson->id === $currentLesson->id; 
                            @endphp
                            <li>
                                <a href="{{ route('courses.learn', ['slug' => $course->slug, 'lesson' => $lesson->id]) }}" 
                                   class="px-6 py-3.5 text-sm flex items-start transition-all duration-200 {{ $isActive ? 'bg-indigo-50 border-l-4 border-indigo-600 text-indigo-800 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-l-4 border-transparent' }}">
                                    
                                    <div class="mt-0.5 mr-3 flex-shrink-0">
                                        @if($lesson->content_type === 'video')
                                            <svg class="w-4 h-4 {{ $isActive ? 'text-indigo-600' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path></svg>
                                        @else
                                            <svg class="w-4 h-4 {{ $isActive ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        @endif
                                    </div>
                                    
                                    <span class="line-clamp-2 leading-relaxed">{{ $lesson->title }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection