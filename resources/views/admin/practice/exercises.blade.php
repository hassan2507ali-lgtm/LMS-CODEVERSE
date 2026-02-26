@extends('layouts.app')

@section('content')
<div class="py-12 pt-24">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <!-- Tombol Kembali -->
        <div class="mb-4">
            <a href="{{ route('admin.practice.index') }}" class="inline-flex items-center text-teal-500 hover:text-teal-700 font-semibold transition duration-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar Practice
            </a>
        </div>

        <!-- Judul Halaman -->
        <h2 class="text-3xl font-semibold mb-6 text-gray-800">
            Kelola Exercises: <span class="font-bold text-teal-600">{{ $practice->title }}</span>
        </h2>

        <!-- Pesan Sukses -->
        @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Validasi -->
        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Tambah Exercise Baru -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 mb-6">
            <div class="p-6 text-gray-900">
                <h3 class="text-xl font-semibold mb-4">Tambah Exercise Baru</h3>
                <form action="{{ route('admin.practice.exercises.store', $practice->id) }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Exercise *</label>
                            <input type="text" name="title" id="title" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500" placeholder="Contoh: Basic Fade Animation" required>
                        </div>

                        <!-- Difficulty -->
                        <div>
                            <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-1">Tingkat Kesulitan *</label>
                            <select name="difficulty" id="difficulty" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500" required>
                                <option value="easy">Easy (Mudah)</option>
                                <option value="medium">Medium (Sedang)</option>
                                <option value="hard">Hard (Sulit)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Singkat</label>
                        <input type="text" name="description" id="description" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500" placeholder="Deskripsi singkat tentang exercise ini">
                    </div>

                    <!-- Instructions -->
                    <div>
                        <label for="instructions" class="block text-sm font-medium text-gray-700 mb-1">Instruksi (Step-by-step)</label>
                        <textarea name="instructions" id="instructions" rows="4" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500" placeholder="1. Langkah pertama&#10;2. Langkah kedua&#10;3. Langkah ketiga"></textarea>
                    </div>

                    <!-- Starter Code -->
                    <div>
                        <label for="starter_code" class="block text-sm font-medium text-gray-700 mb-1">Starter Code (Kode Awal)</label>
                        <textarea name="starter_code" id="starter_code" rows="6" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 font-mono text-sm" placeholder="// Kode awal untuk user"></textarea>
                    </div>

                    <!-- Solution Code -->
                    <div>
                        <label for="solution_code" class="block text-sm font-medium text-gray-700 mb-1">Solution Code (Solusi)</label>
                        <textarea name="solution_code" id="solution_code" rows="6" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 font-mono text-sm" placeholder="// Kode solusi lengkap"></textarea>
                    </div>

                    <!-- Hints -->
                    <div>
                        <label for="hints" class="block text-sm font-medium text-gray-700 mb-1">Hints (Petunjuk)</label>
                        <textarea name="hints" id="hints" rows="3" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500" placeholder="- Hint 1&#10;- Hint 2&#10;- Hint 3"></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-teal-500 text-white rounded-md hover:bg-teal-600 font-semibold transition-colors">
                            Simpan Exercise
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Daftar Exercises yang Sudah Ada -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
            <div class="p-6 text-gray-900">
                <h3 class="text-xl font-semibold mb-4">Daftar Exercises ({{ $practice->exercises->count() }})</h3>
                
                @forelse ($practice->exercises as $index => $exercise)
                <div class="bg-gray-50 rounded-lg border border-gray-200 p-4 mb-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-teal-100 text-teal-700 font-bold text-sm">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-800">{{ $exercise->title }}</h4>
                                    <span class="inline-block px-2 py-1 text-xs rounded-full
                                        {{ $exercise->difficulty === 'easy' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $exercise->difficulty === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                        {{ $exercise->difficulty === 'hard' ? 'bg-red-100 text-red-700' : '' }}">
                                        {{ ucfirst($exercise->difficulty) }}
                                    </span>
                                </div>
                            </div>
                            
                            @if($exercise->description)
                                <p class="text-gray-600 text-sm ml-11 mb-2">{{ $exercise->description }}</p>
                            @endif

                            <div class="ml-11 text-xs text-gray-500 space-y-1">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span>Instructions: {{ $exercise->instructions ? 'Yes' : 'No' }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                    </svg>
                                    <span>Starter Code: {{ $exercise->starter_code ? 'Yes' : 'No' }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Solution: {{ $exercise->solution_code ? 'Yes' : 'No' }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                    </svg>
                                    <span>Hints: {{ $exercise->hints ? 'Yes' : 'No' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2 ml-4">
                            <!-- Edit Button -->
                            <a href="{{ route('admin.practice.exercises.edit', [$practice->id, $exercise->id]) }}" 
                               class="px-4 py-2 bg-indigo-500 text-white rounded-md hover:bg-indigo-600 text-sm font-semibold text-center transition-colors">
                                Edit
                            </a>

                            <!-- Delete Button -->
                            <form action="{{ route('admin.practice.exercises.destroy', [$practice->id, $exercise->id]) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Yakin hapus exercise ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 text-sm font-semibold transition-colors">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 italic text-center py-8">Belum ada exercise untuk practice ini. Tambahkan exercise pertama di atas!</p>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
