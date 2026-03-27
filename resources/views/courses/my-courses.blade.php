@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 py-12 pt-24 min-h-screen">
    <div class="max-w-6xl mx-auto">
        
        <div class="mb-10">
            <h1 class="text-3xl font-bold text-gray-900 mb-2 font-mono">Ruang Belajar Saya</h1>
            <p class="text-gray-600">Akses semua kelas dan proyek latihan premium yang sudah kamu miliki.</p>
        </div>

        {{-- ========================================== --}}
        {{-- BAGIAN 1: KELAS SAYA (COURSES)             --}}
        {{-- ========================================== --}}
        <div class="mb-16">
            <h2 class="text-2xl font-bold text-indigo-700 mb-6 border-b-2 border-indigo-100 pb-2 flex items-center gap-2">
                📚 Kelas Video
            </h2>
            
            @if ($enrollments->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($enrollments as $enrollment)
                        <div class="bg-white rounded-xl shadow-sm hover:shadow-md overflow-hidden border border-gray-200 transition duration-300 flex flex-col">
                            <img src="{{ $enrollment->course->thumbnail ?? 'https://placehold.co/600x400/cccccc/ffffff?text=No+Image' }}" alt="{{ $enrollment->course->title }}" class="w-full h-48 object-cover">
                            <div class="p-6 flex flex-col flex-grow">
                                <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2">{{ $enrollment->course->title }}</h3>
                                <p class="text-xs text-gray-500 mb-6 flex-grow">Terdaftar: {{ $enrollment->created_at->format('d M Y') }}</p>
                                
                                <a href="{{ route('courses.show', $enrollment->course->slug) }}" class="block w-full text-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-300 text-sm font-medium">
                                    Lanjut Belajar
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-indigo-50/50 p-8 rounded-xl border border-indigo-100 text-center">
                    <p class="text-indigo-600 mb-4 text-sm">Kamu belum memiliki kelas video.</p>
                    <a href="{{ route('courses.course') }}" class="inline-block px-6 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition text-sm font-medium shadow-sm">Jelajahi Kelas</a>
                </div>
            @endif
        </div>

        {{-- ========================================== --}}
        {{-- BAGIAN 2: LATIHAN PREMIUM SAYA (PRACTICES) --}}
        {{-- ========================================== --}}
        <div>
            <h2 class="text-2xl font-bold text-slate-800 mb-6 border-b-2 border-gray-200 pb-2 flex items-center gap-2 font-mono">
                💻 Proyek Latihan Premium
            </h2>
            
            @if ($premiumPractices->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($premiumPractices as $transaction)
                        @php $practice = $transaction->practice; @endphp
                        
                        @if($practice)
                            <div class="bg-slate-900 rounded-xl shadow-md overflow-hidden border border-[#a3e635]/30 hover:shadow-[0_0_15px_rgba(163,230,53,0.2)] transition duration-300 flex flex-col">
                                <img src="{{ $practice->image_url ?? 'https://placehold.co/600x400/1e293b/a3e635?text=Codeverse' }}" alt="{{ $practice->title }}" class="w-full h-48 object-cover opacity-90 border-b border-slate-800">
                                <div class="p-6 flex flex-col flex-grow relative">
                                    {{-- Badge Premium --}}
                                    <div class="absolute -top-3 right-4 bg-[#a3e635] text-slate-900 text-xs font-bold px-3 py-1 rounded-full shadow-sm font-mono uppercase tracking-wider">
                                        Premium
                                    </div>

                                    <h3 class="text-lg font-bold text-white mb-2 line-clamp-2 font-mono mt-1">{{ $practice->title }}</h3>
                                    <p class="text-xs text-slate-400 mb-6 flex-grow">Dibeli pada: {{ $transaction->created_at->format('d M Y') }}</p>
                                    
                                    <a href="{{ route('practice.show', $practice->slug) }}" class="block w-full text-center px-4 py-3 bg-[#a3e635] text-slate-900 font-bold rounded-lg hover:bg-[#8fd125] transition duration-300 font-mono uppercase text-xs tracking-wider">
                                        Buka Workspace 🚀
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="bg-slate-50 p-8 rounded-xl border border-slate-200 text-center">
                    <p class="text-slate-500 mb-4 text-sm font-mono">Belum ada proyek latihan premium yang terbuka.</p>
                    <a href="{{ route('practice.index') }}" class="inline-block px-6 py-2 border-2 border-slate-300 text-slate-600 rounded-lg hover:border-[#a3e635] hover:text-slate-800 hover:bg-[#a3e635]/10 transition text-sm font-mono">Lihat Katalog Latihan</a>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection