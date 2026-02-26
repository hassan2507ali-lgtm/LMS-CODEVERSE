@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#0B1120] text-white pt-24 pb-16 font-sans">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-10">
            <a href="{{ route('practice.index') }}" class="inline-flex items-center text-slate-400 hover:text-white transition mb-6 text-sm font-medium">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Tutorials
            </a>
            
            <h1 class="text-3xl md:text-5xl font-bold font-mono text-white mb-4 tracking-wide">{{ $practice->title }}</h1>
            <p class="text-slate-400 text-lg max-w-2xl leading-relaxed">
                {{ $practice->description }}
            </p>
        </div>

        <div class="relative">
            
            <div class="hidden sm:block absolute left-[1.65rem] top-12 bottom-0 w-0.5 bg-slate-800 z-0"></div>

            <div class="relative z-10 mb-6">
                
                <div class="flex items-center bg-[#1e293b] p-4 rounded-t-xl border border-slate-700 shadow-sm cursor-pointer">
                    <div class="w-10 h-10 rounded-full bg-[#a3e635] text-slate-900 flex items-center justify-center flex-shrink-0 border-4 border-[#0B1120] z-10 mr-4 shadow-sm">
                        <svg class="w-5 h-5 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    
                    <div class="flex-1 flex justify-between items-center">
                        <h2 class="text-xl font-mono font-bold text-white tracking-wide">
                            Exercises List
                        </h2>
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                    </div>
                </div>

                <div class="bg-[#0f172a] border-x border-b border-slate-800 rounded-b-xl p-6 sm:p-8 ml-0 sm:ml-5 mt-[-1px]">
                    
                    <p class="text-slate-400 text-sm mb-6">
                        Complete these exercises step-by-step to master the topic. Click "Start" to open the workspace.
                    </p>

                    @if ($practice->exercises->count() > 0)
                        <div class="flex flex-col space-y-2">
                            @foreach ($practice->exercises as $index => $exercise)
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between py-3 border-b border-slate-800/50 last:border-0 group hover:bg-[#1e293b]/30 px-2 rounded-lg transition duration-200">
                                    
                                    <div class="flex flex-col sm:flex-row sm:items-center flex-1 mb-3 sm:mb-0">
                                        <span class="text-slate-400 font-mono text-sm w-32 mb-1 sm:mb-0">Exercise {{ $index + 1 }}</span>
                                        <span class="text-slate-200 text-sm font-medium pr-4">{{ $exercise->title }}</span>
                                    </div>
                                    
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('practice.exercise.start', ['slug' => $practice->slug, 'exercise' => $exercise->id]) }}" 
                                           class="inline-block border-2 border-slate-600 hover:border-slate-300 text-slate-300 hover:text-white font-mono text-xs px-5 py-2 rounded shadow-sm hover:shadow-md transition uppercase tracking-widest text-center min-w-[100px] bg-[#0f172a]">
                                            Start
                                        </a>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-slate-500 font-mono text-sm">No exercises found yet. Coming soon!</p>
                        </div>
                    @endif

                </div>
            </div>

            <div class="relative z-10 flex items-center p-4 ml-0 sm:ml-1 mt-4 opacity-50 cursor-not-allowed">
                <div class="w-8 h-8 rounded-full border-2 border-slate-600 text-slate-400 flex items-center justify-center flex-shrink-0 bg-[#0B1120] z-10 mr-5 font-mono text-sm">
                    2
                </div>
                <div class="flex-1 flex justify-between items-center">
                    <h2 class="text-xl font-mono font-bold text-slate-400 tracking-wide">
                        Variables
                    </h2>
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection