@extends('layouts.app')

@section('content')
<div class="py-12 pt-24">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

        <!-- Tombol Kembali ke Halaman Manage Content -->
        <div class="mb-4">
            <a href="{{ route('admin.courses.content', $module->course_id) }}" class="inline-flex items-center text-teal-500 hover:text-teal-700 font-semibold transition duration-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Manajemen Konten
            </a>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
            <div class="p-6 text-gray-900">

                <h2 class="text-2xl font-semibold mb-6">Edit Modul: {{ $module->title }}</h2>

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

                <!-- Form Edit Modul -->
                <form action="{{ route('admin.modules.update', $module->id) }}" method="POST">
                    @csrf
                    @method('PUT') {{-- Method PUT untuk update --}}

                    <div class="space-y-6">

                        <!-- Judul Modul -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Judul Modul</label>
                            <input type="text" name="title" id="title" 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" 
                                   value="{{ old('title', $module->title) }}" required>
                        </div>

                        {{-- Nanti bisa tambahkan input 'order' jika perlu --}}

                        <!-- Tombol Submit -->
                        <div class="flex justify-end pt-4">
                            <a href="{{ route('admin.courses.content', $module->course_id) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 mr-3">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 font-semibold">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection