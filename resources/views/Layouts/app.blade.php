<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Code Verse - Belajar Koding Online</title>

        <!-- Fonts (Bawaan Breeze, boleh dibiarkan) -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Style Font Custom Anda -->
        <style>
            body {
                font-family: 'Segoe UI', sans-serif;
            }
        </style>

        <!-- Scripts & Styles dari VITE (Menggantikan CDN Tailwind) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gradient-to-br from-cyan-50 via-white to-blue-100 text-gray-800">

        {{-- Panggil Navbar Custom Anda --}}
        @include('partials.navbar')

        <!-- Page Content -->
        <main>
            {{-- 
                Breeze menggunakan $slot untuk konten di halaman dashboard.
                Halaman custom kita (Landing, Courses) menggunakan @yield('content').
                Kita perlu menangani keduanya.
            --}}
            @if (isset($slot))
                {{ $slot }}
            @else
                @yield('content')
            @endif
        </main>
        
        {{-- Panggil Footer Custom Anda --}}
        @include('partials.footer')

    </body>
</html>