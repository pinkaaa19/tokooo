<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aldy Art | @yield('title')</title>
    
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    @vite('resources/css/app.css')
</head>

<body class="bg-[#F9F7F2] text-stone-900 font-sans antialiased">

<nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-stone-200">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        
        {{-- SISI KIRI: Logo & Mobile Menu --}}
        <div class="flex items-center gap-2">
            <button onclick="toggleMobileMenu()" class="md:hidden p-2 hover:bg-stone-100 rounded-lg transition">
                <i data-lucide="menu" class="w-6 h-6 text-stone-600"></i>
            </button>
            
            <a href="/" class="flex items-center">
                <img src="{{ asset('images/Logo Aldyart.png') }}" alt="Logo" class="h-12 md:h-16 w-auto">
                <h1 class="text-xl font-black tracking-tighter text-[#8B0000] uppercase ml-2">Aldy Art</h1>
            </a>
        </div>

        {{-- SISI TENGAH: Desktop Menu --}}
        <div class="hidden md:flex gap-8 font-bold text-[10px] uppercase tracking-[0.2em]">
            <a href="/" class="{{ Request::is('/') ? 'text-[#8B0000]' : 'text-stone-500 hover:text-[#8B0000]' }} transition">Beranda</a>
            <a href="/koleksi" class="{{ Request::is('koleksi') ? 'text-[#8B0000]' : 'text-stone-500 hover:text-[#8B0000]' }} transition">Koleksi</a>
            <a href="/tentang" class="{{ Request::is('tentang') ? 'text-[#8B0000]' : 'text-stone-500 hover:text-[#8B0000]' }} transition">Tentang</a>
        </div>

        {{-- SISI KANAN: Search + Cart + Login (Dibungkus dalam satu flex container agar rapat) --}}
        <div class="flex items-center gap-2 md:gap-4 text-stone-600">
            
            {{-- 1. Ikon Search --}}
            <a href="{{ route('products.search') }}" class="p-2 outline-none transition hover:text-[#8B0000]">
                <i data-lucide="search" class="w-5 h-5 shadow-sm"></i>
            </a>

            {{-- 2. Ikon Keranjang --}}
            <a href="{{ route('cart.index') }}" class="relative p-2 hover:text-[#8B0000] transition">
                <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                @auth
                    @if(session('cart') && count(session('cart')) > 0)
                        <span class="absolute top-1 right-1 bg-[#8B0000] text-white text-[8px] font-bold w-4 h-4 flex items-center justify-center rounded-full">
                            {{ count(session('cart')) }}
                        </span>
                    @endif
                @endauth
            </a>

            {{-- 3. Area Auth (Profile Dropdown atau Login) --}}
            @auth
                <div class="relative ml-2">
                    <button onclick="toggleProfile()" class="flex items-center gap-2 outline-none group">
                        <img src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : asset('images/profil.jpg') }}" 
                             class="w-9 h-9 rounded-full object-cover border-2 border-stone-100 group-hover:border-[#8B0000] transition">
                        <span class="text-[11px] font-bold hidden lg:block uppercase tracking-wider">{{ Auth::user()->name }}</span>
                    </button>
                    
                    {{-- Dropdown Profile --}}
                    <div id="profileMenu" class="hidden absolute right-0 mt-3 w-48 bg-white border border-stone-100 rounded-2xl shadow-xl py-2 overflow-hidden z-50">
                        <a href="{{ url('/profile') }}" class="block px-4 py-3 text-sm text-stone-600 hover:bg-stone-50 hover:text-[#8B0000] transition">Profil Saya</a>
                        <a href="{{ url('/orders') }}" class="block px-4 py-3 text-sm text-stone-600 hover:bg-stone-50 hover:text-[#8B0000] transition">Pesanan Saya</a>
                        <div class="border-t border-stone-50 my-1"></div>
                        <form action="{{ route('logout') }}" method="POST">@csrf
                            <button type="submit" class="w-full text-left px-4 py-3 text-[10px] text-red-600 hover:bg-red-50 transition font-black uppercase tracking-widest">Logout</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="ml-2 text-[10px] font-black uppercase tracking-widest bg-stone-900 text-white px-5 py-2.5 rounded-full hover:bg-[#8B0000] transition shadow-sm">
                    Login
                </a>
            @endauth
        </div>

    </div>
</nav>

    {{-- ELEMEN MENU MOBILE (YANG TADI HILANG) --}}
    <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-stone-100 animate-in slide-in-from-top duration-300">
        <div class="flex flex-col p-6 gap-4 font-black uppercase tracking-widest text-[11px]">
            <a href="/" class="py-2 {{ Request::is('/') ? 'text-[#8B0000]' : 'text-stone-500' }}">Beranda</a>
            <a href="/koleksi" class="py-2 {{ Request::is('koleksi') ? 'text-[#8B0000]' : 'text-stone-500' }}">Koleksi</a>
            <a href="/tentang" class="py-2 {{ Request::is('tentang') ? 'text-[#8B0000]' : 'text-stone-500' }}">Tentang</a>
        </div>
    </div>
</nav>

<main>
    @yield('content')
</main>

<footer class="bg-stone-900 text-stone-500 py-16 mt-20 italic">
    <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-12">
        <div>
            <h4 class="text-white font-black mb-4 tracking-widest uppercase">ALDY ART</h4>
            <p class="text-sm">Produk Budaya Toraja yang Kreatif</p>
        </div>
        <div>
            <h4 class="text-white font-black mb-4 tracking-widest uppercase">HUBUNGI KAMI</h4>
            <p class="text-sm">Rantepao, Toraja Utara<br>
                <!-- Tautan WhatsApp Admin -->
                <a href="https://wa.me/6282349804981" target="_blank" class="hover:text-[#8B0000] transition">
                    WhatsApp: +62 823-4980-4981
                </a>
            </p>
        </div>
        <div>
            <h4 class="text-white font-black mb-4 tracking-widest uppercase">IKUTI KAMI</h4>
            <div class="flex gap-6 text-2xl">
                <!-- Tautan Instagram -->
                <a href="https://www.instagram.com/aldy_art_toraja" target="_blank" class="hover:text-white transition">
                    <i class="fa-brands fa-instagram"></i>
                </a>
                <!-- Tautan Facebook -->
                <a href="https://www.facebook.com/profile.php?id=100067096216160" target="_blank" class="hover:text-white transition">
                    <i class="fa-brands fa-facebook"></i>
                </a>
            </div>
        </div>
    </div>
</footer>


<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>

    lucide.createIcons();

    function toggleProfile() {
        const profileMenu = document.getElementById("profileMenu");
        // Tutup mobile menu jika sedang terbuka saat buka profile
        document.getElementById("mobileMenu").classList.add("hidden");
        profileMenu.classList.toggle("hidden");
    }

    function toggleMobileMenu(){
        const mobileMenu = document.getElementById("mobileMenu");
        // Tutup profile menu jika sedang terbuka saat buka mobile menu
        if(document.getElementById("profileMenu")) {
            document.getElementById("profileMenu").classList.add("hidden");
        }
        mobileMenu.classList.toggle("hidden");
    }

    // Menutup menu jika klik di luar elemen
    window.onclick = function(event) {
        if (!event.target.closest('.relative') && !event.target.closest('.md\\:hidden')) {
            document.getElementById("profileMenu")?.classList.add("hidden");
            document.getElementById("mobileMenu")?.classList.add("hidden");
        }
    }
</script>

</body>
</html>