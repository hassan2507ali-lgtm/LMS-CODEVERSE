@extends('layouts.app')

@section('content')
<div class="py-12 pt-24">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
            <div class="p-6 text-gray-900">

                <h2 class="text-2xl font-semibold mb-6">Edit Kursus: {{ $course->title }}</h2>

                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.courses.update', $course->id) }}" method="POST"> 
                    @csrf
                    @method('PUT') 

                    <div class="space-y-6">

                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Judul Kursus</label>
                            <input type="text" name="title" id="title" 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" 
                                   value="{{ old('title', $course->title) }}" required>
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Kategori</label>
                            <select name="category" id="category" 
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white" required>
                                <option value="" disabled>-- Pilih Kategori --</option>
                                <option value="Programming" @if(old('category', $course->category) == 'Programming') selected @endif>Programming</option>
                                <option value="Web Development" @if(old('category', $course->category) == 'Web Development') selected @endif>Web Development</option>
                                <option value="UI/UX Design" @if(old('category', $course->category) == 'UI/UX Design') selected @endif>UI/UX Design</option>
                                <option value="Python" @if(old('category', $course->category) == 'Python') selected @endif>Python</option>
                                <option value="Data Science & Analytics" @if(old('category', $course->category) == 'Data Science & Analytics') selected @endif>Data Science & Analytics</option>
                                <option value="Lainnya" @if(old('category', $course->category) == 'Lainnya') selected @endif>Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi Singkat</label>
                            <textarea name="description" id="description" rows="3" 
                                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" 
                                      required>{{ old('description', $course->description) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                                <input type="number" name="price" id="price" 
                                       value="{{ old('price', $course->price) }}"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label for="is_free" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="is_free" id="is_free" 
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                    <option value="1" @if(old('is_free', $course->is_free) == 1) selected @endif>Gratis</option>
                                    <option value="0" @if(old('is_free', $course->is_free) == 0) selected @endif>Berbayar</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="thumbnail" class="block text-sm font-medium text-gray-700">URL Thumbnail</label>
                            <input type="text" name="thumbnail" id="thumbnail" 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" 
                                   value="{{ old('thumbnail', $course->thumbnail) }}"
                                   placeholder="https://... (Gunakan placeholder jika kosong)">
                        </div>

                        <div class="flex justify-end pt-4">
                            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 mr-3">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 font-semibold">
                                Update Kursus
                            </button>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection