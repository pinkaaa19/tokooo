@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-12">
    <div class="max-w-4xl mx-auto bg-white rounded-[3rem] shadow-sm border border-stone-100 overflow-hidden">
        <div class="bg-stone-900 p-10 text-white flex justify-between items-center">
            <div>
                <p class="text-stone-400 text-xs font-bold uppercase tracking-widest mb-1">Rincian Pesanan</p>
                <h2 class="text-2xl font-black italic uppercase">{{ $order->invoice_number }}</h2>
            </div>
            <div class="text-right">
                <span class="bg-[#8B0000] px-4 py-2 rounded-full text-[10px] font-black uppercase italic">
                    {{ str_replace('_', ' ', $order->status) }}
                </span>
            </div>
        </div>

        <div class="p-10">
            <h3 class="font-black text-stone-800 uppercase italic mb-6 border-b pb-4 text-sm">Produk yang Dibeli</h3>
            <div class="space-y-6 mb-10">
                @foreach($order->items as $item)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-stone-100 rounded-2xl flex items-center justify-center overflow-hidden">
                            @php
                                // Cek apakah produk memiliki relasi images dan ambil yang pertama
                                $displayImage = $item->product->images->first()->image ?? null;
                            @endphp

                            @if($displayImage)
                                {{-- Gunakan asset('storage/' . path) karena di DB sudah ada kata 'products/' --}}
                                <img src="{{ asset('storage/' . $displayImage) }}" 
                                    class="object-cover w-full h-full">
                            @else
                                <div class="bg-stone-200 w-full h-full flex items-center justify-center">
                                    <i class="fa fa-image text-stone-400"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h4 class="font-black text-stone-800 text-sm">{{ $item->product->name }}</h4>
                            <p class="text-stone-400 text-xs font-bold">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <p class="font-black text-stone-800 text-sm">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</p>
                </div>
                @endforeach
            </div>

            <div class="bg-stone-50 p-8 rounded-[2rem] space-y-3">
                <div class="flex justify-between text-xs font-bold uppercase text-stone-500">
                    <span>Total Harga Produk</span>
                    <span class="text-stone-800">Rp {{ number_format($order->total_price_items, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-xs font-bold uppercase text-stone-500">
                    <span>Ongkos Kirim</span>
                    <span class="text-stone-800">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <hr class="border-stone-200 my-2">
                <div class="flex justify-between items-center text-stone-800">
                    <span class="text-sm font-black uppercase italic">Total Pembayaran</span>
                    <span class="text-2xl font-black text-[#8B0000] italic">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="mt-10">
                <h3 class="font-black text-stone-800 uppercase italic mb-4 text-xs">Alamat Pengiriman</h3>
                <p class="text-stone-500 text-sm italic leading-relaxed bg-white border border-stone-100 p-6 rounded-2xl shadow-sm">
                    {{ $order->address_detail }}
                </p>
            </div>

            <div class="mt-10 text-center">
                <a href="{{ route('orders.history') }}" class="text-stone-400 text-xs font-black uppercase tracking-widest hover:text-[#8B0000] transition">
                    <i class="fa fa-arrow-left mr-2"></i> Kembali ke Riwayat
                </a>
            </div>
        </div>
    </div>
</div>
@endsection