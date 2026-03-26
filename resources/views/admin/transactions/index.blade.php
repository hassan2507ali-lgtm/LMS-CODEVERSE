@extends('layouts.app')

@section('content')
<div class="py-12 pt-24 min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        {{-- Header & Statistik Singkat --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Laporan Transaksi</h2>
                <p class="text-gray-500 text-sm mt-1">Pantau semua penjualan Kelas dan Latihan Premium.</p>
            </div>
            
            <div class="flex gap-4">
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 min-w-[150px]">
                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">Total Penjualan</p>
                    <p class="text-2xl font-bold text-indigo-600">{{ $totalSales }} <span class="text-sm font-normal text-gray-400">item</span></p>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 min-w-[200px]">
                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">Total Pendapatan</p>
                    <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        </div>

{{-- 🔥 FORM PENCARIAN & FILTER TANGGAL --}}
<form action="{{ route('admin.transactions') }}" method="GET" class="mb-6 bg-white p-5 rounded-xl shadow-sm border border-gray-200 flex flex-col md:flex-row gap-4 items-end">
    
    {{-- Input Pencarian --}}
    <div class="flex-1">
        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Transaksi</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nama, email, atau ID TRX..." class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm">
        </div>
    </div>

    {{-- Input Tanggal Mulai --}}
    <div>
        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
        <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm">
    </div>

    {{-- Input Tanggal Selesai --}}
    <div>
        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
        <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm">
    </div>

    {{-- Tombol Aksi --}}
    <div class="flex gap-2">
        <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-bold transition shadow-sm">
            Filter Data
        </button>
        
        {{-- Tombol Reset hanya muncul kalau ada filter yang sedang aktif --}}
        @if(request()->anyFilled(['search', 'start_date', 'end_date']))
            <a href="{{ route('admin.transactions') }}" class="px-5 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-bold transition">
                Reset
            </a>
        @endif
    </div>
</form>

{{-- Tabel Transaksi --}}
<div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
        {{-- Tabel Transaksi --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl / ID Transaksi</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembeli</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item yang Dibeli</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($transactions as $trx)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $trx->created_at->format('d M Y, H:i') }}</div>
                                    <div class="text-xs text-gray-500 font-mono mt-1">{{ $trx->reference_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $trx->user->name ?? 'User Dihapus' }}</div>
                                    <div class="text-xs text-gray-500">{{ $trx->user->email ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($trx->course_id)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 mb-1">
                                            📚 Course
                                        </span>
                                        <div class="text-sm text-gray-900 line-clamp-1">{{ $trx->course->title ?? 'Course Dihapus' }}</div>
                                    @elseif($trx->practice_id)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-lime-100 text-lime-800 mb-1 font-mono uppercase">
                                            💻 Practice
                                        </span>
                                        <div class="text-sm text-gray-900 line-clamp-1">{{ $trx->practice->title ?? 'Practice Dihapus' }}</div>
                                    @else
                                        <span class="text-sm text-gray-500 italic">Item tidak diketahui</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                    Rp {{ number_format($trx->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($trx->status == 'success')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            ✅ Success
                                        </span>
                                    @elseif($trx->status == 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            ⏳ Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            ❌ {{ ucfirst($trx->status) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Belum ada data transaksi penjualan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            @if($transactions->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection