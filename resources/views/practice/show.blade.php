@extends('layouts.app')

@section('content')

@php
    // AMBIL DATA PROGRESS USER & STATUS PEMBELIAN
    $completedIds = [];
    $hasBought = false; // Default: belum beli

    if (auth()->check()) {
        // 1. Cek soal apa saja yang sudah diselesaikan
        $completedIds = auth()->user()->completedExercises->pluck('id')->toArray();
        
        // 2. CEK KE DATABASE: Apakah user ini punya transaksi 'success' untuk practice ini?
        $hasBought = \App\Models\Transaction::where('user_id', auth()->id())
                        ->where('practice_id', $practice->id)
                        ->where('status', 'success')
                        ->exists();
    }

    $isPremiumTutorial = !$practice->is_free;
@endphp

{{-- Main Container --}}
<div class="min-h-screen bg-white text-gray-900 pt-24 pb-16 font-sans">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Tempat Menampilkan Notifikasi Error/Success Freemium --}}
        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="mb-10 border-b border-gray-100 pb-10">
            <a href="{{ route('practice.index') }}" class="inline-flex items-center text-gray-500 hover:text-gray-900 transition mb-6 text-sm font-medium">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Tutorials
            </a>
            
            <h1 class="text-3xl md:text-5xl font-bold font-mono text-gray-900 mb-4 tracking-wide">{{ $practice->title }}</h1>
            <p class="text-gray-600 text-lg max-w-2xl leading-relaxed">
                {{ $practice->description }}
            </p>
        </div>

        <div class="relative">
            <div class="hidden sm:block absolute left-[1.65rem] top-12 bottom-0 w-0.5 bg-gray-200 z-0"></div>

            <div class="relative z-10 mb-6">
                
                {{-- Header Accordion --}}
                <div class="flex items-center bg-gray-50 p-4 rounded-t-xl border border-gray-200 shadow-sm cursor-pointer">
                    <div class="w-10 h-10 rounded-full bg-[#a3e635] text-slate-900 flex items-center justify-center flex-shrink-0 border-4 border-white z-10 mr-4 shadow-sm">
                        <svg class="w-5 h-5 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    
                    <div class="flex-1 flex justify-between items-center">
                        <h2 class="text-xl font-mono font-bold text-gray-900 tracking-wide">Module List</h2>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                    </div>
                </div>

                {{-- Body Accordion --}}
                <div class="bg-white border-x border-b border-gray-100 rounded-b-xl p-6 sm:p-8 ml-0 sm:ml-5 mt-[-1px]">
                    
                    <p class="text-gray-600 text-sm mb-6">
                        Complete these exercises step-by-step to master the topic. Click "Start" to open the workspace.
                    </p>

                    @if ($groupedExercises->count() > 0)
                        
                        @php 
                            // Hitungan global supaya urutan soal dan batas Freemium tetap menyambung lintas modul
                            $globalExerciseIndex = 0; 
                        @endphp

                        <div class="flex flex-col space-y-6">
                            @foreach ($groupedExercises as $sectionName => $exercises)
                                
                                {{-- Kotak Grup Modul --}}
                                <div class="border border-gray-100 rounded-xl overflow-hidden shadow-sm bg-white">
                                    {{-- Header Modul --}}
                                    <div class="bg-gray-50 border-b border-gray-100 px-5 py-3 flex items-center gap-3">
                                        <div class="bg-slate-200 text-slate-600 p-1.5 rounded-md">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        </div>
                                        <h3 class="font-mono font-bold text-gray-800 text-lg">{{ $sectionName }}</h3>
                                    </div>
                                    
                                    {{-- Isi Modul (Daftar Soal) --}}
                                    <div class="p-2 flex flex-col space-y-1">
                                        @foreach ($exercises as $exercise)
                                            @php
                                                $globalExerciseIndex++;
                                                $isCompleted = in_array($exercise->id, $completedIds);
                                                $isFreeExercise = $globalExerciseIndex <= $practice->free_exercises_count;
                                                // Logika Gembok: Jika ini kelas berbayar, bukan soal gratisan, dan user belum beli = DIKUNCI!
                                                $isLocked = $isPremiumTutorial && !$isFreeExercise && !$hasBought;
                                            @endphp

                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between py-3 border-b border-gray-50 last:border-0 group hover:bg-gray-50 px-4 rounded-lg transition duration-200 {{ $isLocked ? 'opacity-70' : '' }}">
                                                
                                                <div class="flex flex-col sm:flex-row sm:items-center flex-1 mb-3 sm:mb-0">
                                                    <span class="text-gray-500 font-mono text-sm w-32 mb-1 sm:mb-0">Exercise {{ $globalExerciseIndex }}</span>
                                                    <span class="text-gray-800 text-sm font-medium pr-4 flex items-center gap-2">
                                                        {{ $exercise->title }}
                                                        @if($isCompleted)
                                                            <svg class="w-5 h-5 text-[#a3e635]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                        @endif
                                                    </span>
                                                </div>
                                                
                                                <div class="flex-shrink-0">
                                                    @if($isLocked)
                                                        {{-- TOMBOL GEMBOK --}}
                                                        <a href="#premium-banner" class="inline-flex items-center justify-center border-2 border-gray-200 bg-gray-50 text-gray-400 hover:text-purple-600 hover:border-purple-300 font-mono text-xs px-5 py-2 rounded shadow-sm uppercase tracking-widest min-w-[100px] transition">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                            Locked
                                                        </a>
                                                    @else
                                                        {{-- TOMBOL START/REVIEW NORMAL --}}
                                                        <a href="{{ route('practice.exercise.start', ['slug' => $practice->slug, 'exercise' => $exercise->id]) }}" 
                                                           class="inline-block border-2 font-mono text-xs px-5 py-2 rounded shadow-sm hover:shadow-md transition uppercase tracking-widest text-center min-w-[100px] 
                                                           {{ $isCompleted 
                                                                ? 'border-[#a3e635] text-[#a3e635] hover:bg-[#a3e635] hover:text-slate-900 bg-transparent' 
                                                                : 'border-gray-300 hover:border-gray-400 text-gray-700 hover:text-gray-900 bg-white hover:bg-gray-50' }}">
                                                            {{ $isCompleted ? 'Review' : 'Start' }}
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- ========================================== --}}
                        {{-- 🔥 BANNER PROMO PREMIUM (Hanya muncul jika berbayar & belum dibeli) --}}
                        {{-- ========================================== --}}
                        @if($isPremiumTutorial && !$hasBought)
                            <div id="premium-banner" class="mt-10 bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 rounded-2xl p-8 shadow-xl border border-purple-500/30 relative overflow-hidden">
                                {{-- Efek Cahaya Background --}}
                                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-purple-500 rounded-full blur-3xl opacity-20"></div>
                                <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-blue-500 rounded-full blur-3xl opacity-20"></div>

                                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                                    <div class="text-left flex-1">
                                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-500/20 border border-purple-500/30 text-purple-300 text-xs font-mono font-bold uppercase tracking-wider mb-3">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                            Premium Access
                                        </div>
                                        <h3 class="text-2xl font-bold text-white mb-2 font-mono">Unlock Full Exercises</h3>
                                        <p class="text-purple-200 text-sm leading-relaxed mb-4 max-w-lg">
                                            Kamu sudah mencoba versi gratis. Buka semua kunci latihan studi kasus ini untuk meningkatkan keahlian *coding* kamu ke level selanjutnya!
                                        </p>
                                        <div class="flex items-center gap-3">
                                            <span class="text-3xl font-bold text-white">Rp {{ number_format($practice->price, 0, ',', '.') }}</span>
                                            <span class="text-purple-300 text-sm line-through">Rp {{ number_format($practice->price + 50000, 0, ',', '.') }}</span>
                                        </div>
                                    </div>

                                    <div class="w-full md:w-auto">
                                        {{-- FORM TOMBOL BELI MENGARAH KE CHECKOUT --}}
                                        <form action="{{ route('checkout.practice', $practice->id) }}" method="POST" class="w-full">
                                            @csrf
                                            <button type="submit" class="block w-full text-center px-8 py-4 bg-[#a3e635] hover:bg-[#8fd125] text-slate-900 font-bold font-mono rounded-xl transition shadow-[0_0_20px_rgba(163,230,53,0.3)] hover:shadow-[0_0_25px_rgba(163,230,53,0.5)] uppercase tracking-wider text-sm">
                                                Beli Sekarang 🚀
                                            </button>
                                        </form>
                                        <p class="text-purple-300 text-xs text-center mt-3 flex items-center justify-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                            Akses Selamanya
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        {{-- ========================================== --}}

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

{{-- Script Smooth Scroll --}}
<script>
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
</script>
@endsection