@extends('Layouts.app') @section('content')
<div class="bg-white rounded-xl shadow-sm p-6 mb-8 border border-gray-100">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Kelola User</h2>
            <p class="text-sm text-gray-500">Daftar semua pengguna yang terdaftar di platform Code Verse.</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                    <th class="px-4 py-3">Nama Lengkap</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Status / Role</th>
                    <th class="px-4 py-3">Tanggal Daftar</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @foreach($users as $user)
                <tr class="text-gray-700 hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 font-medium">{{ $user->name }}</td>
                    <td class="px-4 py-3 text-sm">{{ $user->email }}</td>
                    <td class="px-4 py-3 text-sm">
                        @if($user->is_admin)
                            <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full">Admin</span>
                        @else
                            <span class="px-2 py-1 font-semibold leading-tight text-blue-700 bg-blue-100 rounded-full">User Biasa</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm">{{ $user->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection