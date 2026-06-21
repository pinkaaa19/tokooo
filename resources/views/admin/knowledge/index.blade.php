@extends('layouts.admin')

@section('admin_content')
{{-- Kontainer Utama dengan Alpine.js untuk kontrol Dropdown, Tab, dan Penghitung Total Dinamis --}}
<div x-data="{ 
    openDropdown: false, 
    activeTab: 'motif',
    totalMotif: {{ $motifs->count() }},
    totalSop: {{ $sops->count() }},
    totalFaq: {{ $motifs->where('type', 'faq')->count() }} {{-- Sementara membaca sisa FAQ di tabel lama --}}
}" class="p-2">
    
    {{-- HEADER SECTION --}}
    <div class="flex justify-between items-center mb-10 relative">
        <div>
            <h2 class="text-4xl font-black text-gray-900 uppercase italic tracking-tighter">
                Knowledge <span class="text-[#8B0000]">Base</span>
            </h2>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mt-1">
                Total <span x-text="activeTab === 'motif' ? 'motif' : (activeTab === 'sop' ? 'SOP' : 'FAQ')"></span>: 
                <span class="text-[#8B0000]" x-text="activeTab === 'motif' ? totalMotif : (activeTab === 'sop' ? totalSop : totalFaq)"></span> Item
            </p>
        </div>
        
        {{-- DROPDOWN TOMBOL "+ DIGITALISASI BARU" --}}
        <div class="relative">
            <button @click="openDropdown = !openDropdown" @click.away="openDropdown = false" 
                class="bg-black text-white px-8 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-[#8B0000] transition shadow-lg shadow-black/10 flex items-center gap-3 cursor-pointer">
                + Digitalisasi Baru
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 transition-transform duration-200" :class="openDropdown ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7" />
                </</svg>
            </button>

            <div x-show="openDropdown" x-transition class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden z-50 py-2" style="display: none;">
                <a href="{{ route('admin.motif.create') }}" class="block px-6 py-3 text-[10px] font-black uppercase tracking-wider text-gray-700 hover:bg-gray-50 hover:text-[#8B0000] transition">🎨 Tambah Motif Baru</a>
                <a href="{{ route('admin.sop.create') }}" class="block px-6 py-3 text-[10px] font-black uppercase tracking-wider text-gray-700 hover:bg-gray-50 hover:text-[#8B0000] transition">📋 Tambah SOP Baru</a>
                <a href="{{ route('admin.faq.create') }}" class="block px-6 py-3 text-[10px] font-black uppercase tracking-wider text-gray-700 hover:bg-gray-50 hover:text-[#8B0000] transition">💬 Tambah FAQ Baru</a>
            </div>
        </div>
    </div>

    {{-- NAVIGASI TAB KATEGORI --}}
    <div class="flex gap-3 border-b border-stone-100 pb-4 mb-10">
        <button @click="activeTab = 'motif'" :class="activeTab === 'motif' ? 'bg-black text-white' : 'bg-stone-50 text-stone-400 hover:text-black'" class="px-6 py-3 rounded-xl font-black text-[9px] uppercase tracking-widest transition cursor-pointer">🎨 Filosofi Motif</button>
        <button @click="activeTab = 'sop'" :class="activeTab === 'sop' ? 'bg-black text-white' : 'bg-stone-50 text-stone-400 hover:text-black'" class="px-6 py-3 rounded-xl font-black text-[9px] uppercase tracking-widest transition cursor-pointer">📋 SOP Operasional</button>
        <button @click="activeTab = 'faq'" :class="activeTab === 'faq' ? 'bg-black text-white' : 'bg-stone-50 text-stone-400 hover:text-black'" class="px-6 py-3 rounded-xl font-black text-[9px] uppercase tracking-widest transition cursor-pointer">💬 FAQ Produk</button>
    </div>

    {{-- CARDS GRID WRAPPER --}}
    <div>
        {{-- ================= TAB 1: KUMPULAN DATA MOTIF ================= --}}
        <div x-show="activeTab === 'motif'" x-transition class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20">
            {{-- SINKRONISASI: Menggunakan $motifs langsung --}}
            @forelse($motifs->whereIn('type', ['image', 'video', 'animation']) as $item)
            <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm border border-gray-100 group transition hover:shadow-2xl flex flex-col h-full">
                <div class="aspect-video relative overflow-hidden bg-gray-200 shrink-0">
                    @if($item->file_path)
                        <img src="{{ asset('storage/' . $item->file_path) }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                    @else
                        <div class="w-full h-full flex items-center justify-center font-bold text-gray-400 text-[10px] uppercase">Tidak Ada Media</div>
                    @endif
                    <div class="absolute top-4 left-4">
                        <span class="bg-black/60 backdrop-blur-md text-white text-[7px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full border border-white/20">{{ $item->group_name ?? 'Passura' }}</span>
                    </div>
                </div>
                <div class="p-8 flex flex-col flex-grow">
                    <h3 class="font-black uppercase italic text-lg mb-2 leading-tight truncate text-stone-900">{{ $item->title }}</h3>
                    <div class="mb-6 space-y-1">
                        <p class="text-[9px] font-bold text-stone-400 uppercase tracking-tight">Sumber: <span class="text-stone-600">{{ $item->source ?? 'Buku Sejarah' }}</span></p>
                        <p class="text-[9px] font-bold text-stone-400 uppercase tracking-tight">Relasi: <span class="text-[#8B0000]">{{ $item->relatedMotifs ? $item->relatedMotifs->count() : 0 }} Motif Terkait</span></p>
                    </div>
                    <div class="mt-auto pt-6 border-t border-stone-100 flex justify-between items-center">
                        <span class="text-[9px] font-black uppercase text-[#8B0000] bg-red-50 px-3 py-1 rounded-full tracking-widest">MOTIF</span>
                        <div class="flex gap-4 items-center text-[10px] font-bold uppercase">
                            <a href="{{ route('admin.motif.show', $item->id) }}" class="text-gray-400 hover:text-black transition">Detail</a>
                            <a href="{{ route('admin.motif.edit', $item->id) }}" class="text-amber-500 hover:text-amber-700 transition">Edit</a>
                            <form action="{{ route('admin.motif.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data motif ini?')">
                                @csrf @method('DELETE') <button type="submit" class="text-red-400 hover:text-red-700 transition cursor-pointer">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
                <div class="col-span-1 md:col-span-3 py-20 text-center text-stone-400 font-bold uppercase tracking-widest text-[11px] italic">Belum ada data filosofi motif tradisional.</div>
            @endforelse
        </div>

        {{-- ================= TAB 2: KUMPULAN DATA SOP ================= --}}
        <div x-show="activeTab === 'sop'" x-transition class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20" style="display: none;">
            {{-- SINKRONISASI: Menggunakan variabel bersih $sops --}}
            @forelse($sops as $item)
            <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm border border-gray-100 group transition hover:shadow-2xl flex flex-col h-full">
                <div class="p-8 flex flex-col flex-grow justify-between h-64">
                    <div>
                        <span class="bg-stone-100 text-stone-700 text-[7px] font-black uppercase tracking-widest px-3 py-1 rounded-full mb-3 inline-block">📋</span>
                        <h3 class="font-black uppercase italic text-lg leading-tight text-stone-900 line-clamp-2">{{ $item->title }}</h3>
                        <p class="text-xs text-stone-400 mt-2 line-clamp-2 font-medium">{!! strip_tags($item->description) !!}</p>
                    </div>
                    <div class="pt-6 border-t border-stone-100 flex justify-between items-center">
                        <span class="text-[9px] font-black uppercase text-blue-600 bg-blue-50 px-3 py-1 rounded-full tracking-widest">SOP</span>
                        <div class="flex gap-4 items-center text-[10px] font-bold uppercase">
                            <a href="{{ route('admin.sop.show', $item->id) }}" class="text-gray-400 hover:text-black transition">Detail</a>
                            <a href="{{ route('admin.sop.edit', $item->id) }}" class="text-amber-500 hover:text-amber-700 transition">Edit</a>
                            <form action="{{ route('admin.sop.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus dokumen SOP ini?')">
                                @csrf @method('DELETE') <button type="submit" class="text-red-400 hover:text-red-700 transition cursor-pointer">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
                <div class="col-span-1 md:col-span-3 py-20 text-center text-stone-400 font-bold uppercase tracking-widest text-[11px] italic">Belum ada dokumen standar operasional prosedur.</div>
            @endforelse
        </div>

{{-- ================= TAB 3: KUMPULAN DATA FAQ ================= --}}
<div x-show="activeTab === 'faq'" x-transition class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20" style="display: none;">
    
    {{-- PERBAIKAN: Ubah looping agar membaca langsung dari variabel $faqs --}}
    @forelse($faqs as $item)
    <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm border border-gray-100 group transition hover:shadow-2xl flex flex-col h-full">
        <div class="p-8 flex flex-col flex-grow justify-between h-64">
            <div class="space-y-3">
                <div class="border-l-4 border-[#8B0000] pl-3">
                    <span class="block text-[7px] font-black text-[#8B0000] uppercase tracking-widest">Pertanyaan (Q)</span>
                    {{-- SINKRONISASI KOLOM: Gunakan $item->question sesuai struktur tabel baru --}}
                    <h4 class="font-bold text-stone-800 text-sm italic leading-snug">"{{ $item->question }}"</h4>
                </div>
                <div class="bg-stone-50 p-3 rounded-xl">
                    <span class="block text-[7px] font-black text-stone-400 uppercase tracking-widest mb-1">Jawaban (A)</span>
                    {{-- SINKRONISASI KOLOM: Gunakan $item->answer sesuai struktur tabel baru --}}
                    <p class="text-xs text-stone-500 line-clamp-2 font-medium">{{ $item->answer }}</p>
                </div>
            </div>
            <div class="pt-6 border-t border-stone-100 flex justify-between items-center">
                <span class="text-[9px] font-black uppercase text-green-600 bg-green-50 px-3 py-1 rounded-full tracking-widest">FAQ</span>
                <div class="flex gap-4 items-center text-[10px] font-bold uppercase">
                    {{-- SINKRONISASI URL ROUTE: Arahkan ke admin.faq --}}
                    <a href="{{ route('admin.faq.show', $item->id) }}" class="text-gray-400 hover:text-black transition">Detail</a>
                    <a href="{{ route('admin.faq.edit', $item->id) }}" class="text-amber-500 hover:text-amber-700 transition">Edit</a>
                    <form action="{{ route('admin.faq.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data FAQ ini?')">
                        @csrf @method('DELETE') <button type="submit" class="text-red-400 hover:text-red-700 transition cursor-pointer">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
        <div class="col-span-1 md:col-span-3 py-20 text-center text-stone-400 font-bold uppercase tracking-widest text-[11px] italic">Belum ada data tanya jawab karakteristik produk.</div>
    @endforelse
</div>
    </div>
</div>
@endsection