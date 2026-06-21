@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen">
    <div class="container mx-auto px-6 md:px-12 py-16">
        <div class="flex items-baseline justify-between border-b border-stone-200 pb-6 mb-10">
            <h1 class="text-3xl font-black uppercase italic tracking-tighter text-stone-900">
                Keranjang <span class="text-[#8B0000]">Belanja</span>
            </h1>
            <span class="text-stone-400 font-bold uppercase text-[10px] tracking-[0.2em]">
                {{ count(session('cart', [])) }} Produk Terpilih
            </span>
        </div>

        @if(session('cart') && count(session('cart')) > 0)
        <form action="{{ route('checkout.index') }}" method="GET">
            <div class="flex flex-col lg:flex-row gap-20">
                
                <div class="flex-1">
                    @foreach(session('cart') as $key => $item)
                        @php $subtotalBaris = $item['price'] * $item['quantity'] @endphp
                        
                        <div class="flex flex-row items-center gap-6 py-10 border-b border-stone-100 last:border-0 group">
                            
                            <div class="flex-shrink-0">
                                <input type="checkbox" name="selected_items[]" value="{{ $key }}" 
                                    class="item-checkbox w-6 h-6 border-2 border-stone-200 rounded cursor-pointer
                                            text-[#8B0000] focus:ring-[#8B0000] focus:ring-offset-0 
                                            accent-[#8B0000]"
                                    data-price="{{ $subtotalBaris }}" checked>
                            </div>

                            <div class="w-24 md:w-32 aspect-[3/4] overflow-hidden rounded-xl bg-stone-50 flex-shrink-0 shadow-sm">
                                <img src="{{ asset('storage/'.$item['image']) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </div>

                            <div class="flex flex-col flex-1 min-h-[140px] justify-between">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-black uppercase text-base tracking-tight text-stone-900 leading-tight">
                                            {{ $item['name'] }}
                                        </h3>
                                        <p class="text-[9px] text-stone-400 font-black uppercase tracking-widest mt-2">
                                            Warna: {{ $item['color'] }} / Ukuran: {{ $item['size'] }}
                                        </p>
                                    </div>

                                    <button type="button" onclick="hapusItem('{{ $key }}')" class="text-stone-300 hover:text-red-600 transition-all p-1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>

                                <div class="flex justify-between items-end mt-4">
                                    <div class="text-[10px] font-bold text-stone-400 uppercase tracking-widest">
                                        {{ $item['quantity'] }} x Rp {{ number_format($item['price'], 0, ',', '.') }}
                                    </div>
                                    
                                    <div class="text-right">
                                        <p class="text-[9px] font-black text-stone-300 uppercase tracking-widest mb-1">Subtotal</p>
                                        <p class="font-black text-xl text-stone-900 tracking-tight">
                                            Rp {{ number_format($subtotalBaris, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="w-full lg:w-[400px]">
                    <div class="bg-stone-50 p-10 rounded-[2.5rem] sticky top-12 border border-stone-100 shadow-sm">
                        <h2 class="text-[11px] font-black uppercase tracking-[0.3em] text-stone-900 mb-8 pb-4 border-b border-stone-200">Ringkasan Pesanan</h2>
                        
                        <div class="space-y-6 mb-10">
                            <div class="flex justify-between text-[10px] font-black uppercase tracking-widest text-stone-400">
                                <span>Produk Dipilih</span>
                                <span id="jumlah-terpilih" class="text-stone-900">0 Produk</span>
                            </div>
                            
                            <div class="border-t border-stone-200 pt-8 flex justify-between items-center">
                                <span class="font-black uppercase text-xs tracking-[0.2em] text-stone-900">Total Harga</span>
                                <span id="total-akhir" class="font-black text-3xl text-[#8B0000] tracking-tighter">
                                    Rp 0
                                </span>
                            </div>
                        </div>

                        <button type="submit" id="btn-checkout"
                                class="flex items-center justify-center w-full bg-black text-white py-6 rounded-2xl font-black uppercase text-[10px] tracking-[0.3em] hover:bg-[#8B0000] transition-all disabled:bg-stone-200 disabled:cursor-not-allowed shadow-xl active:scale-95">
                            Lanjut ke Pembayaran
                        </button>
                        
                        <p class="mt-6 text-[8px] text-center text-stone-400 italic leading-relaxed uppercase tracking-widest">
                            *Ongkos kirim akan dihitung pada tahap berikutnya
                        </p>
                    </div>
                    
                    <a href="/" class="block mt-8 text-center font-black uppercase text-[9px] tracking-[0.3em] text-stone-400 hover:text-black transition-colors">
                        ← Kembali Belanja
                    </a>
                </div>
            </div>
        </form>

        <form id="form-hapus" method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>

        @else
        <div class="text-center py-40 bg-stone-50 rounded-[4rem] border-2 border-dashed border-stone-100">
            <h2 class="text-xl font-black uppercase italic tracking-tighter text-stone-300 mb-6">Keranjang Belanja Anda Kosong</h2>
            <a href="/" class="px-10 py-4 bg-black text-white rounded-xl font-black uppercase text-[10px] tracking-[0.3em] hover:bg-[#8B0000] transition-all">Lihat Koleksi Produk</a>
        </div>
        @endif
    </div>
</div>

<script>
    const checkboxes = document.querySelectorAll('.item-checkbox');
    const totalAkhirElement = document.getElementById('total-akhir');
    const jumlahTerpilihElement = document.getElementById('jumlah-terpilih');
    const btnCheckout = document.getElementById('btn-checkout');

    function hitungTotal() {
        let total = 0;
        let jumlah = 0;

        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                total += parseInt(checkbox.getAttribute('data-price'));
                jumlah++;
            }
        });

        // Update teks harga dan jumlah
        totalAkhirElement.innerText = 'Rp ' + total.toLocaleString('id-ID');
        jumlahTerpilihElement.innerText = jumlah + ' Produk Dipilih';
        
        // Matikan tombol jika tidak ada produk terpilih
        btnCheckout.disabled = jumlah === 0;
    }

    // Pasang listener pada setiap checkbox
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', hitungTotal);
    });

    // Jalankan hitungan saat halaman pertama kali dibuka
    hitungTotal();

    // Fungsi untuk memicu form hapus
    function hapusItem(key) {
        if(confirm('Hapus produk ini dari tas belanja?')) {
            const form = document.getElementById('form-hapus');
            form.action = '/cart/remove/' + key;
            form.submit();
        }
    }
</script>
@endsection