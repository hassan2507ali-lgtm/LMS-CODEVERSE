@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 pt-24 pb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Pesan Notifikasi --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl shadow-sm flex items-center gap-3 font-medium">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl shadow-sm flex items-center gap-3 font-medium">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Tombol Kembali --}}
        <div class="mb-6">
            <a href="{{ route('courses.course') }}" class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-teal-600 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Katalog
            </a>
        </div>

        {{-- 🔥 CEK STATUS KEPEMILIKAN DI AWAL --}}
        @php
            $isEnrolled = Auth::check() ? \App\Models\Enrollment::where('user_id', Auth::id())->where('course_id', $course->id)->exists() : false;
        @endphp

        {{-- 🔥 LAYOUT GRID DINAMIS --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            {{-- ====================================== --}}
            {{-- BAGIAN KIRI: Info Kursus & Materi --}}
            {{-- Jika sudah beli = Lebar 3 Kolom (Full). Jika belum = 2 Kolom --}}
            {{-- ====================================== --}}
            <div class="{{ $isEnrolled ? 'lg:col-span-3' : 'lg:col-span-2' }} space-y-8">
                
                {{-- Judul (Tetap Tampil untuk Semua) --}}
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4 leading-tight">
                        {{ $course->title }}
                    </h1>
                    
                    <div class="flex items-center gap-4 {{ !$isEnrolled ? 'mb-8 pb-8 border-b border-gray-100' : '' }}">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-indigo-50 text-indigo-600 text-xs font-bold rounded-full border border-indigo-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            {{ $course->modules->count() }} Modul
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-teal-50 text-teal-600 text-xs font-bold rounded-full border border-teal-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            Video Interaktif
                        </span>
                        
                        {{-- Badge tambahan di header jika sudah memiliki kelas --}}
                        @if($isEnrolled)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-600 text-xs font-bold rounded-full border border-green-100 ml-auto">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Akses Terbuka
                            </span>
                        @endif
                    </div>

                    {{-- 🔥 TENTANG KELAS: SEMBUNYIKAN JIKA SUDAH BELI --}}
                    @if(!$isEnrolled)
                        <h2 class="text-xl font-bold text-gray-800 mb-3 mt-8">Tentang Kelas Ini</h2>
                        <p class="text-gray-600 leading-relaxed whitespace-pre-line text-sm sm:text-base">
                            {{ $course->description }}
                        </p>
                    @endif
                </div>

                {{-- Kurikulum Materi --}}
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Kurikulum Materi</h2>
                    <div class="space-y-4">
                        @forelse ($course->modules as $index => $module)
                            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition">
                                <div class="flex items-center p-5 bg-gray-50/50 border-b border-gray-100">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm mr-4">
                                        {{ $index + 1 }}
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-800">{{ $module->title }}</h3>
                                    <span class="ml-auto text-xs font-bold text-gray-400">{{ $module->lessons->count() }} Materi</span>
                                </div>

                                <ul class="divide-y divide-gray-100">
                                    @forelse ($module->lessons as $lesson)
                                        <li class="flex items-center justify-between p-4 hover:bg-slate-50 transition group">
                                            <div class="flex items-center gap-4">
                                                @if ($lesson->content_type == 'video')
                                                    <div class="w-8 h-8 rounded-full bg-red-50 text-red-500 flex items-center justify-center">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path></svg>
                                                    </div>
                                                @else 
                                                    <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path></svg>
                                                    </div>
                                                @endif
                                                <span class="text-sm font-medium text-gray-700 group-hover:text-teal-600 transition">{{ $lesson->title }}</span>
                                            </div>
                                            
                                            {{-- Tombol Mulai di dalam Kurikulum --}}
                                            @if ($isEnrolled)
                                                <a href="{{ route('courses.learn', ['slug' => $course->slug, 'lesson' => $lesson->id]) }}" class="text-[11px] px-4 py-1.5 bg-teal-50 text-teal-600 rounded-full font-bold uppercase tracking-wider hover:bg-teal-500 hover:text-white transition">Mulai Belajar</a>
                                            @else
                                                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                            @endif
                                        </li>
                                    @empty
                                        <li class="px-5 py-4 text-gray-400 italic text-sm text-center">Belum ada materi di modul ini.</li>
                                    @endforelse
                                </ul>
                            </div>
                        @empty
                            <div class="p-8 text-center bg-white rounded-2xl border border-gray-200 shadow-sm">
                                <p class="text-gray-500 font-medium">Materi kursus sedang disiapkan. Nantikan segera!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- ====================================== --}}
            {{-- BAGIAN KANAN: Sticky Card Checkout --}}
            {{-- 🔥 SEMBUNYIKAN JIKA SUDAH BELI --}}
            {{-- ====================================== --}}
            @if(!$isEnrolled)
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden sticky top-28">
                    
                    <div class="w-full aspect-video bg-gray-900 relative group cursor-pointer overflow-hidden">
                        <img src="{{ $course->thumbnail ?? 'https://placehold.co/1280x720/1e293b/ffffff?text=Course+Preview' }}" alt="Thumbnail" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/60 via-transparent to-transparent"></div>
                        
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-14 h-14 bg-white/20 backdrop-blur-sm border border-white/40 rounded-full flex items-center justify-center group-hover:bg-teal-500 group-hover:border-teal-500 transition-all duration-300 shadow-2xl group-hover:scale-110">
                                <svg class="w-6 h-6 text-white ml-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
                            </div>
                        </div>

                        <div class="absolute top-4 left-4">
                            <span class="px-2.5 py-1 bg-black/60 backdrop-blur-md text-white text-[10px] font-bold uppercase tracking-wider rounded-md border border-white/10">
                                🎬 Course Preview
                            </span>
                        </div>
                    </div>

                    <div class="p-6 sm:p-8">
                        <div class="mb-6">
                            @if ($course->is_free || $course->price == 0)
                                <span class="text-3xl font-extrabold text-green-500">Gratis!</span>
                            @else
                                <span class="text-sm font-bold text-gray-400 block mb-1">Investasi Kelas</span>
                                <span class="text-3xl font-extrabold text-gray-900">Rp{{ number_format($course->price, 0, ',', '.') }}</span>
                            @endif
                        </div>

                        <div class="mb-6">
                            @auth
                                <form action="{{ route('checkout.process', $course->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center justify-center px-6 py-3.5 bg-teal-500 text-white rounded-xl hover:bg-teal-600 hover:shadow-lg hover:-translate-y-0.5 text-sm font-bold transition duration-300">
                                        {{ ($course->is_free || $course->price == 0) ? 'Unlock Course (Gratis)' : 'Unlock Course' }}
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="w-full flex items-center justify-center px-6 py-3.5 bg-indigo-500 text-white rounded-xl hover:bg-indigo-600 hover:shadow-lg text-sm font-bold transition duration-300">
                                    Login untuk Unlock
                                </a>
                            @endauth
                        </div>

                        <div class="border-t border-gray-100 pt-6 mt-2">
                            <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-4">Kelas ini mencakup:</h4>
                            <ul class="space-y-3">
                                <li class="flex items-center text-sm text-gray-600 font-medium">
                                    <svg class="w-4 h-4 mr-3 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Akses materi selamanya
                                </li>
                                <li class="flex items-center text-sm text-gray-600 font-medium">
                                    <svg class="w-4 h-4 mr-3 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Video & Teks Berkualitas
                                </li>
                                <li class="flex items-center text-sm text-gray-600 font-medium">
                                    <svg class="w-4 h-4 mr-3 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Belajar kapan saja & di mana saja
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            {{-- 🔥 REKOMENDASI (SEMUA USER SUDAH DI-HANDLE DI ATAS, TAPI INI HANYA UNTUK BELUM BELI) --}}
            @if($recommendations->count() > 0 && !$isEnrolled)
                <div class="lg:col-span-3 mt-12 pt-10 border-t border-gray-200">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Mungkin kamu juga tertarik mempelajari...</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($recommendations as $rec)
                            <a href="{{ route('courses.show', $rec->slug) }}" class="group flex flex-col bg-white border border-gray-100 rounded-xl overflow-hidden hover:border-indigo-300 hover:shadow-lg transition duration-300 h-full">
                                <div class="relative h-40 w-full bg-gray-100 overflow-hidden">
                                    <img src="{{ $rec->thumbnail ?? 'https://placehold.co/600x400/f8fafc/94a3b8?text=Code+Verse' }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    @if($rec->is_free || $rec->price == 0)
                                        <span class="absolute top-3 right-3 bg-green-500 text-white text-[10px] font-black px-2 py-1 rounded-md uppercase tracking-wider shadow-sm">Gratis</span>
                                    @endif
                                </div>
                                <div class="p-5 flex-1 flex flex-col">
                                    <h4 class="font-bold text-gray-800 group-hover:text-indigo-600 transition mb-2 line-clamp-2">{{ $rec->title }}</h4>
                                    <div class="mt-auto pt-3 border-t border-gray-50 flex justify-between items-center">
                                        <span class="font-extrabold {{ $rec->price > 0 ? 'text-gray-900' : 'text-green-500' }}">
                                            {{ $rec->price > 0 ? 'Rp' . number_format($rec->price, 0, ',', '.') : 'FREE' }}
                                        </span>
                                        <span class="text-xs font-bold text-indigo-600 group-hover:text-indigo-800">Detail &rarr;</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection