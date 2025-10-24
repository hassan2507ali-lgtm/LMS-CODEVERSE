@extends('layouts.app')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<!-- Hero Section -->
<section class="flex flex-col md:flex-row items-center justify-between px-4 sm:px-6 py-16 max-w-7xl mx-auto relative pt-24">
    <!-- Background Circle Decoration -->
    <img src="{{ asset('images/placeholder-1.png') }}" alt="Circle Decoration" class="absolute right-0 top-4 md:right-10 md:top-10 w-[420px] md:w-[500px] z-0 opacity-50" />

    <div class="max-w-xl w-full relative z-10">
        <h1 class="text-2xl sm:text-4xl font-bold leading-snug mb-4">
            Mulai Perjalanan <span class="text-teal-400">Belajarmu</span><br>
            Dan Kuasai <span class="text-teal-400">Keterampilan</span><br>
            <span class="text-teal-400">Coding</span> Untuk <span class="text-teal-400">Masa Depan</span><br>
            Yang <span class="text-teal-400">Lebih Cerah</span>
        </h1>
        <p class="text-gray-600 mb-6 text-sm">Ayo mulai belajar dasar pemrograman! Kuasai coding dengan sistem pembelajaran online yang praktis dan menyenangkan.</p>
        <div class="flex gap-4">
            
            <a href="#" class="bg-green-500 text-white px-6 py-2 rounded-md hover:bg-green-600 font-medium">Get Started</a>
            <a href="#" class="border border-green-500 text-green-600 px-6 py-2 rounded-md hover:bg-green-50 font-medium">Get free trial</a>
        </div>
        <div class="flex gap-6 mt-8 text-sm">
            <div class="flex items-center gap-2">
                <img src="https://cdn-icons-png.flaticon.com/512/447/447031.png" class="w-4 h-4" alt="Public Speaking">
                Public Speaking
            </div>
            <div class="flex items-center gap-2">
                <img src="https://cdn-icons-png.flaticon.com/512/1041/1041880.png" class="w-4 h-4" alt="Career-Oriented">
                Career-Oriented
            </div>
            <div class="flex items-center gap-2">
                <img src="https://cdn-icons-png.flaticon.com/512/3105/3105926.png" class="w-4 h-4" alt="Creative Thinking">
                Creative Thinking
            </div>
        </div>
    </div>
    <div class="relative mt-12 md:mt-0 w-full md:w-1/2 z-10">
        <img src="{{ asset('images/logo.png') }}" alt="Hero Graphic" class="w-full max-w-[300px] sm:max-w-md mx-auto">
        <div class="absolute top-8 left-2 bg-white shadow-md px-4 py-2 rounded-xl text-sm font-medium flex items-center gap-2">
            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927C9.469 1.835 10.531 1.835 10.951 2.927L12.319 6.609 16.292 7.07C17.426 7.196 17.777 8.497 16.931 9.224L13.978 11.81 14.817 15.676C15.029 16.757 13.901 17.571 12.942 17.045L9.999 15.5 7.056 17.045C6.097 17.571 4.969 16.757 5.181 15.676L6.02 11.81 3.067 9.224C2.221 8.497 2.572 7.196 3.706 7.07L7.679 6.609 9.049 2.927z"></path></svg>
            2K+ Video Courses
        </div>
        <div class="absolute top-0 right-4 bg-white shadow-md px-4 py-2 rounded-xl text-sm font-medium flex items-center gap-2">
            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor"></circle><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"></path></svg>
            5K+ Online Courses
        </div>
        <div class="absolute bottom-4 left-1/3 bg-white shadow-md px-4 py-2 rounded-xl text-sm font-medium flex items-center gap-2">
            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 5a2 2 0 012-2h4a2 2 0 012 2H2zM2 7h12v10a2 2 0 01-2 2H4a2 2 0 01-2-2V7zm12 0a2 2 0 002-2h-4a2 2 0 002 2z"></path></svg>
            Tutors 250+
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="py-16 px-4 sm:px-6 text-center">
    <h3 class="text-sm text-green-500 font-semibold uppercase">Our Services</h3>
    <h2 class="text-2xl md:text-3xl font-bold mb-12">Fostering a playful & engaging learning environment</h2>

    <div class="grid md:grid-cols-3 gap-6 max-w-6xl mx-auto">
        <!-- Card 1 -->
        <a href="#" class="group bg-white hover:bg-emerald-400 transition-colors duration-300 text-left rounded-xl p-6 shadow-md w-full border block">
            <div class="flex items-center gap-3 mb-3">
                <div class="bg-emerald-100 group-hover:bg-white p-2 rounded-md transition">
                    <svg class="w-6 h-6 text-emerald-500 group-hover:text-emerald-700 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" />
                        <circle cx="12" cy="12" r="10" stroke="currentColor"></circle>
                    </svg>
                </div>
                <h4 class="font-semibold text-lg group-hover:text-white transition">Interaction Design</h4>
            </div>
            <p class="text-sm text-gray-600 group-hover:text-white mb-4 transition">Lessons on design that cover the most recent developments.</p>
            <span class="text-sm font-medium text-emerald-500 group-hover:text-white underline transition">Learn More →</span>
        </a>
        <!-- Card 2 -->
        <a href="#" class="group bg-white hover:bg-blue-500 transition-colors duration-300 text-left rounded-xl p-6 shadow-md w-full border block">
            <div class="flex items-center gap-3 mb-3">
                <div class="bg-blue-100 group-hover:bg-white p-2 rounded-md transition">
                    <svg class="w-6 h-6 text-blue-500 group-hover:text-blue-700 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" />
                        <circle cx="12" cy="12" r="10" stroke="currentColor"></circle>
                    </svg>
                </div>
                <h4 class="font-semibold text-lg group-hover:text-white transition">UX Design Course</h4>
            </div>
            <p class="text-sm text-gray-600 group-hover:text-white mb-4 transition">Classes in development that cover the most recent advancements in web.</p>
            <span class="text-sm font-medium text-blue-500 group-hover:text-white underline transition">Learn More →</span>
        </a>
        <!-- Card 3 -->
        <a href="#" class="group bg-white hover:bg-pink-500 transition-colors duration-300 text-left rounded-xl p-6 shadow-md w-full border block">
            <div class="flex items-center gap-3 mb-3">
                <div class="bg-pink-100 group-hover:bg-white p-2 rounded-md transition">
                    <svg class="w-6 h-6 text-pink-500 group-hover:text-pink-700 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" />
                        <circle cx="12" cy="12" r="10" stroke="currentColor"></circle>
                    </svg>
                </div>
                <h4 class="font-semibold text-lg group-hover:text-white transition">User Interface Design</h4>
            </div>
            <p class="text-sm text-gray-600 group-hover:text-white mb-4 transition">User Interface Design courses that cover the most recent trends</p>
            <span class="text-sm font-medium text-pink-500 group-hover:text-white underline transition">Learn More →</span>
        </a>
    </div>
</section>

<!-- Programs Section -->
<section class="py-16 px-4 sm:px-6">
    <div class="text-center mb-12">
        <h2 class="text-2xl md:text-3xl font-bold">Explore Our Programs</h2>
    </div>
    <div class="grid md:grid-cols-3 gap-6 max-w-6xl mx-auto">
        <div class="bg-[#4C4DDC] text-white font-medium rounded-xl p-6 cursor-pointer transition transform duration-300 hover:scale-105 hover:shadow-xl relative">
            <div class="absolute -top-6 left-4 w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-md">
                <img src="https://cdn-icons-png.flaticon.com/512/1061/1061068.png" class="w-6 h-6" alt="icon">
            </div>
            <h4 class="text-lg mb-2 mt-6 font-bold">Pengenalan Pemrograman : Python</h4>
            <p class="text-sm leading-relaxed">Belajar mengenal dasar pemrograman Python dengan konsep yang mudah dipahami dan interaktif.</p>
        </div>
        <div class="bg-[#F04438] text-white font-medium rounded-xl p-6 cursor-pointer transition transform duration-300 hover:scale-105 hover:shadow-xl relative">
            <div class="absolute -top-6 left-4 w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-md">
                <img src="https://cdn-icons-png.flaticon.com/512/919/919836.png" class="w-6 h-6" alt="icon">
            </div>
            <h4 class="text-lg mb-2 mt-6 font-bold">Algoritma Pemrograman : Python</h4>
            <p class="text-sm leading-relaxed">Pelajari logika dan algoritma dasar pemrograman menggunakan bahasa Python secara efektif.</p>
        </div>
        <div class="bg-[#F79009] text-white font-medium rounded-xl p-6 cursor-pointer transition transform duration-300 hover:scale-105 hover:shadow-xl relative">
            <div class="absolute -top-6 left-4 w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-md">
                <img src="https://cdn-icons-png.flaticon.com/512/747/747376.png" class="w-6 h-6" alt="icon">
            </div>
            <h4 class="text-lg mb-2 mt-6 font-bold">Algoritma Pemrograman : Python</h4>
            <p class="text-sm leading-relaxed">Tingkatkan pemahaman struktur data dan teknik pemrograman lanjutan dengan studi kasus nyata.</p>
        </div>
    </div>
    <div class="text-center mt-10">
         {{-- Mengubah button menjadi link --}}
        <a href="#" class="bg-cyan-600 hover:bg-cyan-700 text-white font-semibold px-6 py-3 rounded-lg transition duration-300">Choose my program now</a>
    </div>
</section>

<!-- Blog Section -->
<section class="py-16 px-4 sm:px-6 ">
    <div class="text-center mb-12">
        <h2 class="text-2xl md:text-3xl font-bold">Top Articles</h2>
    </div>
    <div class="grid md:grid-cols-3 gap-6 max-w-6xl mx-auto">
        <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition duration-300 transform hover:scale-105">
             {{-- Menggunakan placeholder online karena 4119036.jpg tidak ada --}}
            <img src="https://placehold.co/600x400/d1fae5/34d399?text=Blog+Post" class="w-full h-44 object-cover" alt="UX Review">
            <div class="p-6">
                <p class="text-sm text-indigo-600 font-semibold mb-1">Design</p>
                <h3 class="font-bold text-lg mb-2">UX review presentations</h3>
                <p class="text-sm text-gray-600 mb-4">How do you create compelling presentations that wow your colleagues and impress your managers?</p>
                <div class="flex items-center gap-3 text-sm text-gray-500">
                    <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Olivia Rhye" class="w-6 h-6 rounded-full">
                    <span>Olivia Rhye</span>
                    <span>·</span>
                    <span>20 Jan 2022</span>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition duration-300 transform hover:scale-105">
            <img src="https://placehold.co/600x400/fee2e2/ef4444?text=Blog+Post" class="w-full h-44 object-cover" alt="Linear">
            <div class="p-6">
                <p class="text-sm text-pink-600 font-semibold mb-1">Product</p>
                <h3 class="font-bold text-lg mb-2">Migrating to Linear 101</h3>
                <p class="text-sm text-gray-600 mb-4">Linear helps streamline software projects, sprints, tasks, and bug tracking. Here’s how to get started.</p>
                <div class="flex items-center gap-3 text-sm text-gray-500">
                    <img src="https://randomuser.me/api/portraits/men/56.jpg" alt="Phoenix Baker" class="w-6 h-6 rounded-full">
                    <span>Phoenix Baker</span>
                    <span>·</span>
                    <span>19 Jan 2022</span>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition duration-300">
            <img src="https://placehold.co/600x400/dbeafe/3b82f6?text=Blog+Post" class="w-full h-44 object-cover" alt="API Stack">
            <div class="p-6">
                <p class="text-sm text-blue-600 font-semibold mb-1">Software Engineering</p>
                <h3 class="font-bold text-lg mb-2">Building your API Stack</h3>
                <p class="text-sm text-gray-600 mb-4">The rise of RESTful APIs has been met by a rise in tools for creating, testing, and managing them.</p>
                <div class="flex items-center gap-3 text-sm text-gray-500">
                    <img src="https://randomuser.me/api/portraits/women/46.jpg" alt="Laura Steiner" class="w-6 h-6 rounded-full">
                    <span>Laura Steiner</span>
                    <span>·</span>
                    <span>18 Jan 2022</span>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection