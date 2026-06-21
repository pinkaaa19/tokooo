@extends('layouts.admin')

@section('admin_content')
<div class="p-4 space-y-8">
    <div class="flex justify-between items-end">
        <div>
            <h2 class="text-4xl font-black uppercase italic tracking-tighter text-stone-900 leading-none">
                Weekly <span class="text-[#8B0000]">Financial Report</span>
            </h2>
            <div class="h-1.5 w-20 bg-[#8B0000] mt-4"></div>
        </div>
        <button onclick="window.print()" class="bg-stone-900 text-white px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-[#8B0000] transition-all shadow-lg">
            Cetak PDF
        </button>
    </div>

    {{-- Info Status --}}
    <div class="flex gap-4">
        <div class="bg-green-50 border border-green-100 px-4 py-2 rounded-xl flex items-center gap-3">
            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
            <span class="text-[9px] font-black text-green-700 uppercase tracking-widest">Status Terhitung: Selesai, Dikirim, Success</span>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-stone-200/50 border border-stone-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-stone-50 border-b border-stone-100">
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-stone-400">Periode</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-stone-400 text-center">Volume Pesanan</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-stone-400 text-right">Omzet (Grand Total)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-50">
                @forelse($laporanMingguan as $data)
                <tr class="hover:bg-stone-50/50 transition-all">
                    <td class="px-8 py-6">
                        <span class="block font-black text-stone-800 italic uppercase text-base">Minggu Ke-{{ $data->minggu }}</span>
                        <span class="text-[10px] text-stone-400 font-bold uppercase tracking-tighter mt-1">
                            {{ \Carbon\Carbon::parse($data->tanggal_mulai)->startOfWeek()->format('d M') }} - 
                            {{ \Carbon\Carbon::parse($data->tanggal_mulai)->endOfWeek()->format('d M Y') }}
                        </span>
                    </td>
                    <td class="px-8 py-6 text-center">
                        <span class="inline-block bg-stone-100 px-5 py-2 rounded-full text-[10px] font-black text-stone-600">
                            {{ $data->total_transaksi }} Transaksi
                        </span>
                    </td>
                    <td class="px-8 py-6 text-right">
                        <span class="text-xl font-black text-[#8B0000] italic tracking-tighter">
                            Rp {{ number_format($data->total_pendapatan, 0, ',', '.') }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-8 py-24 text-center">
                        <div class="opacity-20 flex flex-col items-center">
                            <span class="text-6xl mb-4">📜</span>
                            <p class="font-black uppercase italic tracking-[0.2em] text-xs">Belum ada pesanan dengan status 'Selesai' minggu ini</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection