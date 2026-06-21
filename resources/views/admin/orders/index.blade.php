@extends('layouts.admin')

@section('admin_content')
<div class="mb-10">
    <h2 class="text-4xl font-black text-gray-900 uppercase italic">Daftar <span class="text-[#8B0000]">Pesanan</span></h2>
</div>

<div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 text-[10px] uppercase font-black tracking-widest text-gray-400">
            <tr>
                <th class="px-8 py-5">Pembeli</th>
                <th class="px-8 py-5">Total Bayar</th>
                <th class="px-8 py-5">Status</th>
                <th class="px-8 py-5">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($orders as $order)
            <tr>
                <td class="px-8 py-5 font-bold">{{ $order->user->name }}</td>
                <td class="px-8 py-5">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                <td class="px-8 py-5">
                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase italic 
                        {{ $order->status == 'success' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ str_replace('_', ' ', $order->status) }}
                    </span>
                </td>
                <td class="px-8 py-5 text-center">
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="bg-black text-white px-4 py-2 rounded-xl text-[10px] font-bold uppercase hover:bg-[#8B0000] transition">
                        Detail & Verifikasi
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection