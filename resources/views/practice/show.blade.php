@extends('layouts.app')

@section('content')

@php
    // AMBIL DATA PROGRESS USER: Cari tahu ID soal apa saja yang sudah diselesaikan user ini
    $completedIds = [];
    if (auth()->check()) {
        // Menggunakan property (tanpa kurung) agar mengambil dari Collection Laravel langsung
        $completedIds = auth()->user()->completedExercises->pluck('id')->toArray();
    }
@endphp

{{-- Main Container: Diubah jadi Putih & Tulisan Gelap --}}
<div class="min-h-screen bg-white text-gray-900 pt-24 pb-16 font-sans">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-10 border-b border-gray-100 pb-10">
            {{-- Tombol Kembali: Abu-abu --}}
            <a href="{{ route('practice.index') }}" class="inline-flex items-center text-gray-500 hover:text-gray-900 transition mb-6 text-sm font-medium">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Tutorials
            </a>
            
            {{-- Judul & Deskripsi: Hitam/Abu Tua --}}
            <h1 class="text-3xl md:text-5xl font-bold font-mono text-gray-900 mb-4 tracking-wide">{{ $practice->title }}</h1>
            <p class="text-gray-600 text-lg max-w-2xl leading-relaxed">
                {{ $practice->description }}
            </p>
        </div>

        <div class="relative">
            
            {{-- Garis Timeline: Abu Muda --}}
            <div class="hidden sm:block absolute left-[1.65rem] top-12 bottom-0 w-0.5 bg-gray-200 z-0"></div>

            <div class="relative z-10 mb-6">
                
                {{-- Header Accordion: Abu Sangat Muda & Border Abu Muda --}}
                <div class="flex items-center bg-gray-50 p-4 rounded-t-xl border border-gray-200 shadow-sm cursor-pointer">
                    {{-- Icon Circle: Border Putih --}}
                    <div class="w-10 h-10 rounded-full bg-[#a3e635] text-slate-900 flex items-center justify-center flex-shrink-0 border-4 border-white z-10 mr-4 shadow-sm">
                        <svg class="w-5 h-5 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    
                    <div class="flex-1 flex justify-between items-center">
                        <h2 class="text-xl font-mono font-bold text-gray-900 tracking-wide">
                            Module List
                        </h2>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                    </div>
                </div>

                {{-- Body Accordion: Putih & Border Abu Sangat Muda --}}
                <div class="bg-white border-x border-b border-gray-100 rounded-b-xl p-6 sm:p-8 ml-0 sm:ml-5 mt-[-1px]">
                    
                    {{-- Deskripsi: Abu Tua --}}
                    <p class="text-gray-600 text-sm mb-6">
                        Complete these exercises step-by-step to master the topic. Click "Start" to open the workspace.
                    </p>

                    @if ($practice->exercises->count() > 0)
                        <div class="flex flex-col space-y-2">
                            @foreach ($practice->exercises as $index => $exercise)
                                @php
                                    // CEK APAKAH SOAL INI SUDAH SELESAI
                                    $isCompleted = in_array($exercise->id, $completedIds);
                                @endphp

                                {{-- Row Item: Border Abu Sangat Muda & Hover Abu Muda --}}
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between py-3 border-b border-gray-100/50 last:border-0 group hover:bg-gray-50 px-2 rounded-lg transition duration-200">
                                    
                                    <div class="flex flex-col sm:flex-row sm:items-center flex-1 mb-3 sm:mb-0">
                                        {{-- Exercise X: Abu Muda --}}
                                        <span class="text-gray-500 font-mono text-sm w-32 mb-1 sm:mb-0">Exercise {{ $index + 1 }}</span>
                                        {{-- Judul Soal: Hitam/Abu Tua --}}
                                        <span class="text-gray-800 text-sm font-medium pr-4 flex items-center gap-2">
                                            {{ $exercise->title }}
                                            
                                            {{-- MUNCULKAN CENTANG HIJAU JIKA SELESAI --}}
                                            @if($isCompleted)
                                                <svg class="w-5 h-5 text-[#a3e635]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <div class="flex-shrink-0">
                                        {{-- Tombol Start/Review: Disesuaikan warnanya --}}
                                        <a href="{{ route('practice.exercise.start', ['slug' => $practice->slug, 'exercise' => $exercise->id]) }}" 
                                           class="inline-block border-2 font-mono text-xs px-5 py-2 rounded shadow-sm hover:shadow-md transition uppercase tracking-widest text-center min-w-[100px] 
                                           {{ $isCompleted 
                                                ? 'border-[#a3e635] text-[#a3e635] hover:bg-[#a3e635] hover:text-slate-900 bg-transparent' 
                                                : 'border-gray-300 hover:border-gray-400 text-gray-700 hover:text-gray-900 bg-white hover:bg-gray-50' }}">
                                            {{ $isCompleted ? 'Review' : 'Start' }}
                                        </a>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    @else
                        {{-- Empty State --}}
                        <div class="text-center py-8">
                            <p class="text-gray-500 font-mono text-sm">No exercises found yet. Coming soon!</p>
                        </div>
                    @endif

                </div>
            </div>

            

        </div>
    </div>
</div>
@endsection