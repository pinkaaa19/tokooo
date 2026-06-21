@extends('layouts.admin')

@section('admin_content')
{{-- HEADER FORM CREATE --}}
<div class="mb-10 flex justify-between items-center">
    <h2 class="text-3xl font-black text-gray-900 uppercase italic tracking-tighter">
        Tambah <span class="text-[#8B0000]">Filosofi Motif Baru</span>
    </h2>
    <a href="{{ route('admin.knowledge.index') }}" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-black transition">
        &larr; Kembali
    </a>
</div>

{{-- FORM INPUT DATA MULTIMEDIA --}}
<div class="bg-white p-10 rounded-[3rem] shadow-sm border border-gray-100 max-w-3xl">
    <form action="{{ route('admin.motif.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- INPUT NAMA / JUDUL MOTIF --}}
        <div>
            <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">Nama / Judul Motif</label>
            <input type="text" name="title" placeholder="Masukkan nama motif (Contoh: Pa'Ssedan)" class="w-full px-5 py-3 rounded-xl border border-stone-200 focus:outline-none focus:border-black font-medium" required>
        </div>

        {{-- INPUT KELOMPOK MOTIF --}}
        <div>
            <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">Kelompok Motif (Group Name)</label>
            <input type="text" name="group_name" placeholder="Contoh: Passura' Pao" class="w-full px-5 py-3 rounded-xl border border-stone-200 focus:outline-none focus:border-black font-medium">
        </div>

        {{-- INPUT SUMBER SEJARAH --}}
        <div>
            <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">Sumber Informasi Sejarah</label>
            <input type="text" name="source" placeholder="Contoh: Tokoh Adat / Buku Sejarah Toraja" class="w-full px-5 py-3 rounded-xl border border-stone-200 focus:outline-none focus:border-black font-medium">
        </div>

        {{-- INPUT ARTIKEL / NARASI FILOSOFI --}}
        <div>
            <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">Narasi Filosofi & Makna Budaya</label>
            <textarea name="description" rows="6" placeholder="Tuliskan penjelasan lengkap mengenai filosofi, nilai luhur, dan makna kebudayaan dari motif ini..." class="w-full px-5 py-3 rounded-xl border border-stone-200 focus:outline-none focus:border-black font-medium" required></textarea>
        </div>

        {{-- KANVAS UNGGAH GAMBAR FISIK --}}
        <div class="border-b border-stone-100 pb-6">
            <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">Media Gambar Motif</label>
            <input type="file" name="image" class="w-full text-stone-500 text-sm" required>
            <p class="text-[9px] text-stone-400 mt-1">*Wajib mengunggah gambar representasi fisik motif tradisional.</p>
        </div>

        {{-- FITUR BARU: SEKTOR INPUT URL VIDEO YOUTUBE --}}
        <div class="bg-stone-50 p-6 rounded-2xl border border-stone-100 space-y-2">
            <label class="block text-[10px] font-black uppercase tracking-wider text-[#8B0000] mb-2">
                🎬 Tautan Video YouTube (Opsional)
            </label>
            <input type="url" name="video_url" placeholder="Contoh: https://www.youtube.com/watch?v=..." 
                class="w-full px-5 py-3 rounded-xl border border-stone-200 bg-white focus:outline-none focus:border-black font-medium text-sm">
            <p class="text-[9px] text-stone-400">
                *Menambahkan link video YouTube akan memperkaya dokumentasi audio-visual sistem KMS pada halaman detail produk budaya.
            </p>
        </div>

        {{-- PILIHAN KETERKAITAN JARINGAN MOTIF (RELASI PIVOT M2M) --}}
        <div>
            <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">Hubungkan dengan Motif Terkait</label>
            <div class="grid grid-cols-2 gap-3 max-h-48 overflow-y-auto p-4 bg-stone-50 rounded-2xl border border-stone-100">
                @forelse($allMotifs as $motif)
                    <label class="flex items-center gap-3 p-2 bg-white rounded-xl border border-stone-100 shadow-sm cursor-pointer hover:border-black transition">
                        <input type="checkbox" name="related_motifs[]" value="{{ $motif->id }}" class="rounded border-stone-300 text-black focus:ring-black">
                        <span class="text-xs font-black uppercase tracking-tight text-stone-700 truncate">{{ $motif->title }}</span>
                    </label>
                @empty
                    <p class="text-[10px] text-stone-400 font-bold uppercase italic p-2">Belum ada data motif lain untuk dihubungkan.</p>
                @endforelse
            </div>
        </div>

        {{-- BUTTON SUBMIT FORM --}}
        <div class="pt-4">
            <button type="submit" class="bg-black text-white px-8 py-4 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-[#8B0000] transition shadow-md cursor-pointer">
                Terbitkan Data Motif Baru
            </button>
        </div>
    </form>
</div>
@endsection