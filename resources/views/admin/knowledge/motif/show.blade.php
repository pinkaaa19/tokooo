@extends('layouts.admin')

@section('admin_content')
{{-- HEADER HALAMAN DETAIL --}}
<div class="mb-10 flex justify-between items-center">
    <h2 class="text-3xl font-black text-gray-900 uppercase italic tracking-tighter">
        Isi <span class="text-[#8B0000]">Digitalisasi Motif</span>
    </h2>
    <a href="{{ route('admin.knowledge.index') }}" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-black transition">
        &larr; Kembali
    </a>
</div>

{{-- GRID UTAMA LAYOUT --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
    
    {{-- KOLOM KIRI & TENGAH (KONTEN UTAMA) --}}
    <div class="lg:col-span-2 space-y-8">
        
        {{-- AREA MULTIMEDIA: DOKUMENTASI VIDEO YOUTUBE --}}
        @if($knowledge->video_url)
            <div class="bg-white p-4 rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden">
                <p class="text-[10px] uppercase font-black tracking-widest text-[#8B0000] italic mb-3 pl-2">
                    🎬 Dokumentasi Audio-Visual (YouTube)
                </p>
                <div class="aspect-video bg-black rounded-[2rem] overflow-hidden shadow-2xl">
                    @php
                        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $knowledge->video_url, $match);
                        $video_id = $match[1] ?? null;
                    @endphp
                    @if($video_id)
                        <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $video_id }}" frameborder="0" allowfullscreen></iframe>
                    @else
                        <div class="w-full h-full flex items-center justify-center text-white font-bold italic text-xs">Link Video Tidak Valid</div>
                    @endif
                </div>
            </div>
        @endif

        {{-- AREA MULTIMEDIA: VISUALISASI GAMBAR FISIK --}}
        <div class="bg-white p-4 rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden">
            <p class="text-[10px] uppercase font-black tracking-widest text-stone-400 italic mb-3 pl-2">
                🎨 Visualisasi Bentuk / Pola Motif
            </p>
            @if($knowledge->file_path)
                <img src="{{ asset('storage/' . $knowledge->file_path) }}" class="w-full h-auto rounded-[2rem] shadow-md">
            @else
                <div class="aspect-video bg-gray-50 rounded-[2rem] flex items-center justify-center border-2 border-dashed border-gray-200">
                    <span class="text-gray-400 font-bold uppercase text-[10px] tracking-widest italic">Tidak ada media gambar yang diunggah</span>
                </div>
            @endif
        </div>

        {{-- ARTIKEL FILOSOFI MOTIF --}}
        <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-gray-100">
            <h1 class="text-4xl font-black uppercase italic mb-8 text-gray-800 leading-tight">
                {{ $knowledge->title }}
            </h1>
            <div class="space-y-4">
                <p class="text-[10px] uppercase font-black tracking-widest text-[#8B0000] italic">
                    Filosofi & Deskripsi Makna Budaya
                </p>
                <div class="text-gray-600 leading-relaxed italic text-lg">
                    {!! nl2br(e($knowledge->description)) !!}
                </div>
            </div>
        </div>

        {{-- FITUR INTERAKTIF: MENAMPILKAN DAFTAR MOTIF YANG TERELASI --}}
        <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-gray-100">
            <p class="text-[10px] uppercase font-black tracking-widest text-gray-400 italic mb-6">
                Jaringan Keterkaitan Filosofi Motif Tradisional
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @forelse($knowledge->relatedMotifs as $related)
                    <a href="{{ route('admin.motif.show', $related->id) }}" class="flex items-center gap-4 p-3 bg-stone-50 hover:bg-red-50 rounded-2xl transition group">
                        <div class="w-16 h-12 rounded-xl bg-stone-200 overflow-hidden shrink-0">
                            @if($related->file_path)
                                <img src="{{ asset('storage/' . $related->file_path) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[7px] font-bold text-gray-400 uppercase">🎨</div>
                            @endif
                        </div>
                        <div class="overflow-hidden">
                            <h4 class="font-black text-xs uppercase tracking-tight text-stone-800 group-hover:text-[#8B0000] truncate">{{ $related->title }}</h4>
                            <span class="text-[8px] font-bold text-stone-400 uppercase block mt-0.5">{{ $related->group_name ?? 'Umum' }}</span>
                        </div>
                    </a>
                @empty
                    <div class="col-span-2 text-stone-400 font-bold italic text-xs uppercase tracking-widest py-2">
                        Motif ini berdiri sendiri (Belum dihubungkan dengan motif lain).
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- KOLOM KANAN (SIDEBAR METADATA & TOMBOL AKSI) --}}
    <div class="space-y-6">
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 sticky top-10">
            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-6 border-b pb-4">
                Informasi Konten
            </p>
            <div class="space-y-6">
                <div>
                    <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Dibuat Pada</span>
                    <span class="font-black text-gray-800 text-xl italic">{{ $knowledge->created_at->format('d M Y') }}</span>
                </div>
                <div>
                    <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Kelompok Motif</span>
                    <span class="font-black text-gray-800 text-lg italic mt-1">{{ $knowledge->group_name ?? 'Passura' }}</span>
                </div>
                <div>
                    <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Sumber Informasi</span>
                    <span class="block font-bold text-stone-500 text-xs mt-1 truncate" title="{{ $knowledge->source }}">{{ $knowledge->source ?? 'Aldy Art' }}</span>
                </div>
                <div>
                    <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Relasi Jaringan KMS</span>
                    <span class="inline-block bg-red-50 text-[#8B0000] px-4 py-1 rounded-full font-black text-[10px] uppercase tracking-widest mt-1">
                        {{ $knowledge->relatedMotifs ? $knowledge->relatedMotifs->count() : 0 }} Motif Terkait
                    </span>
                </div>

                {{-- TOMBOL MODUL FORM EDIT --}}
                <div class="pt-6 border-t border-stone-50">
                    <a href="{{ route('admin.motif.edit', $knowledge->id) }}" class="block w-full text-center bg-black text-white py-5 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-[#8B0000] transition shadow-lg hover:shadow-red-900/20">
                        Edit Konten Ini
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection