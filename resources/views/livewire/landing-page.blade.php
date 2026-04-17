<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPG Karawang Timur - Portal Data</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        /* Custom styling untuk gradasi halus */
        .hero-gradient {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        }
    </style>
</head>
<body class="font-sans text-gray-800 bg-gray-50">

    <nav class="fixed z-50 w-full shadow-sm bg-white/90 backdrop-blur-md" x-data="{ open: false }">
        <div class="container flex items-center justify-between px-6 py-4 mx-auto">
            <a href="#" class="text-2xl font-bold tracking-wider text-blue-700 uppercase">PPG Kartim</a>
            
            <button id="menu-btn" class="block text-blue-700 md:hidden focus:outline-none">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
    
            <div class="hidden space-x-8 font-medium md:flex">
                <a href="#profil" class="transition hover:text-blue-600">Profil</a>
                <a href="#statistik" class="transition hover:text-blue-600">Statistik</a>
                <a href="#event" class="transition hover:text-blue-600">Event</a>
                <a href="#registrasi" class="px-4 py-2 text-white transition bg-blue-600 rounded-lg hover:bg-blue-700">Login</a>
            </div>
        </div>
    
        <div id="mobile-menu" class="hidden px-6 py-4 space-y-4 bg-white border-t border-gray-100 shadow-xl md:hidden">
            <a href="#profil" class="block font-medium hover:text-blue-600">Profil</a>
            <a href="#statistik" class="block font-medium hover:text-blue-600">Statistik</a>
            <a href="#event" class="block font-medium hover:text-blue-600">Event</a>
            <a href="#registrasi" class="block px-4 py-2 text-center text-white bg-blue-600 rounded-lg">Login</a>
        </div>
    </nav><nav class="fixed z-50 w-full bg-white shadow-md">
        <div class="container flex items-center justify-between px-6 py-4 mx-auto">
            <a href="#" class="text-2xl font-bold tracking-wider text-blue-700 uppercase">PPG Kartim</a>
            
            <button id="menu-btn" class="block p-2 transition rounded-md md:hidden focus:outline-none hover:bg-gray-100">
                <svg id="icon-open" class="w-8 h-8 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
                <svg id="icon-close" class="hidden w-8 h-8 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
    
            <div class="hidden space-x-8 font-medium md:flex">
                <a href="#profil" class="transition hover:text-blue-600">Profil</a>
                <a href="#statistik" class="transition hover:text-blue-600">Statistik</a>
                <a href="#event" class="transition hover:text-blue-600">Event</a>
                <a href="#registrasi" class="px-4 py-2 text-white transition bg-blue-600 rounded-lg hover:bg-blue-700">Login</a>
            </div>
        </div>
    
        <div id="mobile-menu" class="flex flex-col hidden p-6 space-y-4 bg-white border-t border-gray-100 shadow-xl md:hidden">
            <a href="#profil" class="pb-2 text-lg font-medium text-gray-700 border-b hover:text-blue-600">Profil</a>
            <a href="#statistik" class="pb-2 text-lg font-medium text-gray-700 border-b hover:text-blue-600">Statistik</a>
            <a href="#event" class="pb-2 text-lg font-medium text-gray-700 border-b hover:text-blue-600">Event Mendatang</a>
            <a href="#registrasi" class="px-4 py-3 font-bold text-center text-white bg-blue-600 rounded-xl">Registrasi Sekarang</a>
        </div>
    </nav>

    <section class="flex items-center min-h-screen pt-20 text-white hero-gradient">
        <div class="container px-6 mx-auto text-center" data-aos="fade-up">
            <h1 class="mb-6 text-4xl font-extrabold md:text-6xl">Selamat Datang di Portal Data <br>PPG Karawang Timur</h1>
            <p class="max-w-2xl mx-auto mb-10 text-lg md:text-xl opacity-90">
                Mewujudkan generus yang alim-faqih, berakhlaqul karimah, dan mandiri melalui pendataan yang terintegrasi.
            </p>
            <a href="#registrasi" class="px-8 py-4 text-lg font-bold text-blue-700 transition-all transform bg-white rounded-full shadow-xl hover:bg-yellow-400 hover:text-white hover:scale-105">
                Registrasi Sekarang
            </a>
        </div>
    </section>

    <section id="profil" class="py-20 overflow-hidden bg-white">
        <div class="container px-6 mx-auto">
            <div class="flex flex-col items-center gap-12 md:flex-row">
                <div class="md:w-1/2" data-aos="fade-right">
                    <img src="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=800&q=80" alt="Kegiatan PPG" class="shadow-2xl rounded-2xl">
                </div>
                <div class="md:w-1/2" data-aos="fade-left">
                    <h2 class="mb-6 text-3xl font-bold text-blue-800">Profil PPG Karawang Timur</h2>
                    <p class="mb-4 leading-relaxed text-gray-600">
                        PPG (Penggerak Pembina Generus) Karawang Timur adalah lembaga yang berfokus pada pembinaan karakter dan kemandirian generasi muda di wilayah Karawang Timur.
                    </p>
                    <p class="leading-relaxed text-gray-600">
                        Kami berkomitmen untuk menyediakan wadah pembelajaran yang komprehensif, mulai dari usia PAUD hingga jenjang Pra-Nikah, didukung oleh tenaga pengajar (mubaligh) yang kompeten.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="statistik" class="py-20 bg-gray-100">
        <div class="container px-6 mx-auto">
            <h2 class="mb-12 text-3xl font-bold text-center text-gray-800" data-aos="fade-up">Statistik Terkini</h2>
            
            <div class="grid grid-cols-1 gap-6 mb-12 md:grid-cols-2">
                <div class="p-8 text-center text-white bg-blue-800 shadow-lg rounded-2xl" data-aos="zoom-in">
                    <span class="text-5xl font-black">12</span>
                    <p class="mt-2 font-semibold tracking-widest text-blue-200 uppercase">Mubaligh Tugasan</p>
                </div>
                <div class="p-8 text-center text-white bg-blue-600 shadow-lg rounded-2xl" data-aos="zoom-in" data-aos-delay="100">
                    <span class="text-5xl font-black">25</span>
                    <p class="mt-2 font-semibold tracking-widest text-blue-100 uppercase">Mubaligh Setempat</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 md:grid-cols-5">
                <div class="p-6 text-center bg-white border-b-4 border-green-500 shadow-sm rounded-xl" data-aos="fade-up" data-aos-delay="100">
                    <h4 class="text-3xl font-bold text-green-600">30</h4>
                    <p class="text-sm font-medium text-gray-500 uppercase">PAUD / TK</p>
                </div>
                <div class="p-6 text-center bg-white border-b-4 border-green-500 shadow-sm rounded-xl" data-aos="fade-up" data-aos-delay="200">
                    <h4 class="text-3xl font-bold text-green-600">55</h4>
                    <p class="text-sm font-medium text-gray-500 uppercase">Caberawit</p>
                </div>
                <div class="p-6 text-center bg-white border-b-4 border-green-500 shadow-sm rounded-xl" data-aos="fade-up" data-aos-delay="300">
                    <h4 class="text-3xl font-bold text-green-600">42</h4>
                    <p class="text-sm font-medium text-gray-500 uppercase">Pra Remaja</p>
                </div>
                <div class="p-6 text-center bg-white border-b-4 border-green-500 shadow-sm rounded-xl" data-aos="fade-up" data-aos-delay="400">
                    <h4 class="text-3xl font-bold text-green-600">60</h4>
                    <p class="text-sm font-medium text-gray-500 uppercase">Remaja</p>
                </div>
                <div class="p-6 text-center bg-white border-b-4 border-green-500 shadow-sm rounded-xl" data-aos="fade-up" data-aos-delay="500">
                    <h4 class="text-3xl font-bold text-green-600">28</h4>
                    <p class="text-sm font-medium text-gray-500 uppercase">Pra Nikah</p>
                </div>
            </div>
        </div>
    </section>

    <section id="event" class="py-20 bg-white">
        <div class="container px-6 mx-auto">
            <h2 class="mb-10 text-3xl font-bold text-center" data-aos="fade-up">Event Mendatang</h2>
            <div class="max-w-4xl mx-auto space-y-6">
                <div class="flex flex-col items-center p-6 border-l-8 border-orange-500 shadow-sm md:flex-row bg-orange-50 rounded-2xl" data-aos="fade-left">
                    <div class="mb-4 text-center border-orange-200 md:w-24 md:mb-0 md:border-r md:mr-6">
                        <span class="block text-3xl font-bold text-orange-600">25</span>
                        <span class="text-sm font-bold text-orange-400 uppercase">MAR</span>
                    </div>
                    <div class="flex-grow text-center md:text-left">
                        <h4 class="text-xl font-bold text-gray-800">Munaqosah Tahfidz Akbar</h4>
                        <p class="italic text-gray-600">Masjid Al-Fattah | 08:00 - Selesai</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="registrasi" class="py-20 bg-blue-50">
        <div class="container px-6 mx-auto">
            <div class="max-w-3xl mx-auto overflow-hidden bg-white shadow-2xl rounded-3xl" data-aos="zoom-in-up">
                <div class="p-6 text-center text-white bg-blue-600">
                    <h2 class="text-2xl font-bold">Formulir Registrasi</h2>
                    <p class="text-blue-100">Silakan isi data diri Anda dengan benar.</p>
                </div>
                <form class="p-8 space-y-4">
                    <div>
                        <label class="block mb-2 text-sm font-bold">Pilih Kategori</label>
                        <select class="w-full p-3 transition border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                            <option>Generus</option>
                            <option>Mubaligh</option>
                            <option>Pengurus</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block mb-2 text-sm font-bold">Nama Lengkap</label>
                            <input type="text" placeholder="Contoh: Ahmad" class="w-full p-3 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-bold">Nomor WhatsApp</label>
                            <input type="tel" placeholder="0812..." class="w-full p-3 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-bold">Alamat Lengkap</label>
                        <textarea class="w-full p-3 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500" rows="3"></textarea>
                    </div>
                    <button type="submit" class="w-full py-4 font-bold text-white transition transform bg-blue-600 rounded-lg shadow-lg hover:bg-blue-700 hover:-translate-y-1">
                        Kirim Data
                    </button>
                </form>
            </div>
        </div>
    </section>

    <footer class="py-12 text-white bg-gray-900">
        <div class="container px-6 mx-auto text-center">
            <h3 class="mb-4 text-xl font-bold uppercase">PPG Karawang Timur</h3>
            <p class="max-w-md mx-auto mb-6 text-sm text-gray-400">Sistem informasi pendataan dan pengelolaan pembina generus wilayah Karawang Timur.</p>
            <div class="pt-8 text-xs text-gray-500 border-t border-gray-800">
                &copy; 2026 PPG Karawang Timur. All Rights Reserved.
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Inisialisasi Efek Fade
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
    </script>
    <script>
        const btn = document.getElementById('menu-btn');
        const menu = document.getElementById('mobile-menu');
        const iconOpen = document.getElementById('icon-open');
        const iconClose = document.getElementById('icon-close');
    
        btn.addEventListener('click', () => {
            // Toggle Menu
            menu.classList.toggle('hidden');
            
            // Switch Icons (Ganti ikon garis tiga jadi silang)
            iconOpen.classList.toggle('hidden');
            iconClose.classList.toggle('hidden');
        });
    
        // Otomatis tutup menu saat link diklik
        const navLinks = document.querySelectorAll('#mobile-menu a');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                menu.classList.add('hidden');
                iconOpen.classList.remove('hidden');
                iconClose.classList.add('hidden');
            });
        });
    </script>
</body>
</html>