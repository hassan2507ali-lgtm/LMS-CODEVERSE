@extends('layouts.app')

@section('content')

@php
    // 1. DETEKSI BAHASA OTOMATIS BERDASARKAN KATEGORI/JUDUL
    // Mengambil kata kunci dari kategori atau judul
    $keyword = strtolower(($practice->category ?? '') . ' ' . $practice->title);
    
    // Menentukan jenis bahasa
    $isHtml = str_contains($keyword, 'html') || str_contains($keyword, 'css');
    $isJs = str_contains($keyword, 'javascript') || str_contains($keyword, 'js');
    
    // Mengatur default UI berdasarkan bahasa
    if ($isHtml) {
        $langType = 'html';
        $fileName = 'index.html';
        $panelTitle = 'Browser Preview';
        $defaultCode = "<h1>Hello World!</h1>\n<p>Tulis kode HTML kamu di bawah ini...</p>";
    } elseif ($isJs) {
        $langType = 'javascript';
        $fileName = 'script.js';
        $panelTitle = 'Console Output';
        $defaultCode = "console.log('Hello JavaScript!');";
    } else {
        $langType = 'python';
        $fileName = 'script.py';
        $panelTitle = 'Terminal Output';
        $defaultCode = "# Write code below 💖\n\nprint('hi')";
    }
@endphp

<div class="fixed inset-0 z-50 flex flex-col bg-gray-50 text-gray-800 font-sans overflow-hidden">
    
    <div class="h-12 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 shadow-sm z-10">
        <div class="flex items-center space-x-4">
            <a href="{{ route('practice.show', $practice->slug) }}" class="text-gray-400 hover:text-gray-900 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </a>
            <span class="text-sm font-bold text-gray-700 font-mono">Exercise ({{ strtoupper($langType) }} Mode)</span>
        </div>
    </div>

    <div class="flex-1 flex flex-col lg:flex-row overflow-hidden">
        
        <div class="w-full lg:w-[40%] flex flex-col border-r border-gray-200 bg-white overflow-y-auto">
            <div class="p-6 sm:p-10 flex-1">
                <h1 class="text-3xl md:text-4xl font-bold font-mono text-gray-900 mb-4 tracking-wide">
                    01. {{ $exercise->title }}
                </h1>
                
                <div class="text-indigo-600 font-mono text-lg font-bold mb-8">
                    # {{ $practice->title }}
                </div>

                <div class="prose prose-slate max-w-none text-gray-600 text-sm md:text-base leading-relaxed">
                    @if($exercise->question)
                        {!! nl2br(e($exercise->question)) !!}
                    @else
                        <p>Welcome to the exercise!</p>
                        <p>Silakan ikuti instruksi dan tulis kode kamu di editor sebelah kanan.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="w-full lg:w-[60%] flex flex-col bg-gray-100">
            
            <div class="flex-1 flex flex-col bg-white m-2 rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-100 pt-2 px-4 flex border-b border-gray-200">
                    <div class="bg-white text-gray-900 px-4 py-2 font-mono text-sm font-bold rounded-t-md flex items-center border-t-2 border-indigo-500 shadow-sm -mb-px z-10">
                        @if($isHtml)
                            <span class="text-orange-500 mr-2">⟨/⟩</span>
                        @else
                            <svg class="w-4 h-4 mr-2 text-indigo-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/></svg>
                        @endif
                        {{ $fileName }}
                    </div>
                </div>

                <div class="flex-1 flex overflow-hidden relative">
                    <div class="w-12 bg-gray-50 text-gray-400 text-right pr-3 py-4 font-mono text-sm select-none border-r border-gray-100">
                        1<br>2<br>3<br>4
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
                        <button class="bg-[#0ea5e9] border-b-4 border-[#0284c7] active:border-b-0 active:translate-y-1 text-white px-5 py-2 rounded font-bold text-sm transition-all shadow-sm">
                            Submit answer
                        </button>
                    </div>
                </div>
            </div>

            <div class="h-1/3 min-h-[200px] bg-gray-100 border-t border-gray-200 flex flex-col mx-2 mb-2 rounded-lg overflow-hidden shadow-sm border border-gray-200">
                <div class="bg-gray-200 text-gray-600 text-xs px-4 py-2 font-mono border-b border-gray-300 flex justify-between">
                    <span class="font-bold">{{ $panelTitle }}</span>
                </div>
                <div id="terminalOutput" class="flex-1 p-4 font-mono text-sm text-gray-800 overflow-y-auto whitespace-pre-wrap bg-gray-50 relative">
                    @if($isHtml)
                        <span class="text-gray-400 italic">Klik "Run Code" untuk melihat preview website.</span>
                    @else
                        <span class="text-green-600">user@codeverse</span>:<span class="text-blue-600">~</span>$ <span class="animate-pulse font-bold">_</span>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <div class="h-16 bg-white border-t border-gray-200 flex items-center justify-between px-4 sm:px-6 relative z-10 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
        <div class="flex items-center space-x-4">
            <button class="w-10 h-10 bg-gray-50 border border-gray-300 rounded flex items-center justify-center text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
            <div class="flex flex-col">
                <span class="text-sm font-bold text-gray-900">{{ $exercise->title }}</span>
                <div class="flex items-center text-xs font-mono text-gray-500">
                    <span>Exercise</span>
                </div>
            </div>
        </div>

        <div class="flex space-x-3">
            <button class="bg-white border-2 border-gray-300 text-gray-600 px-6 py-2 rounded font-bold text-sm hover:bg-gray-50 transition shadow-sm">Back</button>
            <button class="bg-[#facc15] border-b-4 border-[#ca8a04] active:border-b-0 active:translate-y-1 text-gray-900 px-8 py-2 rounded font-black uppercase tracking-wider text-sm transition-all shadow-sm">Next</button>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/pyodide/v0.24.1/full/pyodide.js"></script>

<script>
    const executionLang = "{{ $langType }}"; // Menyimpan mode bahasa (html/python/javascript)
    let pyodideReadyPromise = null;

    async function initPyodide() {
        if (!pyodideReadyPromise) {
            pyodideReadyPromise = loadPyodide();
        }
        return await pyodideReadyPromise;
    }

    document.getElementById('runBtn').addEventListener('click', async function() {
        const code = document.getElementById('codeEditor').value;
        const terminal = document.getElementById('terminalOutput');
        const runBtn = document.getElementById('runBtn');

        runBtn.innerHTML = 'Running...';
        runBtn.disabled = true;

        // JIKA BAHASA ADALAH HTML
        if (executionLang === 'html') {
            // Bersihkan styling terminal agar full screen putih
            terminal.innerHTML = '';
            terminal.style.padding = '0';
            terminal.style.backgroundColor = '#ffffff';

            // Buat Iframe (Jendela Mini Browser)
            const iframe = document.createElement('iframe');
            iframe.style.width = '100%';
            iframe.style.height = '100%';
            iframe.style.border = 'none';
            iframe.style.backgroundColor = 'white';
            
            terminal.appendChild(iframe);
            
            // Masukkan kode HTML ke dalam iframe
            iframe.contentWindow.document.open();
            iframe.contentWindow.document.write(code);
            iframe.contentWindow.document.close();

            // Selesai
            runBtn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg> Run Code';
            runBtn.disabled = false;
        } 
        
        // JIKA BAHASA ADALAH PYTHON
        else {
            terminal.style.padding = '1rem';
            terminal.style.backgroundColor = '#f9fafb';
            terminal.innerHTML = '<span class="text-indigo-600 font-bold">Menyiapkan mesin Python di browser... ⏳</span>';

            try {
                let pyodide = await initPyodide();
                terminal.innerHTML = '<span class="text-indigo-600 font-bold">Mengeksekusi kode... ⏳</span>';

                pyodide.runPython(`
                    import sys
                    import io
                    import builtins
                    import js

                    sys.stdout = io.StringIO()
                    def custom_input(prompt_text=""):
                        result = js.prompt(prompt_text)
                        return result if result is not None else ""
                    builtins.input = custom_input
                `);

                await pyodide.runPythonAsync(code);
                let stdout = pyodide.runPython("sys.stdout.getvalue()");

                let outputHTML = '<span class="text-green-600">user@codeverse</span>:<span class="text-blue-600">~</span>$ python script.py<br><br>';
                if (stdout.trim() !== '') {
                    outputHTML += `<span class="text-gray-800 font-semibold">${stdout.replace(/\n/g, '<br>')}</span>`;
                } else {
                    outputHTML += '<span class="text-gray-400 italic">Kode berhasil dijalankan, tapi tidak ada output (print).</span>';
                }
                terminal.innerHTML = outputHTML;

            } catch (error) {
                let errorText = error.message;
                if (errorText.includes('File "<exec>"')) {
                    errorText = errorText.split('File "<exec>"')[1];
                    errorText = 'File "script.py"' + errorText;
                }
                let outputHTML = '<span class="text-green-600">user@codeverse</span>:<span class="text-blue-600">~</span>$ python script.py<br><br>';
                outputHTML += `<span class="text-red-600 font-mono bg-red-50 p-2 block rounded mt-2">${errorText.replace(/\n/g, '<br>')}</span>`;
                terminal.innerHTML = outputHTML;
            } finally {
                runBtn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg> Run Code';
                runBtn.disabled = false;
            }
        }
    });
</script>
@endsection