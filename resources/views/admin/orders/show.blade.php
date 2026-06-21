@extends('layouts.admin')

@section('admin_content')
<div class="mb-10 flex justify-between items-center">
    <h2 class="text-3xl font-black text-gray-900 uppercase italic">Pesanan </h2>
    <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-gray-400 underline">KEMBALI KE DAFTAR</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
            <h3 class="font-black text-[#8B0000] text-xs uppercase mb-6 tracking-widest">Tujuan Pengiriman</h3>
            <div class="grid grid-cols-2 gap-4">
                <p class="text-[10px] uppercase text-gray-400 font-black col-span-2">Alamat Lengkap</p>
                <p class="font-bold text-lg mb-4 col-span-2">{{ $order->address_detail ?? '-' }}</p>
                <div>
                    <p class="text-[10px] uppercase text-gray-400 font-black">Nomor HP</p>
                    <p class="font-bold">{{ $order->user->phone_number ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
            <h3 class="font-black text-[#8B0000] text-xs uppercase mb-6 tracking-widest">Produk Pesanan</h3>
            <table class="w-full text-left">
                <tbody class="divide-y divide-gray-50">
                    @foreach($order->items as $item)
                    <tr>
<td class="py-4 w-20">
    <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 border">
        @php
            $firstImage = $item->product->images->first();
        @endphp

        @if($firstImage)
            {{-- Tambahkan 'products/' sebelum nama file --}}
            <img src="{{ asset('storage/' . $firstImage->image) }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center text-[10px] text-gray-400 font-bold">
                NO IMG
            </div>
        @endif
    </div>
</td>

                        <td class="py-4 font-bold">{{ $item->product->name }}</td>
                        <td class="py-4 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    <tr class="font-black text-xl text-[#8B0000]">
                        <td class="pt-6">GRAND TOTAL</td>
                        <td class="pt-6 text-right font-black italic">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-black text-white p-8 rounded-[2.5rem] shadow-xl">
            <h3 class="font-black text-xs uppercase mb-6 tracking-widest">Verifikasi & Status</h3>
            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                @csrf @method('PATCH')
                <select name="status" class="w-full bg-white/10 p-4 rounded-xl text-sm mb-4 border-none text-white focus:ring-2 focus:ring-[#8B0000]">
                    <option class="text-black" value="waiting_confirmation" {{ $order->status == 'waiting_confirmation' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option class="text-black" value="dikemas" {{ $order->status == 'dikemas' ? 'selected' : '' }}>Pesanan Dikemas</option>
                    <option class="text-black" value="dikirim" {{ $order->status == 'dikirim' ? 'selected' : '' }}>Pesanan Dikirim</option>
                    <option class="text-black" value="success" {{ $order->status == 'success' ? 'selected' : '' }}>Selesai</option>
                    <option class="text-black" value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                <button type="submit" class="w-full bg-[#8B0000] py-4 rounded-xl font-black uppercase text-[10px] tracking-widest">Update Progres</button>
            </form>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
            <h3 class="font-black text-[#8B0000] text-xs uppercase mb-6 tracking-widest">Lampiran Bukti</h3>
            @if($order->payment_proof)
                <img src="{{ asset('storage/' . $order->payment_proof) }}" class="w-full rounded-2xl">
            @else
                <p class="text-gray-400 italic text-xs">User belum mengunggah bukti.</p>
            @endif
        </div>
    </div>
</div>
@endsection