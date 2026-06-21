@extends('layouts.app')

@section('title', 'Pesanan Saya - Aldy Art')

@section('content')
<div class="bg-gray-50 min-h-screen">
    {{-- TAB NAVIGATION (Gaya Shopee) --}}
    <div class="bg-white shadow-sm sticky top-0 z-30">
        <div class="container mx-auto px-4">
            <div class="flex overflow-x-auto no-scrollbar items-center justify-between text-[11px] font-black uppercase tracking-tighter">
                @php
                    // Daftar tab status
                    $status_tabs = [
                        'all' => 'SEMUA', 
                        'pending' => 'BELUM BAYAR', 
                        'dikemas' => 'DIKEMAS', 
                        'dikirim' => 'DIKIRIM', 
                        'success' => 'SELESAI', 
                        'cancelled' => 'DIBATALKAN'
                    ];
                    $current_status = request('status', 'all');
                @endphp

                @foreach($status_tabs as $key => $label)
                    <a href="{{ route('orders.history', ['status' => $key]) }}" 
                       class="px-6 py-5 border-b-4 transition-all whitespace-nowrap {{ $current_status == $key ? 'border-[#8B0000] text-[#8B0000]' : 'border-transparent text-gray-400 hover:text-stone-800' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>



        @if($orders->isEmpty())
            {{-- TAMPILAN JIKA KOSONG --}}
            <div class="bg-white p-20 rounded-sm shadow-sm text-center border border-stone-100">
                <div class="w-16 h-16 bg-stone-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa fa-file-invoice text-stone-200 text-2xl"></i>
                </div>
                <p class="text-xs font-bold text-stone-400 uppercase tracking-widest">Belum ada pesanan</p>
                <a href="/" class="inline-block mt-6 bg-[#8B0000] text-white px-8 py-3 rounded-sm font-black text-[10px] uppercase tracking-widest">Belanja Sekarang</a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($orders as $order)
                <div class="bg-white shadow-sm rounded-sm overflow-hidden border border-stone-100">
                    {{-- HEADER: IDENTITAS TOKO & STATUS --}}
                    <div class="px-5 py-3 flex justify-between items-center border-b border-stone-50">
                        <div class="flex items-center gap-2">
                            <span class="bg-[#8B0000] text-white text-[9px] font-black px-1.5 py-0.5 rounded-sm uppercase tracking-tighter">Mall</span>
                            <span class="text-[11px] font-black text-stone-800 uppercase tracking-tight">Aldy Art Budaya Toraja</span>
                        </div>
                        <div class="text-[#8B0000] text-[11px] font-black uppercase italic tracking-tighter">
                            @switch($order->status)
                                @case('pending') Belum Bayar @break
                                @case('dikemas') Sedang Dikemas @break
                                @case('dikirim') Sedang Dikirim @break
                                @case('success') Selesai @break
                                @case('cancelled') Dibatalkan @break
                                @default {{ ucfirst($order->status) }}
                            @endswitch
                        </div>
                    </div>

                    {{-- BODY: INFO PRODUK (DINAMIS) --}}
                    <div class="p-5 flex gap-5">
                        <div class="w-20 h-20 bg-stone-50 rounded-sm shrink-0 overflow-hidden border border-stone-100">
                            {{-- Mengambil gambar dari produk pertama di order_items --}}
                            @if($order->items->isNotEmpty() && $order->items->first()->product->images->isNotEmpty())
                                <img src="{{ asset('storage/' . $order->items->first()->product->images->first()->image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-stone-50"><i class="fa fa-image text-stone-200"></i></div>
                            @endif
                        </div>
                        <div class="flex-1">
                            {{-- JUDUL: NOMOR ORDER & NAMA PRODUK --}}
                            <h4 class="text-sm font-bold text-stone-800 leading-tight">
                                Order #{{ $order->invoice_number }} - 
                                @if($order->items->isNotEmpty())
                                    {{ $order->items->first()->product->name }}
                                    @if($order->items->count() > 1)
                                        <span class="text-gray-400 font-medium"> (dan {{ $order->items->count() - 1 }} produk lainnya)</span>
                                    @endif
                                @else
                                    Produk Budaya Toraja
                                @endif
                            </h4>
                            <p class="text-[10px] text-stone-400 font-bold uppercase mt-2">Variasi: Original Handmade</p>
                            <p class="text-xs text-stone-800 font-bold mt-1 tracking-tighter">x{{ $order->items->sum('quantity') }}</p>
                        </div>
                        <div class="text-right flex flex-col justify-center">
                            <span class="text-sm text-[#8B0000] font-black italic">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- FOOTER: TOTAL & AKSI --}}
                    <div class="p-5 border-t border-stone-50 bg-stone-50/20">
                        <div class="flex justify-end items-center gap-3 mb-5">
                            <span class="text-[10px] font-bold text-stone-400 uppercase tracking-widest text-right">Total Pesanan:</span>
                            <span class="text-lg text-[#8B0000] font-black italic">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                            <div class="text-[9px] font-bold text-stone-300 uppercase tracking-widest italic">
                                Transaksi pada {{ $order->created_at->format('d M Y, H:i') }}
                            </div>
                            <div class="flex gap-2 w-full md:w-auto">
                                <a href="{{ route('orders.show', $order->id) }}" 
                                   class="flex-1 md:flex-none text-center border-2 border-stone-100 text-stone-600 px-6 py-2.5 rounded-sm text-[10px] font-black uppercase tracking-widest hover:bg-stone-50 transition-all">
                                    Tampilkan Rincian
                                </a>
                                @if($order->status == 'pending')
                                    <a href="{{ route('order.payment', $order->id) }}" 
                                       class="flex-1 md:flex-none text-center bg-[#8B0000] text-white px-6 py-2.5 rounded-sm text-[10px] font-black uppercase tracking-widest shadow-sm hover:bg-stone-900 transition-all">
                                        Bayar Sekarang
                                    </a>
                                @else
                                    <a href="/" 
                                       class="flex-1 md:flex-none text-center bg-stone-900 text-white px-6 py-2.5 rounded-sm text-[10px] font-black uppercase tracking-widest shadow-sm hover:bg-[#8B0000] transition-all">
                                        Beli Lagi
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection