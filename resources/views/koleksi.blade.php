@extends('layouts.app')

@section('content')

<div class="container mx-auto px-6 py-12">

<h1 class="text-3xl font-bold mb-10">
Koleksi Produk Toraja
</h1>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

@foreach($products as $product)

<a href="{{ route('product.show', $product->id) }}" 
   class="bg-white rounded-2xl shadow hover:shadow-xl transition p-4 block">

    <!-- Gambar Produk -->
    <div class="rounded-xl overflow-hidden mb-4 aspect-[3/4] bg-gray-100">
    @if($product->images->isNotEmpty())
        @php
            $dbPath = $product->images->first()->image;
            // Cek apakah di database sudah ada kata 'products/'. 
            // Jika sudah ada, jangan tambahkan lagi agar tidak dobel.
            $finalPath = str_contains($dbPath, 'products/') ? $dbPath : 'products/' . $dbPath;
        @endphp

        <img src="{{ asset('storage/' . $finalPath) }}"
             class="w-full h-full object-cover transition duration-500 hover:scale-110"
             alt="{{ $product->name }}"
             onerror="this.src='https://placehold.co/600x800?text=Gambar+Tidak+Tersedia'">
    @else
        <div class="w-full h-full flex items-center justify-center text-gray-400 text-[10px] font-black uppercase italic">
            No Image
        </div>
    @endif
</div>

    <!-- Nama Produk -->
    <h2 class="text-lg font-bold text-stone-800">
        {{ $product->name }}
    </h2>

    <!-- Harga -->
    <p class="text-[#8B0000] font-bold text-lg mt-1">
        Rp {{ number_format($product->price) }}
    </p>

    <!-- Tombol -->
    <button 
    class="mt-4 w-full bg-[#8B0000] text-white py-3 rounded-xl font-semibold hover:bg-black transition">
        Lihat Produk
    </button>

</a>

@endforeach

</div>

</div>

@endsection