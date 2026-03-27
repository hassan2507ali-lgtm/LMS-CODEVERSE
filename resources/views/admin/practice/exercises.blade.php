@extends('layouts.app')

@section('content')
<div class="py-12 pt-24">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="mb-4">
            <a href="{{ route('admin.practice.index') }}" class="inline-flex items-center text-teal-500 hover:text-teal-700 font-semibold transition duration-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar Practice
            </a>
        </div>

        <h2 class="text-3xl font-semibold mb-6 text-gray-800">
            Kelola Exercises: <span class="font-bold text-teal-600">{{ $practice->title }}</span>
        </h2>

        @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="mb-6">
            <button onclick="document.getElementById('aiModuleModal').classList.remove('hidden')" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-md shadow hover:shadow-lg transition flex items-center gap-2 text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                AI Generate Modul Lanjutan ✨
            </button>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 mb-6">
            <div class="p-6 text-gray-900">
                <h3 class="text-xl font-semibold mb-4">Tambah Exercise Baru</h3>
                <form action="{{ route('admin.practice.exercises.store', $practice->id) }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="section_name" class="block text-sm font-medium text-gray-700 mb-1">Pilih / Ketik Nama Modul</label>
                            <input list="module_list" name="section_name" id="section_name" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500" placeholder="Pilih modul yang ada atau ketik baru...">
                            <datalist id="module_list">
                                @foreach($groupedExercises->keys() as $moduleName)
                                    @if($moduleName !== 'General Exercises')
                                        <option value="{{ $moduleName }}">
                                    @endif
                                @endforeach
                            </datalist>
                        </div>

                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Exercise *</label>
                            <input type="text" name="title" id="title" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500" placeholder="Contoh: Menampilkan Semua Data" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-1">Tingkat Kesulitan *</label>
                            <select name="difficulty" id="difficulty" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500" required>
                                <option value="easy">Easy (Mudah)</option>
                                <option value="medium">Medium (Sedang)</option>
                                <option value="hard">Hard (Sulit)</option>
                            </select>
                        </div>

                        <div>
                            <label for="language" class="block text-sm font-medium text-gray-700 mb-1">Bahasa Pemrograman *</label>
                            <select name="language" id="language" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500" required>
                                <option value="sql">SQL</option>
                                <option value="python">Python</option>
                                <option value="php">PHP</option>
                                <option value="javascript">JavaScript</option>
                                <option value="html">HTML/CSS</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Singkat</label>
                        <input type="text" name="description" id="description" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500" placeholder="Deskripsi singkat tentang exercise ini">
                    </div>

                    <div>
                        <label for="instructions" class="block text-sm font-medium text-gray-700 mb-1">Instruksi (Step-by-step)</label>
                        <textarea name="instructions" id="instructions" rows="4" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500" placeholder="1. Langkah pertama&#10;2. Langkah kedua&#10;3. Langkah ketiga"></textarea>
                    </div>

                    <div>
                        <label for="starter_code" class="block text-sm font-medium text-gray-700 mb-1">Starter Code (Kode Awal)</label>
                        <textarea name="starter_code" id="starter_code" rows="6" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 font-mono text-sm" placeholder="// Kode awal untuk user"></textarea>
                    </div>

                    <div>
                        <label for="solution_code" class="block text-sm font-medium text-gray-700 mb-1">Solution Code (Solusi)</label>
                        <textarea name="solution_code" id="solution_code" rows="6" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 font-mono text-sm" placeholder="// Kode solusi lengkap"></textarea>
                    </div>

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

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
            <div class="p-6 text-gray-900">
                <h3 class="text-xl font-semibold mb-6 border-b pb-2">Daftar Exercises ({{ $practice->exercises()->count() }})</h3>
                
                @forelse ($groupedExercises as $sectionName => $exercises)
                    <div class="mb-10 last:mb-0">
                        
                        <div class="flex items-center justify-between mb-4 bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <div class="flex items-center gap-3">
                                <div class="bg-teal-100 text-teal-700 p-2 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                </div>
                                <h4 class="text-lg font-bold text-gray-800">{{ $sectionName }}</h4>
                            </div>
                            
                            <button onclick="openEditModuleModal('{{ $sectionName }}')" class="flex items-center gap-1 text-sm text-teal-600 hover:text-teal-800 font-medium px-3 py-1.5 bg-teal-50 hover:bg-teal-100 rounded-md transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Edit Modul
                            </button>
                        </div>

                        {{-- Daftar Soal di Dalam Modul (SEKARANG BISA DI-DRAG) --}}
                        <div class="sortable-module-list ml-2 pl-4 border-l-2 border-teal-100 space-y-4 min-h-[50px]" data-section-name="{{ $sectionName }}">
                            @foreach ($exercises as $exercise)
                                <div class="exercise-item bg-gray-50 rounded-lg border border-gray-200 p-4 shadow-sm" data-id="{{ $exercise->id }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 flex items-start gap-3">
                                            
                                            {{-- 🔥 IKON PEGANGAN (GRIP) UNTUK DRAG --}}
                                            <div class="drag-handle mt-1 cursor-grab active:cursor-grabbing text-gray-400 hover:text-teal-600 transition" title="Drag untuk memindahkan">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                                            </div>

                                            <div class="flex-1">
                                                <div class="flex items-center gap-3 mb-2">
                                                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-teal-100 text-teal-700 font-bold text-sm shadow-sm">
                                                        {{ $exercise->order }}
                                                    </span>
                                                    <div>
                                                        <h4 class="text-lg font-semibold text-gray-800">{{ $exercise->title }}</h4>
                                                        <span class="inline-block px-2 py-1 text-xs rounded-full
                                                            {{ $exercise->difficulty === 'easy' ? 'bg-green-100 text-green-700' : '' }}
                                                            {{ $exercise->difficulty === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                                            {{ $exercise->difficulty === 'hard' ? 'bg-red-100 text-red-700' : '' }}">
                                                            {{ ucfirst($exercise->difficulty) }}
                                                        </span>
                                                        <span class="inline-block px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 ml-1 font-mono">
                                                            {{ strtoupper($exercise->language ?? 'PYTHON') }}
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                @if($exercise->description)
                                                    <p class="text-gray-600 text-sm mb-2">{{ $exercise->description }}</p>
                                                @endif

                                                <div class="text-xs text-gray-500 space-y-1">
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                        <span>Instructions: <span class="font-medium {{ $exercise->instructions ? 'text-gray-700' : '' }}">{{ $exercise->instructions ? 'Yes' : 'No' }}</span></span>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                                                        <span>Starter Code: <span class="font-medium {{ $exercise->starter_code ? 'text-gray-700' : '' }}">{{ $exercise->starter_code ? 'Yes' : 'No' }}</span></span>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        <span>Solution: <span class="font-medium {{ $exercise->solution_code ? 'text-gray-700' : '' }}">{{ $exercise->solution_code ? 'Yes' : 'No' }}</span></span>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                                                        <span>Hints: <span class="font-medium {{ $exercise->hints ? 'text-gray-700' : '' }}">{{ $exercise->hints ? 'Yes' : 'No' }}</span></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex flex-col gap-2 ml-4">
                                            <a href="{{ route('admin.practice.exercises.edit', [$practice->id, $exercise->id]) }}" 
                                               class="px-4 py-2 bg-indigo-500 text-white rounded-md hover:bg-indigo-600 text-sm font-semibold text-center transition-colors">
                                                Edit
                                            </a>

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
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        <p class="text-gray-500 font-medium">Belum ada exercise untuk practice ini.</p>
                        <p class="text-gray-400 text-sm mt-1">Gunakan tombol Generate AI di atas atau tambah manual.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

{{-- 🔥 MODAL AI GENERATE SUB-MODUL --}}
<div id="aiModuleModal" class="fixed inset-0 z-50 hidden bg-gray-900/60 backdrop-blur-sm overflow-y-auto h-full w-full flex items-center justify-center">
    <div class="relative mx-auto p-8 border w-full max-w-lg shadow-2xl rounded-2xl bg-white">
        
        <div class="text-center mb-6">
            <h3 class="text-2xl font-bold text-gray-900 font-mono">Tambah Sub-Modul AI ✨</h3>
            <p class="text-sm text-gray-500 mt-2">AI akan melanjutkan materi <b class="text-teal-600">{{ $practice->title }}</b> dengan soal-soal baru.</p>
        </div>

        <form action="{{ route('admin.practice.exercises.generate-ai', $practice->id) }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Bahasa Pemrograman</label>
                <select name="language" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="sql">SQL</option>
                    <option value="python">Python</option>
                    <option value="php">PHP</option>
                    <option value="javascript">JavaScript</option>
                    <option value="html">HTML/CSS</option>
                </select>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Topik Modul Lanjutan</label>
                <input type="text" name="module_topic" required placeholder="Contoh: Part 2: Joins & Aggregations" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div class="flex items-center gap-3 mt-8">
                <button type="button" onclick="document.getElementById('aiModuleModal').classList.add('hidden')" class="w-1/2 px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition font-medium">Batal</button>
                <button type="submit" onclick="this.innerHTML='Generating... 🧠'; this.classList.add('opacity-70', 'cursor-not-allowed');" class="w-1/2 px-4 py-3 bg-gradient-to-r from-teal-500 to-teal-600 text-white rounded-xl hover:shadow-lg transition font-medium">Buat Modul 🚀</button>
            </div>
        </form>
    </div>
</div>

{{-- 🔥 MODAL EDIT NAMA MODUL --}}
<div id="editModuleModal" class="fixed inset-0 z-50 hidden bg-gray-900/60 backdrop-blur-sm overflow-y-auto h-full w-full flex items-center justify-center">
    <div class="relative mx-auto p-8 border w-full max-w-md shadow-2xl rounded-2xl bg-white">
        
        <div class="text-center mb-6">
            <h3 class="text-xl font-bold text-gray-900">Ubah Nama Modul</h3>
            <p class="text-sm text-gray-500 mt-1">Semua soal di dalam modul ini akan otomatis mengikuti nama baru.</p>
        </div>

        <form action="{{ route('admin.practice.exercises.module.update', $practice->id) }}" method="POST">
            @csrf
            <input type="hidden" name="old_name" id="old_module_name">
            
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Modul Baru</label>
                <input type="text" name="new_name" id="new_module_name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div class="flex items-center gap-3 mt-6">
                <button type="button" onclick="document.getElementById('editModuleModal').classList.add('hidden')" class="w-1/2 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition font-medium">Batal</button>
                <button type="submit" class="w-1/2 px-4 py-2.5 bg-teal-500 text-white rounded-xl hover:bg-teal-600 transition font-medium">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

{{-- Script untuk mengisi input old_name secara otomatis --}}
<script>
    function openEditModuleModal(currentName) {
        document.getElementById('old_module_name').value = currentName;
        document.getElementById('new_module_name').value = currentName === 'General Exercises' ? '' : currentName;
        document.getElementById('editModuleModal').classList.remove('hidden');
    }
</script>

{{-- 🔥 LIBRARY SORTABLE JS UNTUK DRAG AND DROP --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Ambil semua kotak modul
        const sortableLists = document.querySelectorAll('.sortable-module-list');
        
        sortableLists.forEach(list => {
            new Sortable(list, {
                group: 'shared-exercises', // Nama grup sama, supaya soal bisa ditarik lintas modul!
                animation: 150,
                handle: '.drag-handle', // Yang bisa ditarik cuma pas klik ikon titik-titik
                ghostClass: 'opacity-50', // Efek transparan pas ditarik
                
                // Ketika selesai di-drop
                onEnd: function (evt) {
                    saveNewOrder();
                }
            });
        });

        function saveNewOrder() {
            let newData = [];
            let lists = document.querySelectorAll('.sortable-module-list');
            
            // Tampilkan tulisan loading di tombol kembali (biar admin tau lagi nyimpen)
            let btnBack = document.querySelector('a[href="{{ route('admin.practice.index') }}"]');
            let originalText = btnBack.innerHTML;
            btnBack.innerHTML = 'Menyimpan urutan... ⏳';

            // Loop semua kotak modul satu per satu dari atas ke bawah
            lists.forEach(list => {
                let sectionName = list.getAttribute('data-section-name');
                if (sectionName === 'General Exercises') sectionName = null;

                // Loop semua soal yang ada di dalam kotak modul tersebut
                list.querySelectorAll('.exercise-item').forEach(item => {
                    newData.push({
                        id: item.getAttribute('data-id'),
                        section_name: sectionName
                    });
                });
            });

            // Kirim data urutan baru ke backend (Controller) secara diam-diam
            fetch('{{ route('admin.practice.exercises.reorder', $practice->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ items: newData })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // Reload halaman biar nomor urutnya (1, 2, 3) di layar ikut terupdate
                    window.location.reload(); 
                } else {
                    alert('Gagal menyimpan urutan!');
                    btnBack.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                btnBack.innerHTML = originalText;
            });
        }
    });
</script>
@endsection