@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header with Back Button -->
        <div class="mb-6">
            <a href="{{ route('practice.show', $practice->slug) }}" 
               class="inline-flex items-center text-teal-600 hover:text-teal-700 font-medium transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to {{ $practice->title }}
            </a>
        </div>

        <!-- Exercise Header -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-3xl font-bold text-gray-800">{{ $exercise->title }}</h1>
                        <span class="px-3 py-1 text-sm rounded-full font-semibold
                            {{ $exercise->difficulty === 'easy' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $exercise->difficulty === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $exercise->difficulty === 'hard' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ ucfirst($exercise->difficulty) }}
                        </span>
                    </div>
                    @if($exercise->description)
                        <p class="text-gray-600 text-lg">{{ $exercise->description }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Left Column: Instructions & Hints -->
            <div class="space-y-6">
                
                <!-- Instructions -->
                @if($exercise->instructions)
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Instructions
                    </h2>
                    <div class="prose prose-sm max-w-none text-gray-700">
                        {!! nl2br(e($exercise->instructions)) !!}
                    </div>
                </div>
                @endif

                <!-- Hints -->
                @if($exercise->hints)
                <div class="bg-yellow-50 border-2 border-yellow-200 rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-yellow-800 mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                        Hints
                    </h2>
                    <div class="prose prose-sm max-w-none text-yellow-900">
                        {!! nl2br(e($exercise->hints)) !!}
                    </div>
                </div>
                @endif

                <!-- Solution (Hidden by default) -->
                @if($exercise->solution_code)
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <details class="group">
                        <summary class="cursor-pointer text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2 hover:text-teal-600 transition-colors">
                            <svg class="w-6 h-6 text-green-500 group-open:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            View Solution
                        </summary>
                        <div class="mt-4">
                            <div class="bg-gray-900 rounded-lg p-4 overflow-x-auto">
                                <pre class="text-green-400 text-sm"><code>{{ $exercise->solution_code }}</code></pre>
                            </div>
                        </div>
                    </details>
                </div>
                @endif

            </div>

            <!-- Right Column: Code Editor -->
            <div class="space-y-6">
                
                <!-- Code Editor -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-gray-800 px-6 py-3 flex items-center justify-between">
                        <h2 class="text-white font-semibold flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                            Your Code
                        </h2>
                        <button onclick="copyCode()" class="text-gray-300 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="p-6">
                        <textarea 
                            id="codeEditor" 
                            class="w-full h-96 font-mono text-sm bg-gray-50 border-2 border-gray-200 rounded-lg p-4 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all"
                            placeholder="Write your code here..."
                        >{{ $exercise->starter_code ?? '// Start coding here...' }}</textarea>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex gap-4">
                        <button onclick="runCode()" class="flex-1 bg-teal-500 hover:bg-teal-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Run Code
                        </button>
                        <button onclick="resetCode()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Reset
                        </button>
                        <button onclick="markComplete()" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Complete
                        </button>
                    </div>
                </div>

                <!-- Output Console -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-gray-800 px-6 py-3">
                        <h2 class="text-white font-semibold flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Output
                        </h2>
                    </div>
                    <div id="output" class="p-6 bg-gray-900 text-green-400 font-mono text-sm min-h-32">
                        <p class="text-gray-500">Output will appear here...</p>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>

<script>
const starterCode = {{ Js::from($exercise->starter_code ?? '// Start coding here...') }};

function copyCode() {
    const code = document.getElementById('codeEditor').value;
    navigator.clipboard.writeText(code);
    alert('Code copied to clipboard!');
}

function resetCode() {
    if (confirm('Are you sure you want to reset your code?')) {
        document.getElementById('codeEditor').value = starterCode;
        document.getElementById('output').innerHTML = '<p class="text-gray-500">Output will appear here...</p>';
    }
}

function runCode() {
    const code = document.getElementById('codeEditor').value;
    const output = document.getElementById('output');
    
    output.innerHTML = '<p class="text-yellow-400">Running code...</p>';
    
    // Simulate code execution (in real app, you'd send to backend)
    setTimeout(() => {
        output.innerHTML = `
            <p class="text-green-400">✓ Code executed successfully!</p>
            <p class="text-gray-400 mt-2">Note: This is a simulation. In a real implementation, code would be executed on a secure backend.</p>
            <pre class="mt-4 text-white">${code}</pre>
        `;
    }, 1000);
}

function markComplete() {
    if (confirm('Mark this exercise as complete?')) {
        // In real app, send AJAX request to backend
        alert('Exercise marked as complete! 🎉');
        window.location.href = '{{ route("practice.show", $practice->slug) }}';
    }
}
</script>
@endsection
