@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 py-12 pt-24">

    {{-- Box Konten Utama --}}
    <div class="flex items-center justify-center min-h-[60vh]">
        <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-xl shadow-lg border border-gray-200">
            <h2 class="text-3xl font-bold text-center text-gray-800">Buat Akun Baru</h2>
            <p class="text-center text-gray-600 text-sm">Ayo mulai perjalanan belajarmu!</p>
            
            {{-- Menampilkan error validasi (jika ada) --}}
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
                    <div class="font-medium">Oops! Ada yang salah.</div>
                    <ul class="list-disc list-inside text-sm mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- === FORM REGISTER (MESIN BREEZE) === --}}
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf {{-- Token Keamanan Wajib --}}

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input id="name" type="text" name="name" 
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-teal-500 focus:border-teal-500 text-sm" 
                           required autofocus autocomplete="name" value="{{ old('name') }}"
                           placeholder="Nama Anda">
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                    <input id="email" type="email" name="email" 
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-teal-500 focus:border-teal-500 text-sm" 
                           required autocomplete="username" value="{{ old('email') }}"
                           placeholder="anda@email.com">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" type="password" name="password" 
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-teal-500 focus:border-teal-500 text-sm" 
                           required autocomplete="new-password"
                           placeholder="•••••••• (Minimal 8 karakter)">
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" 
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-teal-500 focus:border-teal-500 text-sm" 
                           required autocomplete="new-password"
                           placeholder="••••••••">
                </div>

                <!-- Tombol Register -->
                <div>
                    <button type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-green-500 hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-300">
                        Daftar
                    </button>
                </div>
            </form>
            {{-- === AKHIR FORM REGISTER === --}}

            <p class="text-sm text-center text-gray-600">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-medium text-teal-500 hover:underline">
                    Login di sini
                </a>
            </p>

        </div>
    </div>
</div>
@endsection