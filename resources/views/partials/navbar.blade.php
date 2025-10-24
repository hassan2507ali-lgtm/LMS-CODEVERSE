<header class="flex justify-between items-center px-6 py-4 sticky top-0 z-50 transition-all duration-300 bg-transparent" id="navbar">
  <div class="text-xl font-bold text-yellow-600 flex items-center gap-2">
    <img src="{{ asset('images/codeverse.png') }}" alt="logo" class="w-8 h-8">
    Code Verse
  </div>
  <nav class="hidden md:flex gap-6 text-sm font-medium">
    <a href="landing" class="hover:text-green-600">Home</a>
    <a href="practice" class="hover:text-green-600">practice</a>
    <a href="course" class="hover:text-green-600">Courses</a>
    <a href="#" class="hover:text-green-600">Contact us</a>
    <a href="#" class="hover:text-green-600">FAQ’s</a>
  </nav>
  <div class="flex gap-3 text-sm font-medium">
    <a href="signin" class="text-gray-600 hover:text-green-600">Sign in</a>
    <a href="#" class="bg-teal-400 text-white px-4 py-2 rounded hover:bg-green-600">Create free account</a>
  </div>
</header>

<script>
  window.addEventListener('scroll', function () {
    const navbar = document.getElementById('navbar');
    if (window.scrollY > 50) {
      navbar.classList.add('bg-white', 'shadow-md'); // Add shadow and white background on scroll
      navbar.classList.remove('bg-transparent');    // Remove transparent background
    } else {
      navbar.classList.add('bg-transparent', 'shadow-none'); // Keep transparent and remove shadow when at the top
      navbar.classList.remove('bg-white');
    }
  });
</script>
