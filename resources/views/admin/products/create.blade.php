@extends('layouts.admin')

@section('admin_content')
<div class="mb-10">
    <h2 class="text-3xl font-black text-gray-900 uppercase italic tracking-tighter">Tambah <span class="text-[#8B0000]">Produk Baru</span></h2>
    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Lengkapi data produk dan target konsumen</p>
</div>

{{-- Menampilkan Error Validasi --}}
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
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" id="productForm">
        @csrf

        {{-- Row 1: Nama, Harga & Kategori --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-1">
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Nama Produk</label>
                <input type="text" name="name" id="product_name" value="{{ old('name') }}" placeholder="Kemeja Tenun Pa'tedong" 
                    class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#8B0000] font-bold text-stone-700" required>
            </div>
            <div>
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Harga Jual (Rp)</label>
                <input type="number" name="price" value="{{ old('price') }}" placeholder="0" 
                    class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#8B0000] font-black text-[#8B0000]" required>
            </div>
            <div>
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Kategori Produk</label>
                <select name="category" class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#8B0000] font-bold text-stone-600 cursor-pointer" required>
                    <option value="" disabled selected>-- Pilih Kategori --</option>
                    <option value="Pakaian" {{ old('category') == 'Pakaian' ? 'selected' : '' }}>Pakaian</option>
                    <option value="Kain" {{ old('category') == 'Kain' ? 'selected' : '' }}>Kain</option>
                    <option value="Tas" {{ old('category') == 'Tas' ? 'selected' : '' }}>Tas</option>
                    <option value="Aksesoris" {{ old('category') == 'Aksesoris' ? 'selected' : '' }}>Aksesoris</option>
                    <option value="Ukiran & Pajangan" {{ old('category') == 'Ukiran & Pajangan' ? 'selected' : '' }}>Ukiran & Pajangan</option>
                </select>
            </div>
        </div>

        {{-- Row 2: Berat, Target Gender & KMS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Berat (Gram)</label>
                <input type="number" name="weight" value="{{ old('weight') }}" placeholder="500" 
                    class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#8B0000]" required>
            </div>
            <div>
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Target Konsumen</label>
                <select name="target_gender" class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#8B0000] font-bold text-stone-600 cursor-pointer" required>
                    <option value="" disabled selected>-- Pilih Target --</option>
                    <option value="pria" {{ old('target_gender') == 'pria' ? 'selected' : '' }}>Pria</option>
                    <option value="wanita" {{ old('target_gender') == 'wanita' ? 'selected' : '' }}>Wanita</option>
                    <option value="unisex" {{ old('target_gender') == 'unisex' ? 'selected' : '' }}>Unisex (Semua)</option>
                </select>
            </div>

        </div>

        {{-- Row 3: Warna & Ukuran --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Warna (Pisahkan koma)</label>
                <input type="text" name="available_colors" value="{{ old('available_colors') }}" placeholder="Merah, Hitam, Kuning" 
                    class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#8B0000] font-bold text-stone-700">
            </div>
            <div>
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Ukuran (Pisahkan koma)</label>
                <input type="text" name="available_sizes" value="{{ old('available_sizes') }}" placeholder="S, M, L, XL" 
                    class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#8B0000] font-bold text-stone-700">
            </div>
        </div>

        {{-- Deskripsi --}}
        <div>
            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Deskripsi Produk</label>
            <textarea name="description" rows="4" placeholder="Ceritakan detail produk..." 
                class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#8B0000] italic text-stone-600">{{ old('description') }}</textarea>
        </div>

        {{-- Gallery Upload --}}
        <div>
            <label class="text-[10px] font-black uppercase tracking-widest text-[#8B0000] block mb-4">Gallery Produk (Max 5MB)</label>
            <div class="border-2 border-dashed border-gray-200 p-10 rounded-[2rem] text-center bg-gray-50 hover:border-[#8B0000] transition group">
                <input type="file" name="images[]" id="images" multiple class="hidden" required onchange="previewImages()" accept="image/*">
                <label for="images" class="cursor-pointer">
                    <span class="text-4xl block mb-2 text-gray-300 group-hover:scale-110 transition inline-block">📸</span>
                    <p class="text-[9px] font-black uppercase tracking-[0.2em] text-gray-400 group-hover:text-black transition">Klik untuk upload gambar</p>
                </label>
                <div id="image-preview" class="grid grid-cols-4 gap-4 mt-6"></div>
            </div>
        </div>

        <button type="submit" class="w-full bg-black text-white py-5 rounded-[2rem] font-black uppercase text-xs tracking-[0.2em] hover:bg-[#8B0000] transition shadow-xl">
            Simpan Produk Baru
        </button>
    </form>
</div>

<script>
    function previewImages() {
        const preview = document.querySelector('#image-preview');
        preview.innerHTML = '';
        const files = document.querySelector('#images').files;
        if (files) {
            [].forEach.call(files, (file) => {
                const reader = new FileReader();
                reader.onload = function() {
                    const div = document.createElement('div');
                    div.className = "aspect-square rounded-2xl overflow-hidden border border-gray-100 shadow-sm";
                    div.innerHTML = `<img src="${this.result}" class="w-full h-full object-cover">`;
                    preview.appendChild(div);
                }
                reader.readAsDataURL(file);
            });
        }
    }
</script>
@endsection