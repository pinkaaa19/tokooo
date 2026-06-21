@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-20 text-center">
    <div class="max-w-md mx-auto bg-white p-10 rounded-[3rem] shadow-xl border border-stone-100">
        <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fa fa-check text-3xl"></i>
        </div>
        <h2 class="text-2xl font-black text-stone-800 uppercase italic mb-2">Terima Kasih!</h2>
        <p class="text-stone-500 text-sm mb-8">Bukti pembayaran telah kami terima. Admin Aldy Art akan segera memverifikasi pesanan Anda.</p>
        
        <a href="/" class="inline-block bg-stone-900 text-white px-10 py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-[#8B0000] transition-all">
            Kembali Berbelanja
        </a>
    </div>
</div>
@endsection