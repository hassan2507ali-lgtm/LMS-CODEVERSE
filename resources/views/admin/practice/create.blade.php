@extends('layouts.app')
@section('content')
<div class="py-12 pt-24">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-semibold mb-6">Tambah Proyek Latihan Baru</h2>
                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
                        <ul class="list-disc list-inside text-sm">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                    </div>
                @endif
                <form action="{{ route('admin.practice.store') }}" method="POST"> 
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Judul Proyek</label>
                            <input type="text" name="title" id="title" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required value="{{ old('title') }}">
                        </div>
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Kategori</label>
                            <input type="text" name="category" id="category" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required value="{{ old('category') }}" placeholder="Contoh: Python, HTML, React">
                        </div>
                        
                        {{-- INPUT BARU: GITHUB LINK --}}
                       <!-- Input Thumbnail -->
<div>
    <label for="thumbnail" class="block text-sm font-medium text-gray-700">URL Thumbnail (Gambar)</label>
    <input type="text" name="thumbnail" class="..." value="{{ old('thumbnail') }}" placeholder="https://placehold.co/600x400">
</div>

<!-- Input GitHub Link -->
<div>
    <label for="github_link" class="block text-sm font-medium text-gray-700">Link GitHub (Opsional)</label>
    <input type="text" name="github_link" class="..." value="{{ old('github_link') }}" placeholder="https://github.com/username/repo">
</div>
{{-- PENGATURAN FREEMIUM --}}
                        <div class="p-5 bg-gray-50 border border-gray-200 rounded-lg space-y-4">
                            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">🔒 Pengaturan Akses & Harga</h3>
                            
                            <div>
                                <label for="is_free" class="block text-sm font-medium text-gray-700">Tipe Akses Latihan</label>
                                <select name="is_free" id="is_free" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" onchange="togglePricing(this.value)">
                                    <option value="1" {{ old('is_free', '1') == '1' ? 'selected' : '' }}>🆓 Gratis (Free)</option>
                                    <option value="0" {{ old('is_free') == '0' ? 'selected' : '' }}>💰 Berbayar (Premium)</option>
                                </select>
                            </div>

                            <div id="pricing_fields" style="display: {{ old('is_free', '1') == '0' ? 'block' : 'none' }};">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <label for="price" class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                                        <input type="number" name="price" id="price" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" value="{{ old('price') }}" placeholder="Contoh: 50000">
                                    </div>
                                    <div>
                                        <label for="free_exercises_count" class="block text-sm font-medium text-gray-700">Jumlah Soal Gratis (Trial)</label>
                                        <input type="number" name="free_exercises_count" id="free_exercises_count" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" value="{{ old('free_exercises_count', 0) }}" placeholder="Contoh: 2">
                                        <p class="text-xs text-gray-500 mt-1">Berapa soal pertama yang bisa diakses gratis untuk "cicip-cicip" sebelum membeli.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi Singkat</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('description') }}</textarea>
                        </div>
                        <div>
                            <label for="tags" class="block text-sm font-medium text-gray-700">Tags (pisahkan dengan koma)</label>
                            <input type="text" name="tags" id="tags" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" value="{{ old('tags') }}" placeholder="Contoh: Beginner, Game">
                        </div>
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700">Konten / Instruksi</label>
                            <textarea name="content" id="content" rows="10" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('content') }}</textarea>
                        </div>
                        <div class="flex justify-end pt-4">
                            <a href="{{ route('admin.practice.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 mr-3">Batal</a>
                            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 font-semibold">Simpan Proyek</button>
                        </div>
                    </div>
                </form>
            </div>
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