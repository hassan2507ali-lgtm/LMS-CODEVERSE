@extends('layouts.app')
@section('content')
<div class="py-12 pt-24">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-teal-500 hover:text-teal-700 font-semibold mb-4"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>Kembali ke Dashboard</a>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Kelola Practice Projects</h2>
                    <a href="{{ route('admin.practice.create') }}" class="px-5 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 font-semibold">+ Tambah Proyek Baru</a>
                </div>
                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-md">{{ session('success') }}</div>
                @endif
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preview</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul & Link</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tags</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($practices as $practice)
                                <tr class="hover:bg-gray-50">
                                    {{-- Preview Image Column --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="relative w-24 h-16 rounded-lg overflow-hidden border border-gray-200 group">
                                            <img src="{{ $practice->image_url }}" 
                                                 alt="{{ $practice->title }}" 
                                                 class="w-full h-full object-cover"
                                                 onerror="this.src='https://placehold.co/200x150/e5e7eb/6b7280?text=No+Image'">
                                            
                                            {{-- GitHub Badge Overlay --}}
                                            @if($practice->github_link)
                                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition-all duration-200 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    
                                    {{-- Title & Link Column --}}
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $practice->title }}</div>
                                        {{-- Menampilkan Link GitHub --}}
                                        @if($practice->github_link)
                                            <a href="{{ $practice->github_link }}" target="_blank" class="text-xs text-blue-500 hover:underline flex items-center mt-1">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" /></svg>
                                                Lihat di GitHub
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-400">No Link</span>
                                        @endif
                                    </td>
                                    
                                    {{-- Category Column --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-teal-100 text-teal-800">
                                            {{ $practice->category }}
                                        </span>
                                    </td>
                                    
                                    {{-- Tags Column --}}
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            @if($practice->tags)
                                                @foreach($practice->tags as $tag)
                                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ $tag }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-xs text-gray-400">-</span>
                                            @endif
                                        </div>
                                    </td>
                                    
                                    {{-- Actions Column --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.practice.exercises.manage', $practice->id) }}" class="text-teal-600 hover:text-teal-900 mr-3">Exercises</a>
                                        <a href="{{ route('admin.practice.edit', $practice->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                        <form action="{{ route('admin.practice.destroy', $practice->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Anda yakin ingin menghapus proyek ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">Belum ada data proyek latihan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection