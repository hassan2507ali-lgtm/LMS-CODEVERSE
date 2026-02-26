@extends('layouts.app')

@section('content')
{{-- Kita gunakan pt-24 (padding-top) agar konten tidak tertutup navbar fixed --}}
<div class="container mx-auto px-4 sm:px-6 py-12 pt-24">

    {{-- Box Konten Utama --}}
    <div class="flex items-center justify-center min-h-[60vh]">
        <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-xl shadow-lg border border-gray-200">
            <h2 class="text-3xl font-bold text-center text-gray-800">Login</h2>
            <p class="text-center text-gray-600 text-sm">Selamat datang kembali! Silakan masuk.</p>
            
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

            {{-- === FORM LOGIN (MESIN BREEZE) === --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf {{-- Token Keamanan Wajib --}}

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                    <input id="email" type="email" name="email" 
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-teal-500 focus:border-teal-500 text-sm" 
                           required autofocus autocomplete="username" value="{{ old('email') }}"
                           placeholder="Email">
                </div>

                <!-- Password -->
                <div>
                    <div class="flex justify-between items-baseline">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-teal-500 hover:underline">
                                Lupa password?
                            </a>
                        @endif
                    </div>
                    <input id="password" type="password" name="password" 
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-teal-500 focus:border-teal-500 text-sm" 
                           required autocomplete="current-password"
                           placeholder="••••••••">
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" 
                           class="h-4 w-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-700">Ingat saya</label>
                </div>

                <!-- Tombol Login -->
                <div>
                    <button type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-green-500 hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-300">
                        Log In
                    </button>
                </div>
            </form>
            {{-- === AKHIR FORM LOGIN === --}}

            <p class="text-sm text-center text-gray-600">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-medium text-teal-500 hover:underline">
                    Daftar di sini
                </a>
            </p>

        </div>
    </div>
</div>
@endsection