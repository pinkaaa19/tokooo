<div class="grid grid-cols-1 md:grid-cols-4 gap-8">
    @foreach($products as $product)
    <div class="bg-white rounded-xl shadow p-4 hover:shadow-lg transition group">
        <a href="{{ route('product.show', $product->id) }}">
            <div class="w-full h-56 bg-gray-100 rounded overflow-hidden">
                @if($product->images->isNotEmpty())
                    {{-- PERBAIKAN: Gunakan asset('storage/products/...') --}}
                    <img src="{{ asset('storage/products/' . $product->images->first()->image) }}" 
                         class="w-full h-full object-cover transition duration-300 group-hover:scale-110"
                         alt="{{ $product->name }}">
                @else
                    {{-- Placeholder jika gambar tidak ada --}}
                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs font-bold uppercase italic">
                        No Image
                    </div>
                @endif
            </div>

            <h3 class="mt-3 font-semibold text-lg text-gray-800">
                {{ $product->name }}
            </h3>

            <p class="text-[#8B0000] font-bold">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </p>
        </a>

        <div class="flex gap-2 items-center mt-4">
            <button class="flex-1 bg-[#8B0000] text-white px-4 py-2 rounded-lg text-xs font-black uppercase tracking-widest hover:bg-black transition">
                Tambah ke Keranjang
            </button>
            <button class="bg-gray-100 p-2 rounded-lg hover:bg-gray-200 transition">
                🛒
            </button>
        </div>
    </div>
    @endforeach
</div>