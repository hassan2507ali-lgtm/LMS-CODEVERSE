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
                           placeholder="Nama ">
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                    <input id="email" type="email" name="email" 
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-teal-500 focus:border-teal-500 text-sm" 
                           required autocomplete="username" value="{{ old('email') }}"
                           placeholder="Email">
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
                <div class="mt-6">
    <div class="relative">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white text-gray-500">Atau masuk dengan</span>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('google.login') }}" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition ease-in-out duration-150">
            <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            Google
        </a>
    </div>
</div>
            </p>

        </div>
    </div>
</div>
@endsection