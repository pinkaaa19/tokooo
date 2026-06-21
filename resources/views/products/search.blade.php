@extends('layouts.app')

@section('title', 'Cari Produk Budaya - Aldy Art')

@section('content')
<div class="bg-[#F9F7F2] min-h-screen py-16" x-data="{ 
    isListening: false,
    startSpeech() {
        if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
            alert('Browser Anda tidak mendukung pencarian suara.');
            return;
        }
        const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
        recognition.lang = 'id-ID';
        recognition.start();
        this.isListening = true;
        
        recognition.onresult = (event) => {
            const transcript = event.results[0][0].transcript;
            this.$refs.searchInput.value = transcript;
            this.isListening = false;
            this.$refs.searchForm.submit();
        };
        
        recognition.onerror = () => { this.isListening = false; };
        recognition.onspeechend = () => { this.isListening = false; recognition.stop(); };
    }
}">
    <div class="container mx-auto px-6">
        
        {{-- Header & Search Box (Perbaikan Sesuai Gambar image_29ccd3.png) --}}
        <div class="max-w-4xl mx-auto mb-16 text-center">
            <h2 class="text-4xl font-black text-stone-900 uppercase italic tracking-tighter mb-8">
                Jelajahi <span class="text-[#8B0000]">Budaya</span> Toraja
            </h2>
            
            <form x-ref="searchForm" action="{{ route('products.search') }}" method="GET" class="relative group max-w-2xl mx-auto">
                <input 
                    x-ref="searchInput"
                    type="text" 
                    name="query" 
                    value="{{ $query ?? '' }}"
                    placeholder="Cari motif atau produk..." 
                    class="w-full bg-white border-none rounded-full py-5 px-8 pr-24 shadow-sm focus:ring-2 focus:ring-[#8B0000] text-stone-800 font-bold italic text-sm transition-all"
                    autofocus
                >
                
                <div class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center gap-3">
                    {{-- Tombol Mic --}}
                    <button 
                        type="button" 
                        @click="startSpeech()"
                        class="p-2 rounded-full transition-colors"
                        :class="isListening ? 'text-red-600 animate-pulse' : 'text-stone-400 hover:text-[#8B0000]'"
                        title="Cari dengan suara"
                    >
                        <i data-lucide="mic" class="w-6 h-6"></i>
                    </button>

                    {{-- Tombol Submit --}}
                    <button type="submit" class="p-2 text-stone-800 hover:text-[#8B0000] transition-colors">
                        <i data-lucide="search" class="w-6 h-6"></i>
                    </button>
                </div>
            </form>
        </div>

        @if(isset($query) && $query != '')
            {{-- Info Hasil Pencarian --}}
            <div class="mb-12 border-l-4 border-[#8B0000] pl-6">
                <h3 class="text-2xl font-black text-stone-900 uppercase italic tracking-tighter">
                    Hasil: <span class="text-[#8B0000]">"{{ $query }}"</span>
                </h3>
                <p class="text-stone-400 text-[10px] font-bold uppercase tracking-[0.2em] mt-2">
                    Ditemukan {{ $products->count() }} Produk terkait Nama produk
                </p>
            </div>

            @if($products->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    @foreach($products as $product)
                        {{-- Card Produk (Sama seperti sebelumnya) --}}
                        <div class="bg-white rounded-[2.5rem] overflow-hidden border border-stone-100 shadow-sm hover:shadow-2xl transition-all duration-500 group">
                            <div class="aspect-[4/5] overflow-hidden bg-stone-100 relative">
                                <span class="absolute top-4 left-4 z-10 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest text-stone-900 shadow-sm">
                                    {{ $product->category }}
                                </span>
                                @if($product->images && $product->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . (Str::contains($product->images->first()->image, 'products/') ? $product->images->first()->image : 'products/' . $product->images->first()->image)) }}" 
                                    class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-[10px] font-bold uppercase">No Image</div>
                                @endif
                            </div>
                            <div class="p-8">
                                @if($product->knowledgeContent)
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="w-1 h-3 bg-[#8B0000]"></span>
                                        <p class="text-[#8B0000] text-[9px] font-black uppercase italic tracking-tighter">Motif: {{ $product->knowledgeContent->title }}</p>
                                    </div>
                                @endif
                                <h3 class="text-lg font-black text-stone-800 uppercase italic leading-none mb-4 group-hover:text-[#8B0000] transition-colors">{{ $product->name }}</h3>
                                <div class="flex justify-between items-center pt-4 border-t border-stone-50">
                                    <p class="text-stone-900 font-black italic">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                    <a href="{{ route('product.show', $product->id) }}" class="bg-stone-900 text-white p-3 rounded-2xl hover:bg-[#8B0000] transition-all shadow-lg"><i data-lucide="arrow-right" class="w-4 h-4"></i></a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- State Kosong --}}
                <div class="py-32 text-center bg-white rounded-[3rem] border border-dashed border-stone-200">
                    <i data-lucide="search-x" class="w-12 h-12 text-stone-300 mx-auto mb-4"></i>
                    <h3 class="text-xl font-black text-stone-800 uppercase italic">Tidak Ada Hasil</h3>
                    <p class="text-stone-400 text-[10px] font-bold uppercase tracking-widest mt-2">Coba cari "Pa'Gellu" atau "Tenun Toraja"</p>
                </div>
            @endif
        @endif
    </div>
</div>
@endsection