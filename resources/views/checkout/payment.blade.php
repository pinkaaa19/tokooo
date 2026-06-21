@extends('layouts.app')

@section('title', 'Pembayaran - Aldy Art')

@section('content')
<div class="container mx-auto px-6 py-12">
    <div class="max-w-2xl mx-auto bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-stone-100">
        <div class="bg-stone-900 p-8 text-center">
            <h2 class="text-2xl font-black text-white uppercase italic tracking-widest">Selesaikan Pembayaran</h2>
            <p class="text-stone-400 text-sm mt-2">Segera lakukan transfer agar pesananmu segera diproses.</p>
        </div>

        <div class="p-8 lg:p-12">
            <div class="flex justify-between items-center mb-8 pb-6 border-b border-stone-100">
                <div>
                    <p class="text-stone-400 text-xs font-bold uppercase tracking-widest mb-1">Nomor Invoice</p>
                    <h3 class="text-xl font-black text-stone-800">{{ $order->invoice_number }}</h3>
                </div>
                <div class="text-right">
                    <p class="text-stone-400 text-xs font-bold uppercase tracking-widest mb-1">Total Bayar</p>
                    <h3 class="text-3xl font-black text-[#8B0000] italic">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</h3>
                </div>
            </div>

        <div class="bg-stone-50 rounded-3xl p-8 mb-10 border border-stone-100">
    <div class="flex items-center justify-center mb-8">
        <div class="text-center">
            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-sm mx-auto mb-4">
                <i class="fa fa-qrcode text-3xl text-[#8B0000]"></i>
            </div>
            <h4 class="font-black text-stone-800 uppercase italic">Pembayaran via QRIS</h4>
            <p class="text-stone-500 text-xs mt-1">Scan kode QR di bawah ini melalui aplikasi Bank atau E-Wallet.</p>
        </div>
    </div>

    {{-- Container Gambar QRIS --}}
    <div class="bg-white p-6 rounded-[2rem] shadow-inner border border-stone-100 flex flex-col items-center">
        {{-- Ganti 'qris-sample.png' dengan file QRIS aslimu di folder public/images --}}
        <img src="{{ asset('images/aldyart.jpeg') }}" 
             alt="QRIS Aldy Art" 
             class="w-full h-auto object-contain rounded-2xl">
        
        <div class="text-center border-t border-stone-100 pt-4 w-full">
            <span class="text-stone-400 text-[10px] font-bold uppercase tracking-widest block mb-1">Merchant Name</span>
            <span class="text-lg font-black text-stone-800 tracking-wider italic uppercase">ALDYART TLLNGLP</span>
        </div>
    </div>
</div>

            <form action="{{ route('order.upload', $order->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-8 text-center">
                    <label class="block text-stone-800 font-black uppercase text-sm mb-4 italic">
                        Unggah Bukti Transfer <span class="text-[#8B0000] text-xs">(JPG/PNG)</span>
                    </label>
                    
                    <div class="relative">
                        <input type="file" name="payment_proof" required
                            class="w-full text-sm text-stone-500 file:mr-4 file:py-3 file:px-6 file:rounded-2xl file:border-0 file:text-xs file:font-black file:uppercase file:bg-stone-900 file:text-white hover:file:bg-[#8B0000] cursor-pointer transition-all" />
                    </div>
                </div>

                <button type="submit" 
                    class="w-full bg-[#8B0000] text-white py-5 rounded-2xl font-black uppercase tracking-widest shadow-xl hover:bg-stone-900 transition-all transform hover:-translate-y-1">
                    Konfirmasi Pembayaran
                </button>
            </form>

            <p class="text-center text-stone-400 text-[10px] mt-8 uppercase font-bold tracking-tighter">
                *Pesanan akan otomatis dibatalkan jika bukti tidak diunggah dalam 1x24 jam.
            </p>
        </div>
    </div>
</div>
@endsection