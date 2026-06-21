@extends('layouts.admin')

@section('admin_content')
<div class="mb-10 flex justify-between items-end">
    <div>
        <h2 class="text-4xl font-black text-gray-900 uppercase italic tracking-tighter">
            Data <span class="text-[#8B0000]">Produk</span>
        </h2>
        {{-- Menampilkan jumlah total produk --}}
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mt-1">
            Total Koleksi: <span class="text-[#8B0000]">{{ $products->count() }}</span> Item
        </p>
    </div>
    
    <a href="{{ route('admin.products.create') }}" class="bg-black text-white px-8 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-[#8B0000] transition shadow-lg shadow-black/10">
        + Tambah Produk Baru
    </a>
</div>

{{-- NOTIFIKASI SUKSES --}}
@if(session('success'))
<div class="mb-8 bg-green-50 p-4 rounded-2xl border border-green-100 text-green-600 text-[10px] font-black uppercase tracking-widest italic">
    {{ session('success') }}
</div>
@endif

{{-- STATUS MAPPING KMS --}}
@if(isset($knowledges))
<div class="mb-8 bg-red-50 p-6 rounded-[2rem] border border-red-100 shadow-inner">
    <p class="text-[10px] font-black uppercase text-[#8B0000] mb-2 ml-2">Status Mapping KMS Aktif</p>
    <p class="text-xs text-gray-500 ml-2 italic">Sistem siap menghubungkan {{ $knowledges->count() }} konten budaya ke produk Anda.</p>
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    @foreach($products as $product)
    <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm border border-gray-100 group">
        
        <div class="aspect-square relative overflow-hidden bg-gray-100">
            @if($product->images->isNotEmpty())
                @php
                    $path = $product->images->first()->image;
                    // Cek apakah di database sudah ada kata 'products/' atau belum
                    $finalPath = str_contains($path, 'products/') ? $path : 'products/' . $path;
                @endphp
                
                {{-- PERBAIKAN UTAMA: Menggunakan asset('storage/' . $finalPath) --}}
                <img src="{{ asset('storage/' . $finalPath) }}" 
                     class="w-full h-full object-cover transition duration-500 group-hover:scale-110"
                     alt="{{ $product->name }}"
                     onerror="this.src='https://placehold.co/500x500?text=File+Tidak+Ditemukan'">
            @else
                <div class="w-full h-full flex items-center justify-center text-gray-400 font-black text-[10px] italic uppercase">
                    No Image
                </div>
            @endif
            
            {{-- Badge Mapping KMS --}}
            @if($product->knowledge)
                <div class="absolute top-4 left-4 bg-[#8B0000] text-white px-4 py-2 rounded-full text-[8px] font-black uppercase tracking-widest shadow-lg">
                    📌 Terhubung: {{ $product->knowledge->title }}
                </div>
            @endif
        </div>

        <div class="p-8">
            <h3 class="font-black uppercase italic text-lg text-gray-800 leading-tight mb-2">
                {{ $product->name }}
            </h3>
            <p class="text-[#8B0000] font-black text-xl mb-4">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </p>
            
            <div class="flex justify-between items-center border-t pt-6">
                <span class="text-[9px] font-black uppercase text-gray-400 tracking-widest">
                    {{ $product->category ?? 'Tanpa Kategori' }}
                </span>
                
                <div class="flex gap-4 items-center">
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="text-[10px] font-bold text-gray-400 hover:text-black uppercase">Edit</a>
                    
                    {{-- TOMBOL HAPUS DENGAN FORM --}}
<form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
    @csrf
    @method('DELETE')
    <button type="submit" class="text-red-600">Hapus</button>
</form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection