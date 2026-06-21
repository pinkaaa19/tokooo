@extends('layouts.app')

@section('title', 'Tentang Kami')

@section('content')
<div class="bg-[#F9F7F2]">
    {{-- Section 1: Hero --}}
    <section class="py-24 container mx-auto px-6">
        <div class="max-w-4xl">
            <span class="text-[#8B0000] font-black uppercase tracking-[0.4em] text-[10px] italic mb-4 block">Personal Branding & Heritage</span>
            <h2 class="text-5xl md:text-7xl font-black text-stone-900 uppercase italic tracking-tighter leading-none mb-8">
                Di Balik Layar <br> <span class="text-[#8B0000]">Aldy Art.</span>
            </h2>
        </div>
    </section>

    {{-- Section 2: Profil Pemilik --}}
    <section class="py-20 bg-white shadow-inner">
        <div class="container mx-auto px-6 grid md:grid-cols-2 gap-16 items-center">
            <div class="relative group">
                {{-- Foto Kamu (Pemilik) --}}
                <div class="absolute -inset-4 border-2 border-stone-200 rounded-[3.5rem] group-hover:border-[#8B0000] transition duration-500"></div>
                <img src="{{ asset('images/owner.png') }}" class="rounded-[3rem] shadow-2xl relative z-10 w-full aspect-[4/5] object-cover" alt="Aldy - Owner Aldy Art">
            </div>
            <div>
                <h3 class="text-3xl font-black uppercase italic mb-2 tracking-tighter text-stone-900">Aldy Art</h3>
                <p class="text-[#8B0000] font-bold uppercase tracking-widest text-[10px] mb-6 italic">Founder & Creative Director</p>
                <div class="space-y-6 text-stone-600 leading-relaxed italic text-sm">
                    <p>
                        "Ketertarikan saya terhadap seni kriya Toraja dimulai dari keinginan sederhana: melihat motif leluhur kita tetap relevan di tangan generasi masa kini. Aldy Art lahir dari semangat untuk mengeksplorasi kembali kain tradisional seperti Sarita ke dalam bentuk yang lebih modern."
                    </p>

                </div>
            </div>
        </div>
    </section>

    {{-- Section 3: Pameran & Festival --}}
    <section class="py-24 container mx-auto px-6">
        <div class="text-center mb-16">
            <h3 class="text-3xl font-black uppercase italic tracking-tighter text-stone-900">Jejak Langkah Festival</h3>
            <div class="h-1 w-20 bg-[#8B0000] mx-auto mt-4"></div>
            <p class="mt-6 text-stone-500 text-xs font-bold uppercase tracking-widest">Aktif berkontribusi dalam berbagai ajang promosi budaya.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Item Pameran 1 --}}
            <div class="group overflow-hidden rounded-[2.5rem] bg-white border border-stone-100 shadow-sm hover:shadow-2xl transition duration-500">
                <div class="h-64 overflow-hidden">
                    <img src="{{ asset('images/pameran1.jpg') }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700 grayscale group-hover:grayscale-0">
                </div>
                <div class="p-8">
                    <span class="text-[#8B0000] text-[9px] font-black uppercase">2021 • Expo UMKM pekan pemuda</span>
                    <h4 class="text-lg font-black text-stone-800 uppercase italic mt-2">Expo</h4>
                    <p class="text-xs text-stone-500 mt-2 italic leading-relaxed">Berpartisipasi dalam kegiatan expo sebagai upaya memperluas pemasaran sekaligus mengenalkan budaya lokal kepada masyarakat luas.</p>
                </div>
            </div>

            {{-- Item Pameran 2 --}}
            <div class="group overflow-hidden rounded-[2.5rem] bg-white border border-stone-100 shadow-sm hover:shadow-2xl transition duration-500">
                <div class="h-64 overflow-hidden">
                    <img src="{{ asset('images/pameran2.jpg') }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700 grayscale group-hover:grayscale-0">
                </div>
                <div class="p-8">
                    <span class="text-[#8B0000] text-[9px] font-black uppercase">2021 • Toraja Highland Festival</span>
                    <h4 class="text-lg font-black text-stone-800 uppercase italic mt-2">Festival Budaya Toraja</h4>
                    <p class="text-xs text-stone-500 mt-2 italic leading-relaxed">Terpilih sebagai salah satu UMKM yang mempresentasikan inovasi produk etnik.</p>
                </div>
            </div>

            {{-- Item Pameran 3 --}}
            <div class="group overflow-hidden rounded-[2.5rem] bg-white border border-stone-100 shadow-sm hover:shadow-2xl transition duration-500">
                <div class="h-64 overflow-hidden">
                    <img src="{{ asset('images/pameran3.jpg') }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700 grayscale group-hover:grayscale-0">
                </div>
                <div class="p-8">
                    <span class="text-[#8B0000] text-[9px] font-black uppercase">2023 • Toraja highland festival</span>
                    <h4 class="text-lg font-black text-stone-800 uppercase italic mt-2">Festival Budaya Toraja</h4>
                    <p class="text-xs text-stone-500 mt-2 italic leading-relaxed">Menampilkan berbagai produk unggulan berbasis kearifan lokal dengan mengangkat motif tradisional Toraja</p>
                </div>
            </div>

            {{-- Item Pameran 4 --}}
            <div class="group overflow-hidden rounded-[2.5rem] bg-white border border-stone-100 shadow-sm hover:shadow-2xl transition duration-500">
                <div class="h-64 overflow-hidden">
                    <img src="{{ asset('images/pameran5.jpg') }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700 grayscale group-hover:grayscale-0">
                </div>
                <div class="p-8">
                    <span class="text-[#8B0000] text-[9px] font-black uppercase">2023 • Seni rupa dan Kriya_Tammuan mali' </span>
                    <h4 class="text-lg font-black text-stone-800 uppercase italic mt-2">Festival Budaya Toraja</h4>
                    <p class="text-xs text-stone-500 mt-2 italic leading-relaxed">Menampilkan inovasi produk etnik modern yang tetap mempertahankan identitas budaya dan nilai filosofis Toraja.</p>
                </div>
            </div>

            {{-- Item Pameran 5 --}}
            <div class="group overflow-hidden rounded-[2.5rem] bg-white border border-stone-100 shadow-sm hover:shadow-2xl transition duration-500">
                <div class="h-64 overflow-hidden">
                    <img src="{{ asset('images/pameran6.jpg') }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700 grayscale group-hover:grayscale-0">
                </div>
                <div class="p-8">
                    <span class="text-[#8B0000] text-[9px] font-black uppercase">2023 • Toraja Carnaval 2 </span>
                    <h4 class="text-lg font-black text-stone-800 uppercase italic mt-2">Festival Budaya Toraja</h4>
                    <p class="text-xs text-stone-500 mt-2 italic leading-relaxed">Mengangkat potensi UMKM lokal dengan menghadirkan produk berkualitas yang menggabungkan tradisi dan desain modern.</p>
                </div>
            </div>

            {{-- Item Pameran 6 --}}
            <div class="group overflow-hidden rounded-[2.5rem] bg-white border border-stone-100 shadow-sm hover:shadow-2xl transition duration-500">
                <div class="h-64 overflow-hidden">
                    <img src="{{ asset('images/pameran7.jpg') }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700 grayscale group-hover:grayscale-0">
                </div>
                <div class="p-8">
                    <span class="text-[#8B0000] text-[9px] font-black uppercase">2023 • Hari tari dunia </span>
                    <h4 class="text-lg font-black text-stone-800 uppercase italic mt-2">Festival Budaya Toraja</h4>
                    <p class="text-xs text-stone-500 mt-2 italic leading-relaxed">Menampilkan produk fashion khas Toraja sebagai bentuk pelestarian budaya sekaligus inovasi dalam industri kreatif.</p>
                </div>
            </div>

            {{-- Item Pameran 7 --}}
            <div class="group overflow-hidden rounded-[2.5rem] bg-white border border-stone-100 shadow-sm hover:shadow-2xl transition duration-500">
                <div class="h-64 overflow-hidden">
                    <img src="{{ asset('images/pameran8.jpg') }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700 grayscale group-hover:grayscale-0">
                </div>
                <div class="p-8">
                    <span class="text-[#8B0000] text-[9px] font-black uppercase">2022 • festival budaya Toraja</span>
                    <h4 class="text-lg font-black text-stone-800 uppercase italic mt-2">Festival Budaya Toraja</h4>
                    <p class="text-xs text-stone-500 mt-2 italic leading-relaxed">Menampilkan berbagai produk unggulan, serta memberikan edukasi kepada pengunjung melalui demo pembuatan dan cerita produk.</p>
                </div>
            </div>

            {{-- Item Pameran 8 --}}
            <div class="group overflow-hidden rounded-[2.5rem] bg-white border border-stone-100 shadow-sm hover:shadow-2xl transition duration-500">
                <div class="h-64 overflow-hidden">
                    <img src="{{ asset('images/pameran4.jpg') }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700 grayscale group-hover:grayscale-0">
                </div>
                <div class="p-8">
                    <span class="text-[#8B0000] text-[9px] font-black uppercase">2023 • Toraja Internasional Festival</span>
                    <h4 class="text-lg font-black text-stone-800 uppercase italic mt-2">Festival Budaya Toraja</h4>
                    <p class="text-xs text-stone-500 mt-2 italic leading-relaxed">Menghadirkan karya autentik bernilai budaya dengan mengangkat kearifan lokal Toraja dalam setiap produk yang ditampilkan.</p>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection