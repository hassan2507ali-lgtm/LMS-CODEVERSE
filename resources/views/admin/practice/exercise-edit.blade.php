@extends('layouts.app')

@section('content')
<div class="py-12 pt-24">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

        <div class="mb-4">
            <a href="{{ route('admin.practice.exercises.manage', $practice->id) }}" class="inline-flex items-center text-teal-500 hover:text-teal-700 font-semibold transition duration-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar Exercises
            </a>
        </div>

        <h2 class="text-3xl font-semibold mb-6 text-gray-800">
            Edit Exercise: <span class="font-bold text-teal-600">{{ $exercise->title }}</span>
        </h2>

        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
            <div class="p-6 text-gray-900">
                <form action="{{ route('admin.practice.exercises.update', [$practice->id, $exercise->id]) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Exercise *</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $exercise->title) }}" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500" required>
                        </div>

                        <div>
                            <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-1">Tingkat Kesulitan *</label>
                            <select name="difficulty" id="difficulty" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500" required>
                                <option value="easy" {{ old('difficulty', $exercise->difficulty) === 'easy' ? 'selected' : '' }}>Easy (Mudah)</option>
                                <option value="medium" {{ old('difficulty', $exercise->difficulty) === 'medium' ? 'selected' : '' }}>Medium (Sedang)</option>
                                <option value="hard" {{ old('difficulty', $exercise->difficulty) === 'hard' ? 'selected' : '' }}>Hard (Sulit)</option>
                            </select>
                        </div>

                        <div>
                            <label for="language" class="block text-sm font-medium text-gray-700 mb-1">Bahasa Pemrograman *</label>
                            <select name="language" id="language" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500" required>
                                <option value="python" {{ old('language', $exercise->language) === 'python' ? 'selected' : '' }}>Python</option>
                                <option value="html" {{ old('language', $exercise->language) === 'html' ? 'selected' : '' }}>HTML/CSS</option>
                                <option value="javascript" {{ old('language', $exercise->language) === 'javascript' ? 'selected' : '' }}>JavaScript</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Singkat</label>
                        <input type="text" name="description" id="description" value="{{ old('description', $exercise->description) }}" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                    </div>

                    <div>
                        <label for="instructions" class="block text-sm font-medium text-gray-700 mb-1">Instruksi (Step-by-step)</label>
                        <textarea name="instructions" id="instructions" rows="4" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">{{ old('instructions', $exercise->instructions) }}</textarea>
                    </div>

                    <div>
                        <label for="starter_code" class="block text-sm font-medium text-gray-700 mb-1">Starter Code (Kode Awal)</label>
                        <textarea name="starter_code" id="starter_code" rows="8" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 font-mono text-sm">{{ old('starter_code', $exercise->starter_code) }}</textarea>
                    </div>

                    <div>
                        <label for="solution_code" class="block text-sm font-medium text-gray-700 mb-1">Solution Code (Solusi)</label>
                        <textarea name="solution_code" id="solution_code" rows="8" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 font-mono text-sm">{{ old('solution_code', $exercise->solution_code) }}</textarea>
                    </div>

                    <div>
                        <label for="hints" class="block text-sm font-medium text-gray-700 mb-1">Hints (Petunjuk)</label>
                        <textarea name="hints" id="hints" rows="3" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">{{ old('hints', $exercise->hints) }}</textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.practice.exercises.manage', $practice->id) }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 font-semibold transition-colors">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2 bg-teal-500 text-white rounded-md hover:bg-teal-600 font-semibold transition-colors">
                            Update Exercise
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection