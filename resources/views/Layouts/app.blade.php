<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code Verse - Belajar Koding Online</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style> body { font-family: 'Segoe UI', sans-serif; } </style>
</head>
<body class="bg-gradient-to-br from-cyan-50 via-white to-blue-100 text-gray-800">
    @include('partials.navbar')
    <main>
        @yield('content')
    </main>
    @include('partials.footer')
</body>
</html>