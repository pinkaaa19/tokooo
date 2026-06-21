@extends('layouts.admin')

@section('admin_content')
<div class="mb-10">
    <h2 class="text-4xl font-black text-gray-900 uppercase italic tracking-tighter">Ringkasan Laporan</h2>
    <p class="text-gray-500">Pantau performa toko dan progres digitalisasi budaya Toraja.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 transition hover:shadow-xl">
        <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-3">Total Pendapatan</p>
        <h3 class="text-3xl font-black text-[#8B0000]">
            Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
        </h3>
    </div>

    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
        <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-3">Produk Terjual</p>
        <h3 class="text-3xl font-black text-gray-900">
            {{ $produkTerjual }} Item
        </h3>
    </div>


</div>

<div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-50 flex justify-between items-center">
        <h4 class="font-black uppercase italic text-gray-800">Pesanan Terbaru</h4>
        <a href="{{ route('admin.orders.index') }}" class="text-[10px] font-black uppercase text-[#8B0000] hover:underline transition">
        Lihat Semua
    </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-[10px] uppercase font-black tracking-widest text-gray-400">
                <tr>
                    <th class="px-8 py-5">Pembeli</th>
                    <th class="px-8 py-5">Total Bayar</th>
                    <th class="px-8 py-5">Status</th>
                    <th class="px-8 py-5">Waktu</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-50">
                @forelse($pesananTerbaru as $order)
                <tr>
                    <td class="px-8 py-5 font-semibold text-gray-900">
                        {{ $order->user->name ?? 'Guest' }}
                    </td>
                    <td class="px-8 py-5 font-bold text-gray-700">
                        Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                    </td>
                    <td class="px-8 py-5">
                        @if($order->status == 'success' || $order->status == 'paid')
                            <span class="bg-green-100 text-green-700 px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest italic">
                                Selesai
                            </span>
                        @else
                            <span class="bg-yellow-100 text-yellow-700 px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest italic">
                                {{ str_replace('_', ' ', $order->status) }}
                            </span>
                        @endif
                    </td>
                    <td class="px-8 py-5 text-gray-400 text-xs">
                        {{ $order->created_at->diffForHumans() }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-8 py-10 text-center text-gray-400 italic">
                        Belum ada transaksi masuk hari ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection