@extends('layouts.app')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- 🔥 IMPORT SQL.JS (WebAssembly) UNTUK MESIN SQL DI BROWSER --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sql.js/1.8.0/sql-wasm.js"></script>
<script src="https://cdn.jsdelivr.net/pyodide/v0.24.1/full/pyodide.js"></script>

@php
    $langType = strtolower($exercise->language ?? 'python');
    
    if ($langType === 'html') {
        $fileName = 'index.html';
        $panelTitle = 'Browser Preview';
        $defaultCode = $exercise->starter_code ?? "<h1>Hello World!</h1>";
    } elseif ($langType === 'javascript') {
        $fileName = 'script.js';
        $panelTitle = 'Console Output';
        $defaultCode = $exercise->starter_code ?? "console.log('Hello');";
    } elseif ($langType === 'sql') {
        $fileName = 'query.sql';
        $panelTitle = 'Query Result Table';
        $defaultCode = $exercise->starter_code ?? "-- Tulis query SQL di bawah ini \nSELECT * FROM ...";
    } else {
        $fileName = 'script.py';
        $panelTitle = 'Terminal Output';
        $defaultCode = $exercise->starter_code ?? "# Write code below \n\nprint('hi')";
    }

    $nextExercise = $practice->exercises->where('order', '>', $exercise->order)->sortBy('order')->first();
    
    $nextUrl = $nextExercise 
        ? route('practice.exercise.start', ['slug' => $practice->slug, 'exercise' => $nextExercise->id]) 
        : route('practice.show', $practice->slug);
@endphp

<div class="fixed inset-0 z-50 flex flex-col bg-gray-50 text-gray-800 font-sans overflow-hidden">
    
    <div class="h-12 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 shadow-sm z-10">
        <div class="flex items-center space-x-4">
            <a href="{{ route('practice.show', $practice->slug) }}" class="text-gray-400 hover:text-gray-900 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </a>
            <span class="text-sm font-bold text-gray-700 font-mono">Exercise ({{ strtoupper($langType) }})</span>
        </div>
    </div>

    <div class="flex-1 flex flex-col lg:flex-row overflow-hidden relative">
        
        <div class="w-full lg:w-[40%] flex flex-col border-r border-gray-200 bg-white overflow-y-auto pb-16 lg:pb-24">
            <div class="p-6 sm:p-10 flex-1">
                <h1 class="text-3xl md:text-4xl font-bold font-mono text-gray-900 mb-4 tracking-wide">
                    {{ $exercise->title }}
                </h1>
                
                <div class="text-indigo-600 font-mono text-lg font-bold mb-6">
                    # {{ $practice->title }}
                </div>

                <div class="prose prose-slate max-w-none text-gray-600 text-sm md:text-base leading-relaxed space-y-4">
                    @if($exercise->description)
                        <p class="text-gray-500 italic">{{ $exercise->description }}</p>
                    @endif
                    
                    <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-100 relative">
                        <h3 class="text-indigo-800 font-bold mb-2">📌 Instruksi:</h3>
                        {!! nl2br(e($exercise->instructions ?? 'Selesaikan kode di samping agar program berjalan dengan benar.')) !!}
                        
                        {{-- TOMBOL LIHAT SKEMA TABEL KHUSUS SQL --}}
                        @if($langType === 'sql' && $practice->database_file)
                            <button onclick="showSqlSchema()" class="mt-4 flex items-center gap-1 px-3 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded shadow-sm hover:bg-indigo-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                                Lihat Tabel & Kolom Dataset
                            </button>
                        @endif
                    </div>

                    @if($exercise->hints)
                        <div class="mt-4">
                            <details class="group">
                                <summary class="flex justify-between items-center font-medium cursor-pointer list-none text-teal-600 hover:text-teal-700">
                                    <span>💡 Butuh Bantuan? (Klik untuk Hint)</span>
                                    <span class="transition group-open:rotate-180">
                                        <svg fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
                                    </span>
                                </summary>
                                <p class="text-gray-600 mt-3 group-open:animate-fadeIn bg-gray-50 p-3 rounded border">
                                    {!! nl2br(e($exercise->hints)) !!}
                                </p>
                            </details>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="w-full lg:w-[60%] flex flex-col bg-gray-100 pb-16 lg:pb-24">
            
            <div class="flex-1 flex flex-col bg-white m-2 rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-100 pt-2 px-4 flex border-b border-gray-200">
                    <div class="bg-white text-gray-900 px-4 py-2 font-mono text-sm font-bold rounded-t-md flex items-center border-t-2 border-indigo-500 shadow-sm -mb-px z-10">
                        {{ $fileName }}
                    </div>
                </div>

                <div class="flex-1 flex overflow-hidden relative">
                    <div class="w-12 bg-gray-50 text-gray-400 text-right pr-3 py-4 font-mono text-sm select-none border-r border-gray-100">
                        1<br>2<br>3<br>4<br>5<br>6
                    </div>
                    <textarea id="codeEditor" class="flex-1 p-4 font-mono text-sm text-gray-800 focus:outline-none resize-none bg-white w-full" spellcheck="false">{{ $defaultCode }}</textarea>
                </div>

                <div class="bg-white border-t border-gray-100 p-3 flex justify-between items-center bg-gray-50/50">
                    <div class="flex space-x-2"></div>
                    <div class="flex space-x-3">
                        <button id="runBtn" class="flex items-center bg-gray-800 text-white px-5 py-2 rounded shadow hover:bg-black transition font-bold text-sm">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
                            Run Code
                        </button>
                        <button id="submitBtn" class="bg-[#0ea5e9] border-b-4 border-[#0284c7] active:border-b-0 active:translate-y-1 text-white px-5 py-2 rounded font-bold text-sm transition-all shadow-sm">
                            Submit Answer
                        </button>
                    </div>
                </div>
            </div>

            <div class="h-1/3 min-h-[200px] bg-gray-100 border-t border-gray-200 flex flex-col mx-2 mb-2 rounded-lg overflow-hidden shadow-sm border border-gray-200">
                <div class="bg-gray-200 text-gray-600 text-xs px-4 py-2 font-mono border-b border-gray-300 flex justify-between">
                    <span class="font-bold">{{ $panelTitle }}</span>
                </div>
                {{-- CLASS OVERFLOW DIUBAH AGAR BISA SCROLL TABEL KE SAMPING UNTUK SQL --}}
                <div id="terminalOutput" class="flex-1 p-4 font-mono text-sm text-gray-800 overflow-auto whitespace-pre-wrap bg-gray-50 relative">
                    <span class="text-gray-400 italic">Output akan muncul di sini...</span>
                </div>
            </div>

        </div>

        <div class="h-16 bg-white border-t border-gray-200 flex items-center justify-between px-4 sm:px-6 absolute bottom-0 w-full z-20 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
            <div class="flex items-center space-x-4">
                <a href="{{ route('practice.show', $practice->slug) }}" class="w-10 h-10 bg-gray-50 border border-gray-300 rounded flex items-center justify-center text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </a>
                <div class="flex flex-col hidden sm:flex">
                    <span class="text-sm font-bold text-gray-900">{{ $exercise->title }}</span>
                    <div class="flex items-center text-xs font-mono text-gray-500">
                        <span>Exercise</span>
                    </div>
                </div>
            </div>

            <div class="flex space-x-3">
                <a href="{{ route('practice.show', $practice->slug) }}" class="bg-white border-2 border-gray-300 text-gray-600 px-6 py-2 rounded font-bold text-sm hover:bg-gray-50 transition shadow-sm inline-flex items-center justify-center">Back</a>
                <a href="{{ $nextUrl }}" class="bg-[#facc15] border-b-4 border-[#ca8a04] active:border-b-0 active:translate-y-1 text-gray-900 px-8 py-2 rounded font-black uppercase tracking-wider text-sm transition-all shadow-sm inline-flex items-center justify-center">
                    {{ $nextExercise ? 'Next' : 'Finish' }}
                </a>
            </div>
        </div>
    </div>
</div>

<textarea id="solutionCode" class="hidden">{{ $exercise->solution_code }}</textarea>

<script>
    const executionLang = "{{ $langType }}"; 
    // Ambil URL dataset SQLite dari server Laravel
    const dbUrl = "{{ $practice->database_file ? asset('storage/' . $practice->database_file) : '' }}";
    
    // VARIABLES ENGINE
    let pyodideReadyPromise = null;
    let sqlDb = null;

    // ==========================================
    // INIT ENGINE SQL & PYTHON
    // ==========================================
    async function initSQL() {
        if (sqlDb) return sqlDb;
        const terminal = document.getElementById('terminalOutput');
        terminal.innerHTML = '<span class="text-indigo-600 font-bold">Mendownload dataset ke browser... ⏳</span>';
        
        try {
            const SQL = await initSqlJs({
                locateFile: file => `https://cdnjs.cloudflare.com/ajax/libs/sql.js/1.8.0/${file}`
            });
            
            if (dbUrl) {
                const response = await fetch(dbUrl);
                const buffer = await response.arrayBuffer();
                sqlDb = new SQL.Database(new Uint8Array(buffer));
            } else {
                sqlDb = new SQL.Database(); // Kosong jika tidak ada file
            }
            return sqlDb;
        } catch(e) {
            terminal.innerHTML = `<span class="text-red-500 font-bold">Gagal memuat Database SQL: ${e.message}</span>`;
            throw e;
        }
    }

    async function initPyodide() {
        if (!pyodideReadyPromise) pyodideReadyPromise = loadPyodide();
        return await pyodideReadyPromise;
    }

    function runJS(code) {
        let output = "";
        const originalLog = console.log;
        console.log = function(...args) { output += args.join(' ') + '\n'; };
        try { eval(code); } catch(e) { output += "Error: " + e.message; }
        console.log = originalLog;
        return output.trim();
    }

    // ==========================================
    // RENDER HTML TABLE UNTUK HASIL SQL
    // ==========================================
    function renderSqlTable(res) {
        if (!res || res.length === 0) return '<span class="text-gray-500 font-bold italic">Query berhasil, tidak ada baris yang ditampilkan.</span>';
        
        let html = '<table class="min-w-full divide-y divide-gray-200 border border-gray-300 shadow-sm bg-white"><thead class="bg-gray-100"><tr>';
        res[0].columns.forEach(col => {
            html += `<th class="px-3 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-200">${col}</th>`;
        });
        html += '</tr></thead><tbody class="divide-y divide-gray-200">';
        
        res[0].values.forEach((row, index) => {
            let bgClass = index % 2 === 0 ? 'bg-white' : 'bg-gray-50';
            html += `<tr class="${bgClass} hover:bg-indigo-50 transition">`;
            row.forEach(val => {
                html += `<td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">${val === null ? '<i class="text-gray-400">NULL</i>' : val}</td>`;
            });
            html += '</tr>';
        });
        html += '</tbody></table>';
        return html;
    }

    // FITUR LIHAT SKEMA TABEL SQL
    async function showSqlSchema() {
        try {
            let db = await initSQL();
            let res = db.exec("SELECT name as Table_Name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%';");
            let html = renderSqlTable(res);
            
            Swal.fire({
                title: 'Struktur Dataset',
                html: `<div class="overflow-x-auto text-left">${html}</div><p class="text-xs text-gray-500 mt-3">Kamu bisa melakukan SELECT ke tabel-tabel di atas.</p>`,
                width: 600
            });
        } catch(e) {
            Swal.fire('Error', 'Gagal memuat struktur tabel.', 'error');
        }
    }

    // ==========================================
    // TOMBOL 1: RUN CODE (Hanya Menampilkan)
    // ==========================================
    document.getElementById('runBtn').addEventListener('click', async function() {
        const code = document.getElementById('codeEditor').value;
        const terminal = document.getElementById('terminalOutput');
        const runBtn = document.getElementById('runBtn');

        runBtn.innerHTML = 'Running...'; runBtn.disabled = true;
        
        if (executionLang === 'html') {
            terminal.innerHTML = ''; terminal.style.padding = '0'; terminal.style.backgroundColor = '#ffffff';
            const iframe = document.createElement('iframe');
            iframe.style.width = '100%'; iframe.style.height = '100%'; iframe.style.border = 'none';
            terminal.appendChild(iframe);
            iframe.contentWindow.document.open(); iframe.contentWindow.document.write(code); iframe.contentWindow.document.close();
            
            runBtn.innerHTML = 'Run Code'; runBtn.disabled = false;
        } 
        else if (executionLang === 'sql') {
            terminal.style.padding = '0'; terminal.style.backgroundColor = '#ffffff';
            try {
                let db = await initSQL();
                let result = db.exec(code);
                terminal.innerHTML = renderSqlTable(result);
            } catch (err) {
                terminal.style.padding = '1rem'; terminal.style.backgroundColor = '#fef2f2';
                terminal.innerHTML = `<div class="text-red-600 font-mono font-bold">SQL Error:</div><div class="text-red-500 mt-1">${err.message}</div>`;
            }
            runBtn.innerHTML = 'Run Code'; runBtn.disabled = false;
        }
        else if (executionLang === 'javascript') {
            terminal.style.padding = '1rem'; terminal.style.backgroundColor = '#f9fafb';
            let out = runJS(code);
            terminal.innerHTML = `<span class="text-green-600">user@codeverse</span>:<span class="text-blue-600">~</span>$ node script.js<br><br><span class="text-gray-800 font-semibold">${out.replace(/\n/g, '<br>')}</span>`;
            runBtn.innerHTML = 'Run Code'; runBtn.disabled = false;
        }
        else { // PYTHON
            terminal.style.padding = '1rem'; terminal.style.backgroundColor = '#f9fafb';
            terminal.innerHTML = '<span class="text-indigo-600 font-bold">Menyiapkan mesin Python... 🐍</span>';
            try {
                let pyodide = await initPyodide();
                pyodide.runPython(`import sys, io, builtins, js\nsys.stdout = io.StringIO()\ndef custom_input(p=""): return js.prompt(p) or ""\nbuiltins.input = custom_input`);
                await pyodide.runPythonAsync(code);
                let stdout = pyodide.runPython("sys.stdout.getvalue()");
                terminal.innerHTML = `<span class="text-green-600">user@codeverse</span>:<span class="text-blue-600">~</span>$ python script.py<br><br><span class="text-gray-800 font-semibold">${stdout.replace(/\n/g, '<br>')}</span>`;
            } catch (error) {
                terminal.innerHTML = `<span class="text-red-600 font-mono bg-red-50 p-2 block rounded mt-2">${error.message.replace(/\n/g, '<br>')}</span>`;
            } finally {
                runBtn.innerHTML = 'Run Code'; runBtn.disabled = false;
            }
        }
    });

    // ==========================================
    // TOMBOL 2: SUBMIT ANSWER (AUTO-GRADER)
    // ==========================================
    document.getElementById('submitBtn').addEventListener('click', async function() {
        const userCode = document.getElementById('codeEditor').value;
        const solutionCode = document.getElementById('solutionCode').value;
        const submitBtn = document.getElementById('submitBtn');

        if(!solutionCode || solutionCode.trim() === "") {
            Swal.fire('Oops!', 'Soal ini belum memiliki kunci jawaban dari Admin.', 'info');
            return;
        }

        submitBtn.innerHTML = 'Mengecek...'; submitBtn.disabled = true;

        try {
            let isCorrect = false;

            if (executionLang === 'html') {
                let userClean = userCode.replace(/\s+/g, '').toLowerCase();
                let solClean = solutionCode.replace(/\s+/g, '').toLowerCase();
                isCorrect = userClean.includes(solClean) || userClean === solClean;
            } 
            else if (executionLang === 'sql') {
                let db = await initSQL();
                let expectedOut = []; let userOut = [];
                
                try { expectedOut = db.exec(solutionCode); } catch(e) { console.error("Kunci jawaban error", e); }
                try { userOut = db.exec(userCode); } catch(e) { }

                // Pengecekan SQL: Apakah array output tabel user SAMA PERSIS dengan array output tabel kunci jawaban?
                isCorrect = (JSON.stringify(expectedOut) === JSON.stringify(userOut)) && userOut.length > 0;
                
                if(!isCorrect) {
                    Swal.fire({
                        icon: 'error', title: 'Data Tabel Belum Sesuai',
                        text: 'Hasil query kamu menghasilkan kolom atau baris yang berbeda dengan yang diminta.',
                        confirmButtonColor: '#ef4444'
                    });
                }
            }
            else if (executionLang === 'javascript') {
                let userOut = runJS(userCode);
                let expectedOut = runJS(solutionCode);
                isCorrect = (userOut === expectedOut && expectedOut !== "");
            }
            else { // PYTHON
                let pyodide = await initPyodide();
                pyodide.runPython(`import sys, io\nsys.stdout = io.StringIO()`);
                await pyodide.runPythonAsync(solutionCode);
                let expectedOut = pyodide.runPython("sys.stdout.getvalue()").trim();

                pyodide.runPython(`sys.stdout = io.StringIO()`);
                await pyodide.runPythonAsync(userCode);
                let userOut = pyodide.runPython("sys.stdout.getvalue()").trim();

                isCorrect = (userOut === expectedOut && expectedOut !== "");
                if(!isCorrect) {
                    Swal.fire({
                        icon: 'error', title: 'Jawaban Belum Tepat',
                        html: `Output yang diharapkan:<br><b class="text-green-600">${expectedOut}</b><br><br>Output programmu:<br><b class="text-red-600">${userOut}</b>`
                    });
                }
            }

            // JIKA BENAR!
            if(isCorrect) {
                try {
                    await fetch("{{ route('practice.exercise.complete', $exercise->id) }}", {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });
                } catch (err) { }

                Swal.fire({
                    icon: 'success', title: 'Luar Biasa! 🎉',
                    text: 'Tabel hasil query kamu sudah tepat sesuai instruksi!',
                    confirmButtonText: '{{ $nextExercise ? "Lanjut ke Soal Berikutnya" : "Kembali ke Daftar Soal" }}',
                    confirmButtonColor: '#0ea5e9'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{!! $nextUrl !!}";
                    }
                });
            } else if (executionLang !== 'python' && executionLang !== 'sql') {
                 Swal.fire('Salah!', 'Coba periksa lagi kodenya. Hasil belum sesuai instruksi.', 'error');
            }

        } catch (error) {
            Swal.fire('Error Kode!', 'Terdapat syntax error pada kodemu, perbaiki dan coba lagi.', 'error');
        } finally {
            submitBtn.innerHTML = 'Submit Answer'; submitBtn.disabled = false;
        }
    });
</script>
@endsection