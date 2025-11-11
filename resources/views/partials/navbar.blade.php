<header class="flex justify-between items-center px-6 py-4 sticky top-0 z-50 transition-all duration-300 bg-transparent" id="navbar">
    
    {{-- LOGO --}}
    <div class="text-xl font-bold text-yellow-600 flex items-center gap-2">
      <img src="{{ asset('images/codeverse.png') }}" alt="logo" class="w-8 h-8">
      Code Verse
    </div>

    {{-- NAVIGASI UTAMA --}}
    <nav class="hidden md:flex gap-6 text-sm font-medium">
      <a href="{{ route('landing') }}" class="text-gray-600 hover:text-teal-500 font-medium">Home</a>
      {{-- Saya perbaiki nama route Anda dari 'courses.course' ke 'courses.index' (sesuai standar) --}}
      <a href="{{ route('courses.course') }}" class="text-gray-600 hover:text-teal-500 font-medium">Courses</a> 
      <a href="{{ route('practice.index') }}" class="text-gray-600 hover:text-teal-500 font-medium">Practice</a>
      <a href="#" class="hover:text-green-600">Contact us</a>
      <a href="#" class="hover:text-green-600">FAQ’s</a>

      {{-- (Opsional) Tampilkan link ke Dashboard HANYA JIKA pengguna adalah Admin --}}
      @auth
          @if(auth()->user()->is_admin)
              <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-800 font-bold ml-4">Admin Dashboard</a>
          @endif
      @endauth
    </nav>

    {{-- BAGIAN KANAN (LOGIN/LOGOUT) --}}
    <div class="flex items-center gap-4">

        @guest
            {{-- TAMPILKAN INI JIKA PENGGUNA BELUM LOGIN --}}
            <a href="{{ route('login') }}" class="text-gray-600 hover:text-teal-500 font-medium">Login</a>
            <a href="{{ route('register') }}" class="bg-green-500 text-white px-5 py-2 rounded-md hover:bg-green-600 font-semibold">Sign Up</a>
        @endguest

        @auth
            {{-- TAMPILKAN INI JIKA PENGGUNA SUDAH LOGIN (BAIK USER BIASA MAUPUN ADMIN) --}}
            
            {{-- Tampilkan nama pengguna --}}
            <span class="text-gray-700 text-sm font-medium hidden sm:block">
              Hi, {{ auth()->user()->name }}
            </span>

            {{-- Tombol Logout (HARUS di dalam form POST) --}}
            <form method="POST" action="{{ route('logout') }}" class="m-0">
              @csrf {{-- Token Keamanan Wajib --}}
              
              {{-- 
                Kita buat link <a> yang terlihat seperti tombol.
                Saat diklik, 'onclick' akan mencegah link-nya berjalan,
                dan malah mengirimkan form POST terdekat.
              --}}
              <a href="{{ route('logout') }}"
                 class="bg-red-500 text-white px-5 py-2 rounded-md hover:bg-red-600 font-semibold"
                 onclick="event.preventDefault(); this.closest('form').submit();">
                Logout
              </a>
            </form>
        @endauth

    </div>
</header>

{{-- SCRIPT SCROLL ANDA (TETAP ADA) --}}
<script>
  window.addEventListener('scroll', function () {
    const navbar = document.getElementById('navbar');
    if (window.scrollY > 50) {
      navbar.classList.add('bg-white', 'shadow-md');
      navbar.classList.remove('bg-transparent');
    } else {
      navbar.classList.add('bg-transparent', 'shadow-none');
      navbar.classList.remove('bg-white');
    }
  });
</script>