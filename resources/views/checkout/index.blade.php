@extends('layouts.app')

@section('title', 'Checkout - Aldy Art')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="container mx-auto px-6 py-10">

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-2xl mb-6 shadow-sm max-w-7xl mx-auto">
            <strong class="font-bold text-sm block mb-1">Eror Transaksi:</strong>
            <p class="text-sm">{{ session('error') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-2xl mb-6 shadow-sm max-w-7xl mx-auto">
            <strong class="font-bold uppercase text-xs tracking-wider block mb-1">Gagal Memproses Checkout:</strong>
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="checkoutForm" method="POST" action="{{ route('checkout.process') }}" onsubmit="return validasiSebelumKirim()">
        @csrf
        <input type="hidden" id="totalWeight" value="{{ $totalWeight }}">
        <input type="hidden" id="subtotal" value="{{ $totalHarga }}">

        <input type="hidden" id="hid_shipping" name="shipping_cost">
        <input type="hidden" id="hid_total" name="grand_total">
        
        <input type="hidden" id="latitude">
        <input type="hidden" id="longitude">

        @if(isset($checkoutItems) && count($checkoutItems) > 0)
            @foreach($checkoutItems as $item)
                <input type="hidden" name="product_ids[]" value="{{ $item['id'] }}">
            @endforeach
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-stone-100">
                <h2 class="text-2xl font-black text-stone-800 uppercase italic mb-6">
                    Informasi <span class="text-[#8B0000]">Pengiriman</span>
                </h2>

                <div class="form-group mb-3">
                    <label class="font-bold text-stone-800 block mb-2 text-sm uppercase tracking-wide">Alamat Lengkap Pengiriman</label>
                    <div class="flex shadow-sm">
                        <textarea id="address" name="address_detail" 
                            class="form-control flex-1 p-4 border-y border-l rounded-l-2xl resize-none text-sm focus:ring-0 focus:border-stone-400 outline-none" 
                            rows="2" placeholder="Ketik alamat atau pilih langsung di peta..."></textarea>
                        <button type="button" onclick="searchByAddressText()" 
                            class="bg-stone-100 px-6 rounded-r-2xl border-y border-r hover:bg-stone-200 transition flex items-center gap-2 font-bold text-stone-700">
                            <i class="fa fa-search"></i> Cari
                        </button>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="font-bold text-stone-800 mb-3 uppercase text-[10px] tracking-[0.2em] text-center opacity-50">Tentukan Lokasi Di Peta</h3>
                    <div id="map" class="shadow-inner border-4 border-stone-50" style="height:450px; border-radius:2rem; z-index: 1;"></div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-stone-100 h-fit sticky top-10">
                <h2 class="text-2xl font-black text-stone-800 uppercase italic mb-6">
                    Rincian <span class="text-[#8B0000]">Tagihan</span>
                </h2>

                <div class="space-y-5">
                    <div class="flex justify-between text-stone-400 font-bold uppercase text-[10px] tracking-widest">
                        <span>Total Belanja</span>
                        <span class="text-stone-800 text-sm">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex justify-between text-stone-400 font-bold uppercase text-[10px] tracking-widest">
                        <span>Ongkos Kirim</span>
                        <span id="shippingCostDisplay" class="text-[#8B0000] text-sm font-black">PILIH LOKASI DI PETA...</span>
                    </div>
                    
                    <hr class="border-stone-100 my-2">

                    <div class="pt-2">
                        <span class="block text-stone-400 font-bold uppercase text-[10px] tracking-widest mb-1">Total Akhir</span>
                        <span class="text-4xl font-black text-[#8B0000] italic tracking-tighter" id="totalPriceDisplay">
                            Rp {{ number_format($totalHarga, 0, ',', '.') }}
                        </span>
                    </div>

                    <button type="submit" id="btnSubmit" disabled
                        class="w-full mt-6 bg-stone-900 text-white py-5 rounded-2xl font-black uppercase tracking-widest shadow-xl hover:bg-[#8B0000] transition-all disabled:bg-stone-100">
                        Konfirmasi & Bayar
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    const SHOP_LOCATION = L.latLng(-2.964527, 119.901500); 
    const SUB_TOTAL = parseInt("{{ $totalHarga }}") || 0;
    const TOTAL_WEIGHT = parseInt("{{ $totalWeight }}") || 1000;
    
    let userMarker; 

    const map = L.map('map').setView(SHOP_LOCATION, 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    function searchByAddressText() {
        const query = document.getElementById('address').value;
        if (query.length < 5) return alert("Masukkan alamat yang lebih spesifik agar pencarian akurat!");

        fetch(`https://nominatim.openstreetmap.org/search?format=jsonv2&q=${encodeURIComponent(query)}&limit=1`)
            .then(res => res.json())
            .then(data => {
                if (data.length > 0) {
                    const latlng = L.latLng(data[0].lat, data[0].lon);
                    map.flyTo(latlng, 16); 
                    movePin(latlng);
                } else {
                    alert("Lokasi tidak ditemukan. Coba tambahkan nama kota atau kecamatan.");
                }
            })
            .catch(err => console.error("Geocoding Error:", err));
    }

    map.on('click', function(e) {
        movePin(e.latlng);
    });

    function movePin(latlng) {
        if (userMarker) {
            userMarker.setLatLng(latlng);
        } else {
            userMarker = L.marker(latlng, { draggable: true }).addTo(map);
            userMarker.on('dragend', function() {
                updateData(userMarker.getLatLng());
            });
        }
        updateData(latlng);
    }

    async function updateData(latlng) {
        document.getElementById("latitude").value = latlng.lat;
        document.getElementById("longitude").value = latlng.lng;

        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${latlng.lat}&lon=${latlng.lng}`);
            const data = await response.json();
            
            document.getElementById("address").value = data.display_name;

            const distance = SHOP_LOCATION.distanceTo(latlng) / 1000;
            getShippingPrice(distance);

        } catch (error) {
            console.error("Gagal sinkronisasi alamat:", error);
        }
    }

    function getShippingPrice(distance) {
        const display = document.getElementById("shippingCostDisplay");
        const totalDisplay = document.getElementById("totalPriceDisplay");
        const btn = document.getElementById("btnSubmit");
        
        display.innerText = "MENGHITUNG...";
        btn.disabled = true;

        fetch(`/get-ongkir-distance?distance=${distance}&weight=${TOTAL_WEIGHT}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    const ongkir = data.cost;
                    const grandTotal = SUB_TOTAL + ongkir;

                    display.innerText = `RP ${ongkir.toLocaleString('id-ID')}`;
                    totalDisplay.innerText = `RP ${grandTotal.toLocaleString('id-ID')}`;

                    document.getElementById("hid_shipping").value = ongkir;
                    document.getElementById("hid_total").value = grandTotal;
                    
                    btn.disabled = false;
                }
            });
    }

    function validasiSebelumKirim() {
        const lat = document.getElementById("latitude").value;
        const grandTotal = document.getElementById("hid_total").value;
        const address = document.getElementById("address").value;

        if (!address || address.trim() === "") {
            alert("Silakan isi alamat lengkap pengiriman terlebih dahulu!");
            return false;
        }
        if (!lat || !grandTotal) {
            alert("Peta atau hitungan ongkir belum siap! Silakan klik lokasi rumah Anda di peta terlebih dahulu.");
            return false;
        }
        return true; 
    }
</script>
@endsection
