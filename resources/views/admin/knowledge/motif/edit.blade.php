@extends('layouts.admin')

@section('admin_content')
{{-- HEADER FORM EDIT --}}
<div class="mb-10 flex justify-between items-center">
    <h2 class="text-3xl font-black text-gray-900 uppercase italic tracking-tighter">
        Edit <span class="text-[#8B0000]">Filosofi Motif</span>
    </h2>
    <a href="{{ route('admin.knowledge.index') }}" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-black transition">
        &larr; Batal
    </a>
</div>

{{-- FORM EDIT DATA MULTIMEDIA --}}
<div class="bg-white p-10 rounded-[3rem] shadow-sm border border-gray-100 max-w-3xl">
    <form action="{{ route('admin.motif.update', $knowledge->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- INPUT JURUSAN / NAMA MOTIF --}}
        <div>
            <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">Nama / Judul Motif</label>
            <input type="text" name="title" value="{{ $knowledge->title }}" class="w-full px-5 py-3 rounded-xl border border-stone-200 focus:outline-none focus:border-black font-medium" required>
        </div>

        {{-- INPUT KELOMPOK MOTIF --}}
        <div>
            <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">Kelompok Motif (Group Name)</label>
            <input type="text" name="group_name" value="{{ $knowledge->group_name }}" class="w-full px-5 py-3 rounded-xl border border-stone-200 focus:outline-none focus:border-black font-medium">
        </div>

        {{-- INPUT SUMBER SEJARAH --}}
        <div>
            <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">Sumber Informasi Sejarah</label>
            <input type="text" name="source" value="{{ $knowledge->source }}" class="w-full px-5 py-3 rounded-xl border border-stone-200 focus:outline-none focus:border-black font-medium">
        </div>

        {{-- INPUT ARTIKEL / NARASI FILOSOFI --}}
        <div>
            <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">Narasi Filosofi & Makna</label>
            <textarea name="description" rows="6" class="w-full px-5 py-3 rounded-xl border border-stone-200 focus:outline-none focus:border-black font-medium" required>{{ $knowledge->description }}</textarea>
        </div>

        {{-- KANVAS UNGGAH GAMBAR FISIK DAN LIVE PREVIEW --}}
        <div class="border-b border-stone-100 pb-6 space-y-3">
            <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">Ganti Media Gambar (Kosongkan jika tidak diubah)</label>
            <input type="file" name="image" class="w-full text-stone-500 text-sm mb-2">
            
            @if($knowledge->file_path)
                <div class="mt-3 p-2 bg-stone-50 border border-stone-100 rounded-2xl inline-block max-w-[240px]">
                    <p class="text-[8px] font-black uppercase tracking-tight text-stone-400 mb-1.5 pl-1">🖼️ Preview Gambar Saat Ini:</p>
                    <img src="{{ asset('storage/' . $knowledge->file_path) }}" class="w-full h-auto rounded-xl shadow-sm object-cover max-h-36">
                </div>
            @endif
        </div>

        {{-- FITUR BARU: SEKTOR INPUT URL VIDEO YOUTUBE DAN LIVE PLAYER PREVIEW --}}
        <div class="bg-stone-50 p-6 rounded-2xl border border-stone-100 space-y-4">
            <label class="block text-[10px] font-black uppercase tracking-wider text-[#8B0000] mb-2">
                🎬 Tautan Video YouTube (Opsional)
            </label>
            <input type="url" name="video_url" value="{{ $knowledge->video_url }}" placeholder="Contoh: https://www.youtube.com/watch?v=..." 
                class="w-full px-5 py-3 rounded-xl border border-stone-200 bg-white focus:outline-none focus:border-black font-medium text-sm">
            
            @if($knowledge->video_url)
                <div class="mt-3 space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        <p class="text-[10px] text-stone-500 font-bold">
                            Video aktif terdeteksi: 
                            <a href="{{ $knowledge->video_url }}" target="_blank" class="text-[#8B0000] underline hover:text-red-700 transition ml-1">
                                Buka tautan video asli &rarr;
                            </a>
                        </p>
                    </div>
                    
                    {{-- LIVE PLAYER IFRAME YOUTUBE --}}
                    <div class="max-w-md aspect-video bg-black rounded-xl overflow-hidden shadow-md">
                        @php
                            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $knowledge->video_url, $match);
                            $video_id = $match[1] ?? null;
                        @endphp
                        @if($video_id)
                            <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $video_id }}" frameborder="0" allowfullscreen></iframe>
                        @else
                            <div class="w-full h-full flex items-center justify-center text-white font-bold italic text-[10px]">Format Tautan YouTube Tidak Valid</div>
                        @endif
                    </div>
                </div>
            @else
                <p class="text-[9px] text-stone-400">
                    *Menambahkan link video YouTube akan memperkaya dokumentasi audio-visual sistem KMS pada halaman detail.
                </p>
            @endif
        </div>

        {{-- PILIHAN KETERKAITAN JARINGAN MOTIF (RELASI JIKA ADA) --}}
        <div>
            <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">Hubungkan dengan Motif Lain</label>
            <div class="grid grid-cols-2 gap-3 max-h-48 overflow-y-auto p-4 bg-stone-50 rounded-2xl border border-stone-100">
                @forelse($allMotifs as $motif)
                    <label class="flex items-center gap-3 p-2 bg-white rounded-xl border border-stone-100 shadow-sm cursor-pointer hover:border-black transition">
                        <input type="checkbox" name="related_motifs[]" value="{{ $motif->id }}" 
                            {{ in_array($motif->id, $relatedMotifsIds) ? 'checked' : '' }}
                            class="rounded border-stone-300 text-black focus:ring-black">
                        <span class="text-xs font-black uppercase tracking-tight text-stone-700 truncate">{{ $motif->title }}</span>
                    </label>
                @empty
                    <p class="text-[10px] text-stone-400 font-bold uppercase italic p-2">Tidak ada motif lain yang tersedia.</p>
                @endforelse
            </div>
        </div>

        {{-- BUTTON SUBMIT FORM --}}
        <div class="pt-4">
            <button type="submit" class="bg-black text-white px-8 py-4 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-[#8B0000] transition shadow-md cursor-pointer">
                Simpan Perubahan Motif
            </button>
        </div>
    </form>
</div>
@endsection