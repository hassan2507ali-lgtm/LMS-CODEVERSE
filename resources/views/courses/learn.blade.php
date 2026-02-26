@extends('layouts.app')

@section('content')
<div class="pt-16 min-h-screen bg-gray-50 flex flex-col md:flex-row">
    
    <div class="flex-1 p-4 sm:p-6 lg:p-8">
        
        <a href="{{ route('courses.show', $course->slug) }}" class="inline-flex items-center text-sm text-gray-500 hover:text-indigo-600 mb-6 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Detail Kelas
        </a>

        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6">{{ $currentLesson->title }}</h1>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @if($currentLesson->content_type === 'video')
                <div class="aspect-w-16 aspect-h-9 bg-black flex items-center justify-center min-h-[400px]">
                @if($currentLesson->content)
                <iframe src="{{ $currentLesson->content }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-full min-h-[400px]"></iframe>
                        <p class="text-gray-400">URL Video Belum Dimasukkan oleh Admin</p>
                    @endif
                </div>
            @endif

            <div class="p-6 sm:p-8 prose max-w-none text-gray-700">
                {!! nl2br(e($currentLesson->content)) !!}
            </div>
        </div>

    </div>

    <div class="w-full md:w-80 lg:w-96 bg-white border-l border-gray-200 overflow-y-auto" style="height: calc(100vh - 4rem);">
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-bold text-gray-800 line-clamp-2">{{ $course->title }}</h2>
            <p class="text-sm text-gray-500 mt-1">Daftar Materi</p>
        </div>

        <div class="divide-y divide-gray-100">
            @foreach($course->modules as $module)
                <div class="bg-white">
                    <div class="px-6 py-4 bg-gray-50 text-sm font-semibold text-gray-700 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        {{ $module->title }}
                    </div>
                    <ul class="flex flex-col">
                        @foreach($module->lessons as $lesson)
                            @php 
                                $isActive = $lesson->id === $currentLesson->id; 
                            @endphp
                            <a href="{{ route('courses.learn', ['slug' => $course->slug, 'lesson' => $lesson->id]) }}" 
                               class="px-6 py-3 text-sm flex items-center transition-colors {{ $isActive ? 'bg-indigo-50 border-l-4 border-indigo-500 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50 border-l-4 border-transparent' }}">
                                
                                @if($lesson->content_type === 'video')
                                    <svg class="w-4 h-4 mr-3 {{ $isActive ? 'text-indigo-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path></svg>
                                @else
                                    <svg class="w-4 h-4 mr-3 {{ $isActive ? 'text-indigo-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                @endif
                                
                                <span class="line-clamp-2">{{ $lesson->title }}</span>
                            </a>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection