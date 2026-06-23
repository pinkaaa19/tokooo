@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 md:px-8 py-6 md:py-12" x-data="{ activeInfoTab: 'deskripsi' }">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-10 items-start">

        {{-- KOLOM KIRI: SLIDER GAMBAR --}}
        <div class="relative group">
            <div class="rounded-[2rem] md:rounded-[2.5rem] overflow-hidden aspect-[3/4] bg-gray-100 shadow-xl border border-stone-100 relative">
                @if($product->images->isNotEmpty())
                    @php
                        $allImages = $product->images->pluck('image')->toArray();
                        $firstImg = $allImages[0];
                        $finalPath = str_contains($firstImg, 'products/') ? $firstImg : 'products/' . $firstImg;
                    @endphp

                    <img id="mainImage" 
                         src="{{ asset('storage/' . $finalPath) }}"
                         data-images='@json($allImages)'
                         class="w-full h-full object-cover transition duration-500"
                         alt="{{ $product->name }}">

                    @if(count($allImages) > 1)
                        <button type="button" onclick="prevImage()" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-[#8B0000] hover:text-white p-3 md:p-4 rounded-full shadow-lg transition-all z-10 opacity-0 group-hover:opacity-100 font-bold">❮</button>
                        <button type="button" onclick="nextImage()" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-[#8B0000] hover:text-white p-3 md:p-4 rounded-full shadow-lg transition-all z-10 opacity-0 group-hover:opacity-100 font-bold">❯</button>
                    @endif
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center bg-stone-100 text-stone-400">
                        <span class="text-4xl mb-2">📷</span>
                        <span class="text-[10px] font-black uppercase tracking-widest italic">No Image Available</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- KOLOM TENGAH: INFO PRODUK & SISTEM TAB EDUKASI KMS --}}
        <div class="space-y-6 md:space-y-8">
            <div>
                <h1 class="text-2xl md:text-4xl font-black uppercase italic tracking-tighter text-stone-900 leading-none mb-3 md:mb-4">
                    {{ $product->name }}
                </h1>
                <p class="text-2xl md:text-3xl font-black text-[#8B0000] tracking-tighter">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>
            </div>

            {{-- NAVIGASI TAB INFORMASI PRODUK & KMS --}}
            <div class="flex gap-4 border-b border-stone-100 pb-2 overflow-x-auto custom-scrollbar whitespace-nowrap">
                <button @click="activeInfoTab = 'deskripsi'" :class="activeInfoTab === 'deskripsi' ? 'text-black border-b-2 border-black' : 'text-stone-400'" class="pb-2 text-[10px] font-black uppercase tracking-widest outline-none transition cursor-pointer">Deskripsi</button>
                <button @click="activeInfoTab = 'sop'" :class="activeInfoTab === 'sop' ? 'text-black border-b-2 border-black' : 'text-stone-400'" class="pb-2 text-[10px] font-black uppercase tracking-widest outline-none transition cursor-pointer">📋 Panduan (SOP)</button>
                <button @click="activeInfoTab = 'faq'" :class="activeInfoTab === 'faq' ? 'text-black border-b-2 border-black' : 'text-stone-400'" class="pb-2 text-[10px] font-black uppercase tracking-widest outline-none transition cursor-pointer">💬 Tanya Jawab</button>
            </div>

            {{-- ISI KONTEN TAB --}}
            <div class="min-h-[100px]">
                {{-- TAB 1: DESKRIPSI --}}
                <div x-show="activeInfoTab === 'deskripsi'" class="text-stone-600 leading-relaxed italic text-xs md:text-sm text-justify">
                    {{ $product->description }}
                </div>

                {{-- TAB 2: DISTRIBUSI KNOWLEDGE SOP --}}
                <div x-show="activeInfoTab === 'sop'" x-transition class="space-y-3" style="display: none;">
                    @forelse($sops as $sop)
                        <div x-data="{ openSop: false }" class="bg-white border border-stone-100 rounded-xl overflow-hidden shadow-sm hover:border-[#8B0000]/20 transition-all duration-300">
                            <button @click="openSop = !openSop" type="button" class="w-full text-left p-4 flex justify-between items-center transition-all outline-none">
                                <h4 class="font-black text-xs text-stone-900 uppercase italic">{{ $sop->title }}</h4>
                                <span class="text-stone-400 text-sm font-black" x-text="openSop ? '−' : '+'"></span>
                            </button>

                            <div x-show="openSop" x-transition class="px-4 pb-4 space-y-3">
                                <div class="text-xs text-stone-600 leading-relaxed font-medium bg-stone-50 p-3 rounded-lg">
                                    {!! nl2br(e($sop->description)) !!}
                                </div>
                                
                                <div class="grid grid-cols-2 gap-3">
                                    @if($sop->file_path)
                                        <div class="col-span-2 md:col-span-1">
                                            <img src="{{ asset('storage/' . $sop->file_path) }}" 
                                                class="w-full h-24 object-cover rounded-lg shadow-2sm border border-stone-100">
                                        </div>
                                    @endif
                                    
                                    @if($sop->video_url)
                                        <div class="flex items-end">
                                            <a href="{{ $sop->video_url }}" target="_blank" 
                                            class="flex items-center gap-1.5 text-[8px] font-black uppercase tracking-widest text-[#8B0000] hover:bg-stone-50 px-3 py-2 rounded-lg transition border border-stone-100 shadow-2sm">
                                                ▶ Tonton Video
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <p class="text-stone-400 italic text-xs">Belum ada dokumen SOP perawatan khusus untuk produk ini.</p>
                        </div>
                    @endforelse
                </div>

                {{-- TAB 3: DISTRIBUSI KNOWLEDGE FAQ --}}
                <div x-show="activeInfoTab === 'faq'" x-transition class="space-y-2" style="display: none;">
                    @forelse($faqs as $faq)
                        <div x-data="{ openFaq: false }" class="bg-white border border-stone-100 rounded-xl overflow-hidden shadow-sm hover:border-[#8B0000]/20 transition-all">
                            <button @click="openFaq = !openFaq" type="button" class="w-full text-left p-4 flex justify-between items-center font-bold text-stone-800 text-xs hover:bg-stone-50 transition outline-none">
                                <span>"{{ $faq->question }}"</span>
                                <span class="text-stone-400 text-sm font-black" x-text="openFaq ? '−' : '+'"></span>
                            </button>
                            
                            <div x-show="openFaq" x-transition class="p-4 bg-stone-50/50 border-t border-stone-50 text-xs text-stone-600 leading-relaxed font-medium">
                                {{ $faq->answer }}
                                
                                {{-- FEEDBACK LOOP --}}
                                <div class="mt-4 pt-3 border-t border-stone-200 flex items-center justify-between">
                                    <span class="text-[9px] uppercase font-bold text-stone-400 tracking-wider">Apakah ini membantu?</span>
                                    <div class="flex gap-2">
                                        <button type="button" 
                                                @click.stop="sendFeedback({{ $faq->id }}, 1)" 
                                                class="px-2 py-1 bg-white border border-stone-200 rounded text-[9px] hover:bg-green-50 hover:text-green-700 transition cursor-pointer">
                                            Ya
                                        </button>
                                        <button type="button" 
                                                @click.stop="sendFeedback({{ $faq->id }}, 0)" 
                                                class="px-2 py-1 bg-white border border-stone-200 rounded text-[9px] hover:bg-red-50 hover:text-red-700 transition cursor-pointer">
                                            Tidak
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <p class="text-stone-400 italic text-xs">Belum ada tanya jawab terkait produk ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- SECTION KMS: FILOSOFI MOTIF INTERAKTIF --}}
            <div class="bg-[#F9F7F2] p-5 md:p-6 rounded-[2rem] border border-[#8B0000]/10 shadow-sm">
                <h3 class="font-black uppercase text-[10px] tracking-widest text-[#8B0000] mb-2 flex items-center gap-2">
                    <span class="w-2 h-2 bg-[#8B0000] rounded-full animate-pulse"></span>
                    Tahukah Kamu?
                </h3>
                <p class="text-stone-700 text-xs italic leading-relaxed">
                    Pelajari makna filosofis produk ini melalui 
                    <button type="button" 
                        id="btnOpenKms"
                        data-title="{{ $product->knowledgeData->title ?? $product->name }}"
                        data-desc="{{ $product->knowledgeData->description ?? 'Deskripsi filosofi belum tersedia.' }}"
                        data-type="{{ $product->knowledgeData->type ?? 'image' }}"
                        data-path="{{ isset($product->knowledgeData->file_path) ? asset('storage/' . $product->knowledgeData->file_path) : asset('images/default-kms.jpg') }}"
                        data-video="{{ $product->knowledgeData->video_url ?? '' }}"
                        data-group="{{ $product->knowledgeData->group_name ?? 'Motif Tradisional' }}"
                        data-source="{{ $product->knowledgeData->source ?? 'Eksplorasi Budaya Toraja' }}"
                        data-updated="{{ isset($product->knowledgeData->updated_at) ? $product->knowledgeData->updated_at->format('Y') : date('Y') }}"
                        data-related='@json($product->knowledgeData->relatedMotifs ?? [])'
                        onclick="triggerKmsModal(this)" 
                        class="text-[#8B0000] font-black underline hover:text-black transition decoration-2 underline-offset-4 cursor-pointer">
                        Knowledge Budaya Toraja
                    </button>
                </p>
            </div>
        </div>

        {{-- KOLOM KANAN: FORM ORDER --}}
        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="h-fit md:sticky md:top-10">
            @csrf
            <div class="border border-stone-100 rounded-[2rem] md:rounded-[2.5rem] p-6 md:p-8 shadow-2xl bg-white relative overflow-hidden">
                {{-- Pilihan Warna --}}
                <div class="mb-6 md:mb-8">
                    <p class="font-black uppercase text-[10px] tracking-widest text-gray-400 mb-3">Pilih Warna</p>
                    <div class="flex flex-wrap gap-2.5">
                        @if($product->available_colors)
                            @foreach(explode(',', $product->available_colors) as $color)
                                <label class="cursor-pointer">
                                    <input type="radio" name="color" value="{{ trim($color) }}" class="peer sr-only" required {{ $loop->first ? 'checked' : '' }}>
                                    <div class="px-4 py-2 border-2 border-stone-50 rounded-xl text-[10px] font-black uppercase tracking-widest peer-checked:border-[#8B0000] peer-checked:text-[#8B0000] peer-checked:bg-[#8B0000]/5 transition-all">
                                        {{ trim($color) }}
                                    </div>
                                </label>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- Pilihan Ukuran --}}
                @if($product->available_sizes)
                <div class="mb-6 md:mb-8">
                    <p class="font-black uppercase text-[10px] tracking-widest text-gray-400 mb-3">Pilih Ukuran</p>
                    <div class="grid grid-cols-4 gap-2">
                        @foreach(explode(',', $product->available_sizes) as $size)
                            <label class="cursor-pointer">
                                <input type="radio" name="size" value="{{ trim($size) }}" class="peer sr-only" required {{ $loop->first ? 'checked' : '' }}>
                                <div class="py-2.5 text-center border-2 border-stone-50 rounded-xl font-black text-[10px] peer-checked:border-[#8B0000] peer-checked:text-[#8B0000] peer-checked:bg-[#8B0000]/5 transition-all">
                                    {{ trim($size) }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Quantity --}}
                <div class="mb-6 md:mb-8">
                    <p class="font-black uppercase text-[10px] tracking-widest text-gray-400 mb-3">Jumlah Pesanan</p>
                    <div class="flex items-center border-2 border-stone-100 rounded-2xl overflow-hidden">
                        <button type="button" onclick="decrement()" class="w-12 h-12 md:w-14 md:h-14 bg-stone-50 hover:bg-stone-100 font-bold text-xl transition">−</button>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" readonly data-price="{{ $product->price }}"
                               class="w-full text-center font-black text-xl border-none focus:ring-0">
                        <button type="button" onclick="increment()" class="w-12 h-12 md:w-14 md:h-14 bg-stone-50 hover:bg-stone-100 font-bold text-xl transition">+</button>
                    </div>
                </div>

                <div class="flex justify-between items-center mb-6 md:mb-8 bg-stone-50 p-4 md:p-5 rounded-2xl">
                    <span class="text-gray-400 text-[10px] font-black uppercase tracking-widest">Total Harga</span>
                    <span id="totalPriceDisplay" class="font-black text-xl text-[#8B0000]">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </span>
                </div>

                <div class="space-y-3">
                    @auth
                        <button type="submit" name="button_action" value="add_to_cart" class="bg-stone-100 text-stone-900 w-full py-4.5 rounded-2xl font-black uppercase text-[10px] tracking-[0.2em] hover:bg-stone-200 transition-all active:scale-95 cursor-pointer">
                            + Keranjang
                        </button>
                        <button type="submit" name="button_action" value="add_to_checkout" class="bg-black text-white w-full py-4.5 rounded-2xl font-black uppercase text-[10px] tracking-[0.2em] hover:bg-[#8B0000] transition-all shadow-xl shadow-[#8B0000]/20 active:scale-95 cursor-pointer">
                            Beli Sekarang
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="block w-full py-4.5 bg-[#8B0000] text-white text-center rounded-2xl font-black uppercase text-[10px] tracking-[0.2em] hover:bg-black transition-all shadow-lg">
                            Login Sekarang
                        </a>
                    @endauth
                </div>
            </div>
        </form>
    </div>
</div>

{{-- MODAL KMS (KNOWLEDGE MANAGEMENT SYSTEM) --}}
<div id="kmsModal" class="hidden fixed inset-0 bg-black/75 backdrop-blur-md flex items-center justify-center z-[100] p-3 md:p-6 transition-all duration-300">
    <div class="flex flex-col md:flex-row items-center justify-center max-w-5xl w-full gap-4 md:gap-8 h-[95vh] md:h-auto">
        
        {{-- SISI KIRI: ASISTEN VIRTUAL TORAJA (Disembunyikan di HP agar menghemat layar) --}}
        <div id="narrator-container" class="hidden md:flex flex-col items-center justify-center w-full md:w-1/3 animate-in fade-in slide-in-from-left duration-700">
            <div class="relative">
                <div id="voice-waves" class="absolute -inset-8 hidden">
                    <div class="absolute inset-0 bg-[#8B0000] rounded-full opacity-20 animate-ping"></div>
                    <div class="absolute inset-0 bg-[#8B0000] rounded-full opacity-10 animate-pulse scale-125"></div>
                </div>
                <div class="relative z-10 drop-shadow-[0_15px_40px_rgba(139,0,0,0.25)]">
                    <img id="avatar-img" 
                         src="{{ asset('images/animasi2.png') }}" 
                         data-diam="{{ asset('images/animasi2.png') }}"
                         data-bicara="{{ asset('images/animasi.png') }}"
                         class="w-48 h-auto md:w-72 transition-all duration-500 asisten-idle">
                </div>
            </div>
            <p id="narrator-status" class="text-[9px] font-black uppercase text-white/80 mt-6 tracking-[0.2em] opacity-0 transition-opacity italic animate-pulse">
                Menjelaskan Makna Budaya...
            </p>
        </div>

        {{-- SISI KANAN: POPUP KONTEN KMS --}}
        <div class="bg-[#FDFBF7] w-full max-w-xl rounded-[2rem] md:rounded-[3rem] shadow-2xl relative flex flex-col h-[85vh] md:max-h-[80vh] overflow-hidden border border-stone-200 animate-in fade-in slide-in-from-right duration-500">
            
            {{-- HEADER INTERNAL MODAL --}}
            <div class="bg-[#8B0000] p-4 md:p-6 text-white text-center relative shrink-0 sticky top-0 z-20">
                <div class="flex justify-center mb-0.5">
                    <span class="bg-white/20 px-3 py-0.5 rounded-full text-[6px] font-black uppercase tracking-[0.3em]">Knowledge Culture</span>
                </div>
                <h2 id="kmsTitle" class="text-sm md:text-lg font-black uppercase italic tracking-tighter leading-tight">Nama Motif</h2>
                <p class="text-[7px] uppercase tracking-[0.2em] text-white/60 italic mt-0.5 leading-tight">Digitalisasi Filosofi Budaya Toraja</p>
                <button type="button" onclick="closeKmsModal()" class="absolute right-5 top-1/2 -translate-y-1/2 md:top-6 md:translate-y-0 text-white/60 hover:text-white transition-all text-sm cursor-pointer">✕</button>
            </div>

            {{-- AREA SCROLL INTERNAL (Mencegah double scrollbar di HP) --}}
            <div id="kmsScrollArea" class="p-5 md:p-7 overflow-y-auto custom-scrollbar flex-1 space-y-6">
                <div id="kmsBackNav" class="hidden pb-1 text-left">
                    <button type="button" onclick="backToPreviousMotif()" class="flex items-center gap-2 group outline-none">
                        <div class="w-5 h-5 rounded-full bg-[#8B0000]/10 flex items-center justify-center group-hover:-translate-x-0.5 transition-transform text-[#8B0000]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M15 19l-7-7 7-7" />
                            </svg>
                        </div>
                        <span class="text-[8px] font-black uppercase tracking-widest text-[#8B0000]">Kembali ke Motif Sebelumnya</span>
                    </button>
                </div>

                {{-- KONTEN GAMBAR / VIDEO REKOMENDASI --}}
                <div id="kmsMediaContainer" class="w-full"></div>

                {{-- SUB-METADATA BOX --}}
                <div class="grid grid-cols-2 gap-px bg-stone-200 border border-stone-200 rounded-xl overflow-hidden shadow-sm text-center">
                    <div class="bg-white p-2.5 flex flex-col items-center">
                        <span class="text-[7px] text-gray-400 font-bold uppercase mb-0.5 tracking-widest">Nama Motif</span> 
                        <span id="valName" class="text-[8px] font-black italic text-stone-800 uppercase"></span>
                    </div>
                    <div class="bg-white p-2.5 flex flex-col items-center border-l border-stone-50">
                        <span class="text-[7px] text-gray-400 font-bold uppercase mb-0.5 tracking-widest">Kelompok Motif</span> 
                        <span id="valGroup" class="text-[8px] font-black italic text-[#8B0000] uppercase"></span>
                    </div>
                </div>

                {{-- DESKRIPSI FILOSOFI MAKNA --}}
                <div class="space-y-2 bg-white p-4 rounded-xl border border-stone-100 shadow-3sm">
                    <div class="flex items-center justify-center gap-2 mb-1 text-center">
                        <span class="h-[1px] w-6 bg-stone-200"></span>
                        <p class="text-[8px] text-[#8B0000] uppercase tracking-[0.25em] font-black italic">Filosofi & Makna Budaya</p>
                        <span class="h-[1px] w-6 bg-stone-200"></span>
                    </div>
                    <div id="kmsDescription" class="italic text-stone-600 leading-relaxed text-xs text-justify whitespace-pre-line custom-scrollbar"></div>
                </div>

                {{-- AREA MOTIF TERKAIT --}}
                <div id="sectionRelated" class="pt-4 border-t border-stone-100 hidden">
                    <h4 class="text-[8px] font-black uppercase tracking-widest text-[#8B0000] mb-3 flex items-center gap-1.5 italic">
                        <span class="text-[8px]">✨</span> Motif Terkait
                    </h4>
                    <div id="relatedContainer"></div>
                </div>

                {{-- METADATA SUMBER --}}
                <div class="flex justify-between items-center pt-2 border-t border-stone-100 text-[7px] font-black uppercase text-stone-300 tracking-[0.2em]">
                    <span>Sumber: <a id="valSourceLink" href="#" target="_blank" class="hover:text-[#8B0000] underline decoration-stone-200 transition-all text-left"><span id="valSource"></span></a></span>
                    <span>Update: <span id="valDate"></span></span>
                </div>

                <button type="button" onclick="closeKmsModal()" class="w-full bg-black text-white py-4 rounded-xl font-black uppercase text-[9px] tracking-[0.2em] hover:bg-[#8B0000] transition-all cursor-pointer">
                    Lanjutkan Belanja
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // --- SLIDER LOGIC ---
    const mainImgElem = document.getElementById("mainImage");
    const imagesData = mainImgElem ? JSON.parse(mainImgElem.dataset.images) : [];
    let curIdx = 0;

    function updateSliderImage() {
        if(!mainImgElem) return;
        const rawPath = imagesData[curIdx];
        const finalPath = rawPath.includes('products/') ? rawPath : 'products/' + rawPath;
        mainImgElem.classList.add('opacity-0');
        setTimeout(() => {
            mainImgElem.src = "{{ asset('storage') }}/" + finalPath;
            mainImgElem.classList.remove('opacity-0');
        }, 150);
    }
    function nextImage() { if (imagesData.length > 1) { curIdx = (curIdx + 1) % imagesData.length; updateSliderImage(); } }
    function prevImage() { if (imagesData.length > 1) { curIdx = (curIdx - 1 + imagesData.length) % imagesData.length; updateSliderImage(); } }

    // --- PRICE LOGIC ---
    const qtyInput = document.getElementById("quantity");
    const priceDisplay = document.getElementById("totalPriceDisplay");
    const basePrice = parseInt(qtyInput ? qtyInput.getAttribute('data-price') : 0);

    function updatePrice() {
        let total = basePrice * parseInt(qtyInput.value);
        priceDisplay.innerText = "Rp " + total.toLocaleString('id-ID');
    }
    function increment() { qtyInput.value = parseInt(qtyInput.value) + 1; updatePrice(); }
    function decrement() { if (parseInt(qtyInput.value) > 1) { qtyInput.value = parseInt(qtyInput.value) - 1; updatePrice(); } }

    // --- KMS MODAL LOGIC WITH HISTORY & SPEECH SYNTHESIS ---
    const synth = window.speechSynthesis;
    let kmsHistory = [];
    let currentKmsData = null;
    let typewriterInterval = null;
    let lipSyncInterval = null;

    if (typeof speechSynthesis !== 'undefined' && speechSynthesis.onvoiceschanged !== undefined) {
        speechSynthesis.onvoiceschanged = () => {
            synth.getVoices();
        };
    }

    function updateKmsContent(data, isBackAction = false) {
        // MATIKAN TOTAL SUARA & ANIMASI SEBELUMNYA (Mencegah Suara Ganda / Bertumpuk)
        if (synth) {
            synth.cancel();
        }
        clearInterval(typewriterInterval);
        clearInterval(lipSyncInterval);

        if (!isBackAction && currentKmsData) {
            kmsHistory.push(currentKmsData);
        }
        currentKmsData = data;

        const backNav = document.getElementById('kmsBackNav');
        if (backNav) {
            kmsHistory.length > 0 ? backNav.classList.remove('hidden') : backNav.classList.add('hidden');
        }
        
        const scrollArea = document.getElementById('kmsScrollArea');
        if (scrollArea) scrollArea.scrollTop = 0;

        document.getElementById('kmsTitle').innerText = data.title;
        document.getElementById('valName').innerText = data.title;
        document.getElementById('valGroup').innerText = data.group_name || 'Motif Umum';
        document.getElementById('valSource').innerText = data.source || 'Aldy Art Toraja';
        
        const sourceLink = document.getElementById('valSourceLink');
        if (sourceLink) {
            if (data.source && (data.source.includes('http://') || data.source.includes('https://'))) {
                sourceLink.href = data.source;
                sourceLink.style.pointerEvents = 'auto';
            } else {
                sourceLink.href = 'javascript:void(0)';
                sourceLink.style.pointerEvents = 'none';
            }
        }
        
        const dateObj = data.updated_at ? new Date(data.updated_at) : new Date();
        document.getElementById('valDate').innerText = dateObj.getFullYear();

        const narrationText = `Motif ${data.title}. ${data.description}`;
        playKmsNarration(narrationText);
        runTypewriter('kmsDescription', data.description);

        const mediaContainer = document.getElementById('kmsMediaContainer');
        if (mediaContainer) {
            mediaContainer.innerHTML = ''; 
            let hasMedia = false;

            // RESPONSIVE LAYOUT MEDIA: 1 kolom di HP, 2 kolom di Laptop
            mediaContainer.className = "grid grid-cols-1 md:grid-cols-2 gap-3 w-full";

            const vUrl = data.video_url || data.video;
            const vId = vUrl && vUrl.trim() !== "" ? extractYoutubeId(vUrl) : null;
            if (vId) {
                hasMedia = true;
                mediaContainer.innerHTML += `<div class="w-full aspect-video md:aspect-square bg-black rounded-xl overflow-hidden shadow-sm border border-stone-200"><iframe class="w-full h-full" src="https://www.youtube.com/embed/${vId}" frameborder="0" allowfullscreen></iframe></div>`;
            }

            let imgPath = data.file_path || data.path || '';
            if (imgPath && imgPath !== 'null' && imgPath.trim() !== "") {
                if (!imgPath.includes('http') && !imgPath.startsWith('storage/')) imgPath = '/storage/' + imgPath;
                hasMedia = true;
                mediaContainer.innerHTML += `<div class="w-full aspect-square bg-stone-50 rounded-xl overflow-hidden shadow-sm border border-stone-200"><img src="${imgPath}" class="w-full h-full object-cover animate-in"></div>`;
            }
            if (!hasMedia) {
                mediaContainer.className = "w-full";
                mediaContainer.innerHTML = `<div class="w-full aspect-[2/1] bg-stone-50 rounded-xl flex flex-col items-center justify-center text-stone-300 border border-dashed border-stone-200"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg><span class="text-[8px] font-black uppercase tracking-[0.2em]">Dokumentasi Belum Tersedia</span></div>`;
            }
        }

        const relatedContainer = document.getElementById('relatedContainer');
        const sectionRelated = document.getElementById('sectionRelated');
        if (relatedContainer && sectionRelated) {
            relatedContainer.innerHTML = '';
            if (data.related_motifs && data.related_motifs.length > 0) { 
                sectionRelated.classList.remove('hidden');
                
                // RESPONSIVE LAYOUT REKOMENDASI MOTIF: 3 kolom di HP, 4 kolom di Laptop
                relatedContainer.className = "grid grid-cols-3 md:grid-cols-4 gap-2 mt-1";
                
                data.related_motifs.forEach(motif => {
                    let mPath = motif.file_path || '';
                    if (mPath && !mPath.includes('http') && !mPath.startsWith('storage/')) mPath = '/storage/' + mPath;
                    const card = document.createElement('div');
                    card.className = "bg-stone-50/50 p-1.5 rounded-xl border border-stone-100 shadow-3sm cursor-pointer hover:border-[#8B0000] transition group text-center";
                    card.onclick = () => updateKmsContent(motif);
                    card.innerHTML = `<div class="w-full aspect-square bg-white rounded-lg overflow-hidden mb-1"><img src="${mPath}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" onerror="this.src='/images/default-kms.jpg'"></div><p class="text-[7px] font-black uppercase text-center truncate px-0.5">${motif.title}</p>`;
                    relatedContainer.appendChild(card);
                });
            } else { sectionRelated.classList.add('hidden'); }
        }
    }

    function playKmsNarration(text) {
        if (!('speechSynthesis' in window)) return;

        // PENGHENTIAN SUARA MUTLAK
        synth.cancel();
        clearInterval(lipSyncInterval);
        
        const container = document.getElementById('narrator-container');
        const waves = document.getElementById('voice-waves');
        const status = document.getElementById('narrator-status');
        const avatar = document.getElementById('avatar-img');
        if (!avatar) return;

        const imgDiam = avatar.getAttribute('data-diam');
        const imgBicara = avatar.getAttribute('data-bicara');

        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'id-ID';
        utterance.rate = 0.88; 
        utterance.pitch = 1.0; 

        const availableVoices = synth.getVoices();
        const lockedVoice = availableVoices.find(voice => 
            voice.lang === 'id-ID' && 
            (voice.name.includes('Google') || voice.name.includes('Natural') || voice.name.includes('Microsoft'))
        ) || availableVoices.find(voice => voice.lang === 'id-ID');

        if (lockedVoice) {
            utterance.voice = lockedVoice;
        }

        utterance.onstart = () => {
            if (container) container.classList.remove('hidden');
            if (waves) waves.classList.remove('hidden');
            if (status) status.classList.add('opacity-100');
            avatar.classList.add('scale-105', '-translate-y-2');
            
            lipSyncInterval = setInterval(() => {
                avatar.src = (avatar.src === imgDiam) ? imgBicara : imgDiam;
            }, 140);
        };

        utterance.onend = () => {
            if (waves) waves.classList.add('hidden');
            if (status) status.classList.remove('opacity-100');
            avatar.classList.remove('scale-105', '-translate-y-2');
            
            clearInterval(lipSyncInterval);
            avatar.src = imgDiam;

            setTimeout(() => { 
                if (!synth.speaking && container) container.classList.add('hidden'); 
            }, 2000);
        };

        utterance.onerror = () => {
            clearInterval(lipSyncInterval);
            avatar.src = imgDiam;
        };

        synth.speak(utterance);
    }

    function runTypewriter(id, text) {
        clearInterval(typewriterInterval);
        const el = document.getElementById(id);
        if (!el) return;
        el.innerHTML = '';
        let i = 0;
        typewriterInterval = setInterval(() => {
            if (i < text.length) {
                el.innerHTML += text.charAt(i);
                i++;
            } else { clearInterval(typewriterInterval); }
        }, 25);
    }

    function backToPreviousMotif() {
        if (kmsHistory.length > 0) {
            const previous = kmsHistory.pop();
            updateKmsContent(previous, true);
        }
    }

    // FIX PENUTUP MODAL SEMPURNA (Suara Terhenti Total)
    function closeKmsModal() {
        const modal = document.getElementById('kmsModal');
        const container = document.getElementById('narrator-container');
        if (modal) modal.classList.add('hidden');
        if (container) container.classList.add('hidden');
        document.body.style.overflow = 'auto';
        
        synth.cancel();
        clearInterval(typewriterInterval);
        clearInterval(lipSyncInterval);
    }

    function triggerKmsModal(btn) {
        if (!btn) return;
        kmsHistory = [];
        currentKmsData = null;
        updateKmsContent({
            title: btn.getAttribute('data-title'),
            description: btn.getAttribute('data-desc'),
            video: btn.getAttribute('data-video'),
            file_path: btn.getAttribute('data-path'),
            group_name: btn.getAttribute('data-group'),
            source: btn.getAttribute('data-source'),
            updated_at: btn.getAttribute('data-updated'),
            related_motifs: JSON.parse(btn.getAttribute('data-related') || '[]')
        });
        const modal = document.getElementById('kmsModal');
        if (modal) modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; 
    }

    function extractYoutubeId(url) {
        if (!url) return null;
        const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
        const match = url.match(regExp);
        return (match && match[2].length === 11) ? match[2] : null;
    }

    window.onclick = function(e) { if (e.target.id == 'kmsModal') closeKmsModal(); }

    function sendFeedback(faqId, status) {
        const token = "{{ csrf_token() }}";
        fetch("{{ route('faq.feedback') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": token
            },
            body: JSON.stringify({ faq_id: faqId, is_helpful: status })
        })
        .then(res => res.json())
        .then(data => { alert(data.message); })
        .catch(err => { console.error("Error:", err); });
    }
</script>

<style>
    @keyframes asistenFloat {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-6px) rotate(0.5deg); }
    }
    .asisten-idle { animation: asistenFloat 4s ease-in-out infinite; }

    /* INTEGRASI CUSTOM SCROLLBAR & TOUCH OPTIMIZATION DI HP */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #8B0000; border-radius: 10px; }
    
    #kmsScrollArea {
        -webkit-overflow-scrolling: touch;
    }

    /* ANIMASI POP-UP MODAL */
    .animate-in { animation: fadeIn 0.22s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: scale(0.99); } to { opacity: 1; transform: scale(1); } }
</style>
@endsection ```
