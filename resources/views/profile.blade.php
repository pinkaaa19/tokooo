@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-12">
    <div class="max-w-5xl mx-auto bg-white rounded-[2.5rem] shadow-sm border border-stone-100 overflow-hidden p-8 md:p-12">
        
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="mainProfileForm">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12">
                
                <div class="md:col-span-4 flex flex-col items-center">
                    <div class="w-full aspect-square rounded-3xl overflow-hidden border border-stone-100 mb-4 bg-stone-50">
                        <img id="preview" 
                             src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : asset('images/profil.jpg') }}" 
                             class="w-full h-full object-cover">
                    </div>
                    
                    <input type="file" name="profile_photo" id="profile_photo_input" class="hidden" onchange="previewImage(event)">
                    <button type="button" onclick="document.getElementById('profile_photo_input').click()" 
                            class="w-full py-3 border border-stone-200 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-stone-50 transition">
                        Pilih Foto
                    </button>
                    <p class="text-[9px] text-stone-400 mt-4 text-center uppercase tracking-tighter">Maks 2MB (JPG, JPEG, PNG)</p>
                </div>

                <div class="md:col-span-8">
                    <h3 class="text-stone-800 font-black uppercase italic mb-6 text-sm tracking-widest border-b pb-2">Biodata Diri</h3>
                    
                    <div class="space-y-6 mb-10">
                        <div class="flex justify-between items-center border-b border-stone-50 pb-4">
                            <span class="text-stone-400 text-sm">Nama</span>
                            <div class="flex items-center gap-4">
                                <span id="txt_name" class="font-bold text-stone-800">{{ $user->name }}</span>
                                <button type="button" class="text-green-600 text-xs font-bold" onclick="showModal('modalNama')">Ubah</button>
                            </div>
                            <input type="hidden" name="name" id="hid_name" value="{{ $user->name }}">
                        </div>

                        <div class="flex justify-between items-center border-b border-stone-50 pb-4">
                            <span class="text-stone-400 text-sm">Tanggal Lahir</span>
                            <div class="flex items-center gap-4">
                                <span id="txt_birth_date" class="font-bold {{ $user->birth_date ? 'text-stone-800' : 'text-green-600' }}">
                                    {{ $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->translatedFormat('d F Y') : 'Tambah Tanggal Lahir' }}
                                </span>
                                <button type="button" class="text-green-600 text-xs font-bold" onclick="showModal('modalTanggal')">Ubah</button>
                            </div>
                            <input type="hidden" name="birth_date" id="hid_birth_date" value="{{ $user->birth_date }}">
                        </div>

                        <div class="flex justify-between items-center border-b border-stone-50 pb-4">
                            <span class="text-stone-400 text-sm">Jenis Kelamin</span>
                            <div class="flex items-center gap-4">
                                <span id="txt_gender" class="font-bold {{ $user->gender ? 'text-stone-800' : 'text-green-600' }}">
                                    {{ $user->gender ?? 'Tambah Jenis Kelamin' }}
                                </span>
                                <button type="button" class="text-green-600 text-xs font-bold" onclick="showModal('modalGender')">Ubah</button>
                            </div>
                            <input type="hidden" name="gender" id="hid_gender" value="{{ $user->gender }}">
                        </div>
                    </div>

                    {{-- BAGIAN KONTAK --}}
                    <h3 class="text-stone-800 font-black uppercase italic mb-6 text-sm tracking-widest border-b pb-2">Ubah Kontak</h3>
                    <div class="space-y-6">
                        
                        {{-- BARIS EMAIL TERVERIFIKASI --}}
                        <div class="flex justify-between items-center border-b border-stone-50 pb-4">
                            <span class="text-stone-400 text-sm">Email</span>
                            <div class="flex items-center gap-3">
                                <span class="text-stone-800 font-bold text-sm">{{ $user->email }}</span>
                                <div class="flex items-center gap-1 bg-green-100 px-2 py-0.5 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-green-600 text-[10px] font-black uppercase italic tracking-tighter">Terverifikasi</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center border-b border-stone-50 pb-4">
                            <span class="text-stone-400 text-sm">Nomor HP</span>
                            <div class="flex items-center gap-4">
                                <span id="txt_phone" class="font-bold text-stone-800">{{ $user->phone_number ?? '-' }}</span>
                                <button type="button" class="text-green-600 text-xs font-bold" onclick="showModal('modalPhone')">Ubah</button>
                            </div>
                            <input type="hidden" name="phone_number" id="hid_phone_number" value="{{ $user->phone_number }}">
                        </div>
                    </div>

                    <div class="mt-12 flex justify-end">
                        <button type="submit" id="btnSubmit" class="bg-stone-900 text-white px-12 py-4 rounded-2xl font-black uppercase tracking-widest shadow-xl hover:bg-[#8B0000] transition-all">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- MODAL OVERLAY --}}
<div id="modalOverlay" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="absolute inset-0" onclick="hideModal()"></div>
    
    <div id="modalNama" class="modal-box hidden relative bg-white w-full max-w-md rounded-[2.5rem] p-8 shadow-2xl">
        <h3 class="text-lg font-black uppercase italic mb-4">Ubah Nama</h3>
        <input type="text" id="in_name" value="{{ $user->name }}" class="w-full bg-stone-50 border p-4 rounded-xl mb-6 font-bold outline-none">
        <button type="button" onclick="applyNama()" class="w-full bg-green-600 text-white py-4 rounded-xl font-black uppercase">Terapkan</button>
    </div>

    <div id="modalTanggal" class="modal-box hidden relative bg-white w-full max-w-md rounded-[2.5rem] p-8 shadow-2xl">
        <h3 class="text-lg font-black uppercase italic mb-4 text-center">Tambah Tanggal Lahir</h3>
        <p class="text-xs text-stone-400 text-center mb-6">Kamu hanya dapat mengatur tanggal lahir satu kali.</p>
        <div class="grid grid-cols-3 gap-3 mb-8">
            <select id="sel_tgl" class="border p-3 rounded-xl font-bold bg-stone-50 outline-none">@for($i=1;$i<=31;$i++)<option value="{{$i}}">{{$i}}</option>@endfor</select>
            <select id="sel_bln" class="border p-3 rounded-xl font-bold bg-stone-50 outline-none">@foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $k=>$v)<option value="{{$k+1}}">{{$v}}</option>@endforeach</select>
            <select id="sel_thn" class="border p-3 rounded-xl font-bold bg-stone-50 outline-none">@for($i=date('Y');$i>=1970;$i--)<option value="{{$i}}">{{$i}}</option>@endfor</select>
        </div>
        <button type="button" onclick="applyTanggal()" class="w-full bg-green-600 text-white py-4 rounded-xl font-black uppercase">Simpan</button>
    </div>

    <div id="modalGender" class="modal-box hidden relative bg-white w-full max-w-md rounded-[2.5rem] p-10 text-center shadow-2xl">
        <h3 class="text-lg font-black uppercase italic mb-8">Pilih Jenis Kelamin</h3>
        <div class="grid grid-cols-2 gap-4">
            <button type="button" onclick="applyGender('Pria')" class="border-2 py-6 rounded-3xl font-black hover:border-green-600 transition-all uppercase text-xs tracking-widest">Pria</button>
            <button type="button" onclick="applyGender('Wanita')" class="border-2 py-6 rounded-3xl font-black hover:border-green-600 transition-all uppercase text-xs tracking-widest">Wanita</button>
        </div>
    </div>

    <div id="modalPhone" class="modal-box hidden relative bg-white w-full max-w-md rounded-[2.5rem] p-8 shadow-2xl">
        <h3 class="text-lg font-black uppercase italic mb-4">Ubah Nomor HP</h3>
        <input type="text" id="in_phone" value="{{ $user->phone_number }}" class="w-full bg-stone-50 border p-4 rounded-xl mb-6 font-bold outline-none" placeholder="08xxxx">
        <button type="button" onclick="applyPhone()" class="w-full bg-green-600 text-white py-4 rounded-xl font-black uppercase">Terapkan</button>
    </div>
</div>

<script>
    function showModal(id) {
        document.getElementById('modalOverlay').classList.remove('hidden');
        document.querySelectorAll('.modal-box').forEach(b => b.classList.add('hidden'));
        document.getElementById(id).classList.remove('hidden');
    }
    function hideModal() { document.getElementById('modalOverlay').classList.add('hidden'); }

    function applyNama() {
        const val = document.getElementById('in_name').value;
        document.getElementById('txt_name').innerText = val;
        document.getElementById('hid_name').value = val;
        hideModal();
    }
    function applyTanggal() {
        const t = document.getElementById('sel_tgl').value;
        const b = document.getElementById('sel_bln').value;
        const th = document.getElementById('sel_thn').value;
        const nb = document.getElementById('sel_bln').options[document.getElementById('sel_bln').selectedIndex].text;
        document.getElementById('txt_birth_date').innerText = `${t} ${nb} ${th}`;
        document.getElementById('txt_birth_date').className = 'font-bold text-stone-800 text-sm';
        document.getElementById('hid_birth_date').value = `${th}-${b.padStart(2,'0')}-${t.padStart(2,'0')}`;
        hideModal();
    }
    function applyGender(val) {
        document.getElementById('txt_gender').innerText = val;
        document.getElementById('txt_gender').className = 'font-bold text-stone-800 text-sm';
        document.getElementById('hid_gender').value = val;
        hideModal();
    }
    function applyPhone() {
        const val = document.getElementById('in_phone').value;
        document.getElementById('txt_phone').innerText = val;
        document.getElementById('hid_phone_number').value = val;
        hideModal();
    }

    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = () => document.getElementById('preview').src = reader.result;
        reader.readAsDataURL(event.target.files[0]);
    }

    document.getElementById('mainProfileForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSubmit');
        const formData = new FormData(this);

        btn.disabled = true;
        btn.innerText = 'MEMPROSES...';

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                const navImg = document.getElementById('navbarAvatar');
                const navName = document.getElementById('navbarName');
                if(navImg) navImg.src = data.new_photo_url;
                if(navName) navName.innerText = data.new_name;

                alert(data.message);
            }
            btn.disabled = false;
            btn.innerText = 'SIMPAN PERUBAHAN';
        })
        .catch(err => {
            console.error(err);
            alert('Gagal menyimpan. Periksa koneksi Anda.');
            btn.disabled = false;
            btn.innerText = 'SIMPAN PERUBAHAN';
        });
    });
</script>
@endsection