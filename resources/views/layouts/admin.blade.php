<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Digitalisasi Toraja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-gray-50 lg:flex" x-data="{ sideBarOpen: false }">

    <header class="lg:hidden w-full bg-[#8B0000] text-white p-4 flex justify-between items-center sticky top-0 z-50">
        <h1 class="text-lg font-black uppercase italic tracking-tighter">Admin <span class="text-stone-400">ALDYART</span></h1>
        <button @click="sideBarOpen = true" class="p-2 bg-white/10 rounded-xl focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
        </button>
    </header>

    <aside 
        :class="sideBarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed lg:sticky top-0 left-0 w-72 bg-[#8B0000] min-h-screen text-white p-8 z-[60] shadow-2xl transition-transform duration-300 ease-in-out shrink-0 overflow-y-auto">
        
        <div class="lg:hidden flex justify-end mb-4">
            <button @click="sideBarOpen = false" class="text-white/50 hover:text-white">✕ Close</button>
        </div>

        <div class="mb-12">
            <h1 class="text-2xl font-black uppercase italic tracking-tighter">Admin <span class="text-stone-400">ALDYART</span></h1>
            <p class="text-[10px] text-white/50 uppercase tracking-[0.3em]">Produk Toraja</p>
        </div>

        <nav class="space-y-3">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-4 p-4 rounded-2xl hover:bg-white/10 transition {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 font-bold shadow-lg' : '' }}">
                <span>📊</span> Dashboard
            </a>
            <a href="{{ route('admin.knowledge.index') }}" class="flex items-center gap-4 p-4 rounded-2xl hover:bg-white/10 transition {{ request()->routeIs('admin.knowledge.*') ? 'bg-white/20 font-bold shadow-lg' : '' }}">
                <span>🗿</span> Knowledge Culture
            </a>
            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-4 p-4 rounded-2xl hover:bg-white/10 transition {{ request()->routeIs('admin.products.*') ? 'bg-white/20 font-bold shadow-lg' : '' }}">
                <span>📦</span> Data Produk
            </a>
            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-4 p-4 rounded-2xl hover:bg-white/10 transition {{ request()->routeIs('admin.orders.*') ? 'bg-white/20 font-bold shadow-lg' : '' }}">
                <span>🛒</span> Pesanan Masuk
            </a>
            <a href="{{ route('admin.laporan.mingguan') }}" class="flex items-center gap-4 p-4 rounded-2xl hover:bg-white/10 transition {{ request()->routeIs('admin.laporan.*') ? 'bg-white/20 font-bold shadow-lg' : '' }}">
                <span>📈</span> Laporan Mingguan
            </a>
            <div class="pt-10">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left flex items-center gap-4 p-4 rounded-2xl hover:bg-red-600 transition text-red-200">
                        <span>🚪</span> Logout
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <div 
        x-show="sideBarOpen" 
        @click="sideBarOpen = false" 
        x-transition:enter="transition opacity-0 ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition opacity-100 ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 z-[55] lg:hidden backdrop-blur-sm">
    </div>

    <main class="flex-1 p-6 lg:p-12 w-full">
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-2xl border border-green-200 font-bold text-sm">
                {{ session('success') }}
            </div>
        @endif

        @yield('admin_content')
    </main>

</body>
</html>