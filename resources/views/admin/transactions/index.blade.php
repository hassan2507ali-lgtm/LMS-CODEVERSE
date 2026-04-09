@extends('layouts.app')

@section('content')
{{-- 🔥 MANTRA MENGHILANGKAN PADDING & GRADASI BAWAAN LAYOUT KHUSUS ADMIN --}}
<style>
    footer, .footer, #footer { display: none !important; }
    /* Memaksa konten menempel ke atas jika layouts.app punya padding bawaan */
    .py-12, .py-8 { padding-top: 0 !important; padding-bottom: 0 !important; }
    
    /* MANTRA BARU: Paksa background body halaman ini jadi putih bersih & hapus gradasi! */
    body { 
        background-color: #ffffff !important; 
        background-image: none !important; 
    }
</style>

{{-- 🔥 DIBUAT FLEX ITEMS-START & BG-WHITE AGAR NYATU DENGAN HEADER --}}
<div class="flex items-start bg-white min-h-screen w-full">

    {{-- ========================================== --}}
    {{-- 🔥 SIDEBAR ADMIN PINTAR (ANTI KEPOTONG) --}}
    {{-- ========================================== --}}
    <aside class="w-64 bg-white border-r border-gray-200 shadow-sm hidden md:flex flex-col sticky top-0 h-screen z-10 overflow-y-auto">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Ruang Kendali</h2>
            <p class="text-lg font-bold text-gray-800 font-mono">Admin Panel</p>
        </div>

        <nav class="flex-1 p-4 space-y-1">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition {{ request()->routeIs('dashboard') ? 'bg-teal-50 text-teal-600' : 'text-gray-600 hover:bg-gray-50 hover:text-teal-600' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>

            <a href="{{ route('admin.practice.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition {{ request()->routeIs('admin.practice.*') ? 'bg-teal-50 text-teal-600' : 'text-gray-600 hover:bg-gray-50 hover:text-teal-600' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                Kelola Practice
            </a>

            <a href="{{ route('admin.transactions') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition {{ request()->routeIs('admin.transactions') ? 'bg-teal-50 text-teal-600' : 'text-gray-600 hover:bg-gray-50 hover:text-teal-600' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path></svg>
                Data Transaksi
            </a>

            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition {{ request()->routeIs('admin.users.*') ? 'bg-teal-50 text-teal-600' : 'text-gray-600 hover:bg-gray-50 hover:text-teal-600' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Kelola User
            </a>
        </nav>
    </aside>

    {{-- ========================================== --}}
    {{-- 🔥 MAIN CONTENT --}}
    {{-- ========================================== --}}
    <main class="flex-1 p-8 min-h-screen">
        
        {{-- Header & Statistik Singkat --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Laporan Transaksi 💰</h1>
                <p class="text-gray-500 mt-1">Pantau semua penjualan Kelas dan Latihan Premium.</p>
            </div>
            
            <div class="flex gap-4">
                <div class="bg-gray-50 p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-center min-w-[150px]">
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Total Penjualan</p>
                    <p class="text-2xl font-bold text-teal-600">{{ $totalSales }} <span class="text-sm font-medium text-gray-400">item</span></p>
                </div>
                <div class="bg-gray-50 p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-center min-w-[200px]">
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Total Pendapatan</p>
                    <p class="text-2xl font-bold text-green-500">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- 🔥 FORM PENCARIAN & FILTER TANGGAL --}}
        <form action="{{ route('admin.transactions') }}" method="GET" class="mb-6 bg-gray-50 p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-4 items-end">
            
            {{-- Input Pencarian --}}
            <div class="flex-1 w-full">
                <label for="search" class="block text-sm font-semibold text-gray-600 mb-1.5">Cari Transaksi</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nama, email, atau ID TRX..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm transition shadow-sm">
                </div>
            </div>

            {{-- Input Tanggal Mulai --}}
            <div class="w-full md:w-auto">
                <label for="start_date" class="block text-sm font-semibold text-gray-600 mb-1.5">Dari Tanggal</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm transition shadow-sm text-gray-600">
            </div>

            {{-- Input Tanggal Selesai --}}
            <div class="w-full md:w-auto">
                <label for="end_date" class="block text-sm font-semibold text-gray-600 mb-1.5">Sampai Tanggal</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm transition shadow-sm text-gray-600">
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex gap-2 w-full md:w-auto mt-4 md:mt-0">
                <button type="submit" class="px-5 py-2.5 bg-teal-500 text-white rounded-xl hover:bg-teal-600 text-sm font-bold transition shadow-sm w-full md:w-auto">
                    Filter Data
                </button>
                
                {{-- Tombol Reset hanya muncul kalau ada filter yang sedang aktif --}}
                @if(request()->anyFilled(['search', 'start_date', 'end_date']))
                    <a href="{{ route('admin.transactions') }}" class="px-5 py-2.5 bg-white text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-100 text-sm font-bold transition flex items-center justify-center w-full md:w-auto">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        {{-- Tabel Transaksi --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-10">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 text-xs uppercase tracking-wider">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-bold">Tgl / ID Transaksi</th>
                            <th scope="col" class="px-6 py-4 font-bold">Pembeli</th>
                            <th scope="col" class="px-6 py-4 font-bold">Item yang Dibeli</th>
                            <th scope="col" class="px-6 py-4 font-bold text-right">Nominal</th>
                            <th scope="col" class="px-6 py-4 font-bold text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($transactions as $trx)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-800">{{ $trx->created_at->format('d M Y, H:i') }}</div>
                                    <div class="text-[11px] text-gray-400 font-mono mt-1">{{ $trx->reference_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-800">{{ $trx->user->name ?? 'User Dihapus' }}</div>
                                    <div class="text-[11px] text-gray-500 mt-0.5">{{ $trx->user->email ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($trx->course_id)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-indigo-50 text-indigo-600 mb-1.5 border border-indigo-100 uppercase tracking-widest">
                                            📚 Course
                                        </span>
                                        <div class="text-sm font-medium text-gray-800 line-clamp-1">{{ $trx->course->title ?? 'Course Dihapus' }}</div>
                                    @elseif($trx->practice_id)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-teal-50 text-teal-600 mb-1.5 border border-teal-100 uppercase tracking-widest">
                                            💻 Practice
                                        </span>
                                        <div class="text-sm font-medium text-gray-800 line-clamp-1">{{ $trx->practice->title ?? 'Practice Dihapus' }}</div>
                                    @else
                                        <span class="text-sm text-gray-400 italic">Item tidak diketahui</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-black text-gray-800 text-right">
                                    Rp {{ number_format($trx->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($trx->status == 'success')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-600 border border-green-100">
                                            ✅ Success
                                        </span>
                                    @elseif($trx->status == 'pending')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-50 text-yellow-600 border border-yellow-100">
                                            ⏳ Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600 border border-red-100">
                                            ❌ {{ ucfirst($trx->status) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-200">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                    </div>
                                    <p class="text-gray-500 font-medium">Belum ada data transaksi penjualan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            @if($transactions->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>

    </main>
</div>
@endsection