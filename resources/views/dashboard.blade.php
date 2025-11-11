@extends('layouts.app')

@section('content')
<div class="py-12 pt-24"> {{-- pt-24 agar tidak tertutup navbar --}}
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
            <div class="p-6 text-gray-900">
                
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Kelola Kursus</h2>
                    <a href="{{ route('admin.courses.create') }}" class="px-5 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 font-semibold">
                        + Tambah Kursus Baru
                    </a>
                </div>

                <!-- Pesan Sukses (jika ada) -->
                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-md">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Tabel Daftar Kursus -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Kursus</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            {{-- Loop data $courses dari AdminController --}}
                            @forelse ($courses as $course)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $course->title }}</div>
                                        <div class="text-xs text-gray-500">{{ $course->slug }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($course->is_free)
                                            <span class="text-sm font-medium text-teal-600">Gratis</span>
                                        @else
                                            <span class="text-sm text-gray-900">Rp{{ number_format($course->price, 0, ',', '.') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $course->is_free ? 'bg-teal-100 text-teal-800' : 'bg-indigo-100 text-indigo-800' }}">
                                            {{ $course->is_free ? 'Gratis' : 'Berbayar' }}
                                        </span>
                                    </td>
                                    
                                    {{-- INI BAGIAN AKSI YANG SUDAH LENGKAP --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        
                                        {{-- 1. Link ke Halaman Kelola Konten --}}
                                        <a href="{{ route('admin.courses.content', $course->id) }}" class="text-teal-600 hover:text-teal-900 mr-3">
                                            Manage Content
                                        </a>

                                        {{-- 2. Link ke Halaman Edit Kursus --}}
                                        <a href="{{ route('admin.courses.edit', $course->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            Edit
                                        </a>
                                        
                                        {{-- 3. Form untuk Hapus Kursus --}}
                                        <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Anda yakin ingin menghapus kursus ini? Data tidak bisa dikembalikan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                        Belum ada data kursus.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection