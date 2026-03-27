@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-4">
                <h2 class="text-2xl font-bold text-white">Edit Proyek Latihan</h2>
                <p class="text-purple-100 text-sm mt-1">Perbarui informasi proyek latihan</p>
            </div>

            {{-- Form (DITAMBAH ENCTYPE UNTUK UPLOAD FILE) --}}
            <form action="{{ route('admin.practice.update', $practice->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                {{-- Title --}}
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Proyek <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="title" 
                        id="title" 
                        value="{{ old('title', $practice->title) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        placeholder="Contoh: Build a Word Guessing Game"
                        required
                    >
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Category --}}
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="category" 
                        id="category"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        required
                    >
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Python" {{ old('category', $practice->category) == 'Python' ? 'selected' : '' }}>Python</option>
                        <option value="JavaScript" {{ old('category', $practice->category) == 'JavaScript' ? 'selected' : '' }}>JavaScript</option>
                        <option value="Java" {{ old('category', $practice->category) == 'Java' ? 'selected' : '' }}>Java</option>
                        <option value="HTML" {{ old('category', $practice->category) == 'HTML' ? 'selected' : '' }}>HTML/CSS</option>
                        <option value="React" {{ old('category', $practice->category) == 'React' ? 'selected' : '' }}>React</option>
                        <option value="PHP" {{ old('category', $practice->category) == 'PHP' ? 'selected' : '' }}>PHP</option>
                        <option value="Data Science" {{ old('category', $practice->category) == 'Data Science' ? 'selected' : '' }}>Data Science</option>
                        <option value="Other" {{ old('category', $practice->category) == 'Other' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi Singkat
                    </label>
                    <textarea 
                        name="description" 
                        id="description" 
                        rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        placeholder="Jelaskan secara singkat tentang proyek ini..."
                    >{{ old('description', $practice->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- GitHub Link --}}
                <div>
                    <label for="github_link" class="block text-sm font-medium text-gray-700 mb-2">
                        Link GitHub
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                            </svg>
                        </div>
                        <input 
                            type="url" 
                            name="github_link" 
                            id="github_link" 
                            value="{{ old('github_link', $practice->github_link) }}"
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="https://github.com/username/repo/blob/main/image.png"
                        >
                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        💡 Tip: Paste link gambar dari GitHub untuk menampilkan thumbnail otomatis
                    </p>
                    @error('github_link')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Current Image Preview --}}
                @if($practice->github_link)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Preview Gambar Saat Ini
                    </label>
                    <div class="relative w-48 h-32 rounded-lg overflow-hidden border-2 border-gray-200">
                        <img 
                            src="{{ $practice->image_url }}" 
                            alt="{{ $practice->title }}"
                            class="w-full h-full object-cover"
                            onerror="this.src='https://placehold.co/400x300/e5e7eb/6b7280?text=No+Image'"
                        >
                    </div>
                </div>
                @endif
                
                {{-- PENGATURAN FREEMIUM --}}
                <div class="p-6 bg-purple-50/50 border border-purple-100 rounded-xl space-y-4">
                    <h3 class="text-lg font-bold text-purple-900 border-b border-purple-200 pb-2">🔒 Pengaturan Akses & Harga</h3>
                    
                    <div>
                        <label for="is_free" class="block text-sm font-medium text-gray-700 mb-2">Tipe Akses Latihan</label>
                        <select name="is_free" id="is_free" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" onchange="togglePricing(this.value)">
                            <option value="1" {{ old('is_free', $practice->is_free) == true ? 'selected' : '' }}>🆓 Gratis (Free)</option>
                            <option value="0" {{ old('is_free', $practice->is_free) == false ? 'selected' : '' }}>💰 Berbayar (Premium)</option>
                        </select>
                    </div>

                    <div id="pricing_fields" style="display: {{ old('is_free', $practice->is_free) == false ? 'block' : 'none' }};">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Harga (Rp)</label>
                                <input type="number" name="price" id="price" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" value="{{ old('price', $practice->price) }}" placeholder="Contoh: 50000">
                            </div>
                            <div>
                                <label for="free_exercises_count" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Soal Gratis (Trial)</label>
                                <input type="number" name="free_exercises_count" id="free_exercises_count" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" value="{{ old('free_exercises_count', $practice->free_exercises_count) }}" placeholder="Contoh: 2">
                                <p class="text-xs text-gray-500 mt-1">Berapa soal pertama yang bisa diakses gratis oleh user yang belum beli.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 🔥 PENGATURAN DATABASE SQLITE (Opsional) --}}
                <div class="p-6 bg-teal-50 border border-teal-100 rounded-xl">
                    <label for="database_file" class="block text-sm font-bold text-teal-800 mb-2">
                        <svg class="w-5 h-5 inline-block mr-1 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                        Upload File Database (.sqlite / .db) - Opsi SQL Advanced
                    </label>
                    <input type="file" name="database_file" id="database_file" accept=".sqlite,.db" class="w-full px-4 py-2 border border-white bg-white rounded-lg focus:ring-2 focus:ring-teal-500 text-sm">
                    <p class="text-xs text-teal-600 mt-2">Upload dataset dari Kaggle (max 20MB) agar siswa bisa praktek SQL langsung di browser tanpa membebani server.</p>
                    
                    {{-- Indikator kalau sudah ada file yang ter-upload --}}
                    @if($practice->database_file)
                        <div class="mt-4 inline-flex items-center gap-2 px-3 py-1.5 bg-white text-teal-700 text-sm font-bold rounded-md border border-teal-200 shadow-sm">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Dataset Aktif: {{ basename($practice->database_file) }}
                        </div>
                    @endif

                    @error('database_file')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tags --}}
                <div>
                    <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">
                        Tags
                    </label>
                    <input 
                        type="text" 
                        name="tags" 
                        id="tags" 
                        value="{{ old('tags', is_array($practice->tags) ? implode(', ', $practice->tags) : '') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        placeholder="Python, Beginner, Game (pisahkan dengan koma)"
                    >
                    <p class="mt-1 text-xs text-gray-500">
                        Pisahkan setiap tag dengan koma. Contoh: Python, Beginner, Tutorial
                    </p>
                    @error('tags')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Content --}}
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                        Konten Detail (Opsional)
                    </label>
                    <textarea 
                        name="content" 
                        id="content" 
                        rows="6"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent font-mono text-sm"
                        placeholder="Tambahkan instruksi detail, langkah-langkah, atau informasi tambahan..."
                    >{{ old('content', $practice->content) }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <a 
                        href="{{ route('admin.practice.index') }}" 
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
                    >
                        Batal
                    </a>
                    <button 
                        type="submit" 
                        class="px-6 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:from-purple-700 hover:to-indigo-700 transition-all shadow-md hover:shadow-lg"
                    >
                        💾 Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
<script>
    function togglePricing(isFree) {
        const pricingFields = document.getElementById('pricing_fields');
        if (isFree == '0') {
            pricingFields.style.display = 'block';
        } else {
            pricingFields.style.display = 'none';
        }
    }
</script>
@endsection