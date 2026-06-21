@extends('layouts.app')

@section('title', 'Katalog Warisan Budaya')

@section('content')

{{-- MODAL REKOMENDASI BERTAHAP --}}
    @auth
        @php
            // Cek langsung ke database apakah user aktif sudah pernah mengisi kuesioner preferensi
            $checkPreference = DB::table('preferences')->where('user_id', Auth::id())->exists();
        @endphp

        <div x-data="{ 
                step: 1, 
                showSurvey: false 
            }" 
            {{-- Jika ada session show_survey ATAU user belum pernah mengisi data sama sekali, munculkan kuesioner --}}
            x-init="
                @if(session('show_survey') || !$checkPreference)
                    setTimeout(() => { showSurvey = true }, 500);
                @endif
            "
            @open-survey.window="showSurvey = true"
            class="relative z-[999]">
            
            {{-- BACKDROP --}}
            <div x-show="showSurvey" 
                 class="fixed inset-0 z-[999] flex items-center justify-center bg-stone-900/90 backdrop-blur-md"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 style="display: none;">
                
                {{-- BOX MODAL --}}
                <div class="bg-white w-[95%] max-w-lg rounded-[2.5rem] p-8 md:p-10 shadow-2xl relative overflow-hidden" @click.away="showSurvey = false">
                    <div class="flex gap-2 mb-8 justify-center">
                        <div class="h-1.5 rounded-full transition-all duration-500" :class="step >= 1 ? 'w-10 bg-[#8B0000]' : 'w-4 bg-stone-100'"></div>
                        <div class="h-1.5 rounded-full transition-all duration-500" :class="step >= 2 ? 'w-10 bg-[#8B0000]' : 'w-4 bg-stone-100'"></div>
                    </div>

                    {{-- PERBAIKAN: Action form diarahkan ke route preferences.store --}}
                    <form action="{{ route('preferences.store') }}" method="POST">
                        @csrf
                        {{-- STEP 1: MINAT PRODUK --}}
                        <div x-show="step === 1" x-transition:enter="transition ease-out duration-300">
                            <div class="text-center mb-8">
                                <span class="text-[10px] font-black text-[#8B0000] uppercase tracking-[0.4em] block mb-2">Step 01</span>
                                <h3 class="text-2xl font-black uppercase italic tracking-tighter text-stone-900 leading-tight">Apa yang ingin <br> Anda jelajahi hari ini?</h3>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mb-8">
                                @foreach(['Pakaian', 'Kain', 'Aksesoris', 'Tas', 'Ukiran & Pajangan'] as $val)
                                <label class="group cursor-pointer">
                                    <input type="checkbox" name="interests[]" value="{{ $val }}" class="hidden peer">
                                    <div class="py-5 bg-stone-50 border-2 border-stone-100 rounded-2xl text-center transition-all peer-checked:border-[#8B0000] peer-checked:bg-[#8B0000]/5 hover:border-stone-200">
                                        <span class="text-[10px] font-black uppercase italic tracking-tighter text-stone-800">{{ $val }}</span>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            <button type="button" @click="step = 2" class="w-full bg-black text-white py-4 rounded-full font-black uppercase italic tracking-[0.2em] text-[10px] shadow-xl hover:bg-[#8B0000] transition-all">Selanjutnya</button>
                        </div>

                        {{-- STEP 2: TUJUAN PENGGUNA --}}
                        <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" style="display: none;">
                            <div class="text-center mb-8">
                                <span class="text-[10px] font-black text-[#8B0000] uppercase tracking-[0.4em] block mb-2">Step 02</span>
                                <h3 class="text-2xl font-black uppercase italic tracking-tighter text-stone-900 leading-tight">Apa tujuan kunjungan <br> Anda?</h3>
                            </div>
                            <div class="space-y-3 mb-8">
                                @foreach(['Belanja Produk', 'Inspirasi Hadiah', 'Belajar Budaya (KMS)'] as $val)
                                <label class="block cursor-pointer">
                                    <input type="radio" name="goal" value="{{ $val }}" class="hidden peer" required>
                                    <div class="p-5 bg-stone-50 border-2 border-stone-100 rounded-2xl transition-all peer-checked:border-[#8B0000] peer-checked:bg-[#8B0000]/5 flex items-center justify-between">
                                        <span class="text-[10px] font-black uppercase italic tracking-widest text-stone-800">{{ $val }}</span>
                                        <div class="w-4 h-4 rounded-full border-2 border-stone-200 peer-checked:bg-[#8B0000]"></div>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            <div class="flex gap-4">
                                <button type="button" @click="step = 1" class="flex-1 border-2 border-stone-100 py-4 rounded-full font-black uppercase italic text-[10px] text-stone-400">Kembali</button>
                                {{-- PERBAIKAN: Mengubah type button menjadi type submit agar form terkirim ke database --}}
                                <button type="submit" class="flex-1 bg-[#8B0000] text-white py-4 rounded-full font-black uppercase italic text-[10px] tracking-[0.2em]">Selesai</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endauth

    {{-- HERO SECTION --}}
    <section class="relative h-[65vh] md:h-[80vh] bg-stone-200 flex items-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/gambar home.jpeg') }}" class="w-full h-full object-cover opacity-60" alt="Toraja Heritage">
        </div>
        <div class="container mx-auto px-6 relative z-10 text-center md:text-left">
            <span class="text-[#8B0000] font-black tracking-[0.5em] uppercase text-[10px] mb-4 block">Premium Quality</span>
            <h2 class="text-4xl md:text-8xl font-black text-stone-900 leading-[1.1] md:leading-none mb-6 italic">BUDAYA DALAM <br> <span class="text-[#8B0000]">SETIAP KARYA.</span></h2>
            <p class="text-stone-700 max-w-lg mb-10 text-sm md:text-lg italic">Memperkenalkan keindahan budaya Toraja kepada masyarakat luas melalui produk yang unik.</p>
            <a href="#katalog" class="bg-[#8B0000] text-white px-10 py-4 rounded-full font-black shadow-2xl hover:bg-black transition-all inline-block text-xs md:text-base uppercase tracking-widest">Mulai Belanja</a>
        </div>
    </section>

    {{-- KATALOG SECTION --}}
    <section id="katalog" class="py-20 container mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-8">
            <div>
                {{-- PERBAIKAN SINKRONISASI: Mengubah $label menjadi $rekomendasiTitle --}}
                <h3 class="text-3xl md:text-4xl font-black text-stone-800 uppercase tracking-tighter italic leading-none">
                    {{ $rekomendasiTitle ?? 'Katalog Terbaru' }}
                </h3>
                
                {{-- Tambahan Penanda Algoritma untuk keperluan Sidang Skripsi --}}
                @if(isset($isPersonalized) && $isPersonalized)
                    <p class="text-[#8B0000] text-[9px] font-black uppercase tracking-[0.2em] mt-2 italic">
                        ✨ Diurutkan menggunakan Perhitungan Jaccard Similarity berdasarkan minat Anda
                    </p>
                @endif
                <div class="h-1.5 w-20 bg-[#8B0000] mt-4"></div>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-10">
            @forelse($products as $p)
                <div class="group relative"> {{-- Diberikan class relative untuk indikator badge --}}
                    
                    {{-- INDIKATOR PRESENTASE COCOK ALGORITMA --}}
                    @if(isset($isPersonalized) && $isPersonalized && isset($p->similarity_score) && $p->similarity_score > 0)
                        <div class="absolute top-4 right-4 z-30 bg-[#8B0000] text-white px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest shadow-md">
                            Match: {{ $p->similarity_score * 100 }}%
                        </div>
                    @endif

                    <div class="aspect-[4/5] bg-stone-100 rounded-[2.5rem] overflow-hidden relative mb-6 shadow-sm border border-stone-50 transition-all duration-500 group-hover:shadow-2xl group-hover:-translate-y-2">
                        @php
                            $img = $p->images->first();
                            $imagePath = $img ? (str_contains($img->image, 'products/') ? $img->image : 'products/' . $img->image) : null;
                        @endphp
                        
                        @if($imagePath)
                            <img src="{{ asset('storage/' . $imagePath) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700" alt="{{ $p->name }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-stone-200 font-black text-stone-400 text-[10px]">NO IMAGE</div>
                        @endif
                        
                        <div class="absolute inset-0 bg-stone-900/40 opacity-0 group-hover:opacity-100 transition duration-500 flex items-center justify-center p-6">
                            <a href="{{ route('product.show', $p->id) }}" class="w-full bg-white text-stone-900 py-4 rounded-2xl font-black text-[10px] text-center uppercase tracking-widest hover:bg-[#8B0000] hover:text-white transition shadow-2xl">Lihat Produk</a>
                        </div>
                    </div>
                    <div class="px-2">
                        <span class="text-[8px] font-black uppercase tracking-[0.2em] text-[#8B0000] mb-1 block italic">{{ $p->category }}</span>
                        <h4 class="text-sm md:text-lg font-black text-stone-800 mb-1 truncate uppercase italic leading-none">{{ $p->name }}</h4>
                        <p class="text-base md:text-xl font-black text-stone-900 italic tracking-tighter uppercase leading-none">Rp {{ number_format($p->price, 0, ',', '.') }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center border-2 border-dashed border-stone-200 rounded-[3rem]">
                    <p class="text-stone-400 font-bold uppercase tracking-widest text-xs italic">Koleksi tidak ditemukan.</p>
                </div>
            @endforelse
        </div>
    </section>

    @once
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endonce
@endsection