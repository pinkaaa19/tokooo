@extends('layouts.admin')

@section('admin_content')
<div class="mb-10 flex items-center justify-between">
    <div>
        <h2 class="text-3xl font-black text-gray-900 uppercase italic tracking-tighter">Edit <span class="text-[#8B0000]">Produk</span></h2>
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">ID Produk: #{{ $product->id }}</p>
    </div>
    <a href="{{ route('admin.products.index') }}" class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-black transition">
        &larr; Kembali ke Daftar
    </a>
</div>

{{-- Notifikasi Error --}}
@if ($errors->any())
    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-2xl">
        <p class="text-[10px] font-black uppercase text-red-600 mb-2">Terjadi Kesalahan Input:</p>
        <ul class="list-disc list-inside text-xs text-red-500 italic">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white p-10 rounded-[3rem] shadow-sm border border-gray-100 max-w-4xl mb-20">
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        {{-- 1. INFORMASI UTAMA (Nama, Harga, Kategori) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-1">
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Nama Produk</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" 
                    class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#8B0000] font-bold text-stone-700" required>
            </div>
            <div>
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Harga Jual (Rp)</label>
                <input type="number" name="price" value="{{ old('price', $product->price) }}" 
                    class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#8B0000] font-black text-[#8B0000]" required>
            </div>
            <div>
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Kategori Produk</label>
                <select name="category" class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#8B0000] font-bold text-stone-600 cursor-pointer" required>
                    <option value="" disabled>-- Pilih Kategori --</option>
                    <option value="Pakaian" {{ old('category', $product->category) == 'Pakaian' ? 'selected' : '' }}>Pakaian</option>
                    <option value="Kain" {{ old('category', $product->category) == 'Kain' ? 'selected' : '' }}>Kain </option>
                    <option value="Tas" {{ old('category', $product->category) == 'Tas' ? 'selected' : '' }}>Tas </option>
                    <option value="Aksesoris" {{ old('category', $product->category) == 'Aksesoris' ? 'selected' : '' }}>Aksesoris</option>
                    <option value="Ukiran & Pajangan" {{ old('category', $product->category) == 'Ukiran & Pajangan' ? 'selected' : '' }}>Ukiran & Pajangan</option>
                </select>
            </div>
        </div>

        {{-- 2. SPESIFIKASI & OTOMATISASI KMS BINDING --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Berat (Gram)</label>
                <input type="number" name="weight" value="{{ old('weight', $product->weight) }}" 
                    class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#8B0000]" required>
            </div>
            <div>
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Target Konsumen (Rekomendasi)</label>
                <select name="target_gender" class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#8B0000] font-bold text-stone-600 cursor-pointer" required>
                    <option value="pria" {{ old('target_gender', $product->target_gender) == 'pria' ? 'selected' : '' }}>Pria</option>
                    <option value="wanita" {{ old('target_gender', $product->target_gender) == 'wanita' ? 'selected' : '' }}>Wanita</option>
                    <option value="unisex" {{ old('target_gender', $product->target_gender) == 'unisex' ? 'selected' : '' }}>Unisex (Semua)</option>
                </select>
            </div>
            
            {{-- SEKTOR RETRIEVAL OTOMATIS: KMS AUTO-BINDING REPLACEMENT --}}
            <div>
                <label class="text-[10px] font-black uppercase tracking-widest text-[#8B0000] block mb-2">🔗 KMS Terhubung (Auto)</label>
                <div class="w-full p-4 bg-stone-50 border border-stone-100 rounded-2xl min-h-[56px] flex items-center justify-between">
                    @if($product->knowledge)
                        <div class="flex items-center gap-2 truncate">
                            <span class="inline-block w-2 h-2 rounded-full bg-green-500 animate-pulse shrink-0"></span>
                            <span class="text-xs font-black uppercase tracking-tight text-stone-700 truncate">
                                {{ $product->knowledge->title }}
                            </span>
                        </div>
                        <span class="text-[7px] bg-[#8B0000] text-white px-2 py-0.5 rounded font-black tracking-widest uppercase ml-2 shrink-0">
                            Bound
                        </span>
                    @else
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-2 h-2 rounded-full bg-stone-300 shrink-0"></span>
                            <span class="text-[9px] font-bold text-stone-400 uppercase italic">
                                Belum Terikat Motif
                            </span>
                        </div>
                    @endif
                </div>
                <p class="text-[8px] text-stone-400 font-medium italic mt-1.5 pl-1">
                    *Keterikatan dianalisis otomatis oleh sistem berdasarkan string matching nama produk.
                </p>
            </div>
        </div>

        {{-- 3. VARIASI (Warna & Ukuran) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Pilihan Warna (Pisahkan dengan koma)</label>
                <input type="text" name="available_colors" value="{{ old('available_colors', $product->available_colors ?? '') }}" placeholder="Merah, Hitam, Kuning" 
                    class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#8B0000] font-bold text-stone-700">
            </div>
            <div>
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Pilihan Ukuran (Pisahkan dengan koma)</label>
                <input type="text" name="available_sizes" value="{{ old('available_sizes', $product->available_sizes ?? '') }}" placeholder="S, M, L, XL, All Size" 
                    class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#8B0000] font-bold text-stone-700">
            </div>
        </div>

        {{-- 4. DESKRIPSI --}}
        <div>
            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Deskripsi Produk</label>
            <textarea name="description" rows="4" 
                class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#8B0000] italic text-stone-600 leading-relaxed">{{ old('description', $product->description) }}</textarea>
        </div>

        {{-- 5. MANAJEMEN GAMBAR --}}
        <div class="pt-6 border-t border-gray-100">
            <label class="text-[10px] font-black uppercase tracking-widest text-[#8B0000] block mb-4">Gallery Produk Saat Ini</label>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6" id="image-gallery-grid">
                @foreach($product->images as $img)
                    <div class="relative group aspect-square rounded-[2rem] overflow-hidden border border-gray-100 bg-gray-50 shadow-sm">
                        <img src="{{ asset('storage/' . $img->image) }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                        
                        {{-- Tombol Hapus Satuan --}}
                        <div class="absolute inset-0 bg-red-900/80 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <p class="text-[7px] font-black text-white uppercase tracking-widest mb-2">Hapus Gambar?</p>
                            <a href="{{ route('admin.products.deleteImage', $img->id) }}" 
                               onclick="return confirm('Hapus gambar ini dari gallery?')"
                               class="bg-white text-red-600 p-2 rounded-full shadow-lg hover:scale-110 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach

                {{-- TOMBOL ADD MORE --}}
                <label for="add-more-input" class="border-2 border-dashed border-[#8B0000] rounded-[2rem] flex flex-col items-center justify-center cursor-pointer hover:bg-red-50 transition group aspect-square bg-white shadow-sm">
                    <input type="file" name="images[]" id="add-more-input" multiple class="hidden" onchange="previewImages(this)" accept="image/*">
                    <span class="text-3xl text-[#8B0000] font-light group-hover:scale-125 transition inline-block mb-1">+</span>
                    <p class="text-[8px] font-black uppercase tracking-widest text-[#8B0000]">Add More</p>
                </label>
            </div>
            
            {{-- Preview Gambar Baru --}}
            <div id="new-images-preview" class="grid grid-cols-4 gap-4 mb-6 px-2"></div>

            <p class="text-[9px] italic text-gray-400 font-medium">* Klik tombol silang pada gambar untuk menghapusnya secara permanen.</p>
        </div>

        {{-- 6. BUTTON ACTION --}}
        <div class="pt-6">
            <button type="submit" class="w-full bg-black text-white py-5 rounded-[2rem] font-black uppercase text-xs tracking-[0.2em] hover:bg-[#8B0000] transition-all shadow-xl shadow-black/10 active:scale-95">
                Perbarui Data Produk
            </button>
        </div>
    </form>
</div>

<script>
    function previewImages(input) {
        const previewContainer = document.querySelector('#new-images-preview');
        previewContainer.innerHTML = ''; 
        const maxSize = 5 * 1024 * 1024; // 5MB

        if (input.files) {
            if(input.files.length > 0) {
                previewContainer.innerHTML = '<div class="col-span-4 mb-2"><p class="text-[7px] font-black uppercase text-blue-500 tracking-widest italic">Siap diunggah:</p></div>';
            }

            Array.from(input.files).forEach(file => {
                if (file.size > maxSize) {
                    alert(`File "${file.name}" terlalu besar! Maksimal 5MB.`);
                    input.value = "";
                    previewContainer.innerHTML = "";
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = "aspect-square rounded-[1.5rem] overflow-hidden border-2 border-blue-200 shadow-sm relative";
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover opacity-70">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="bg-blue-500 text-white text-[6px] px-2 py-0.5 rounded-full font-black uppercase">New</span>
                        </div>
                    `;
                    previewContainer.appendChild(div);
                }
                reader.readAsDataURL(file);
            });
        }
    }
</script>

<style>
    input[type="number"]::-webkit-inner-spin-button, 
    input[type="number"]::-webkit-outer-spin-button { 
        -webkit-appearance: none; margin: 0; 
    }
</style>
@endsection