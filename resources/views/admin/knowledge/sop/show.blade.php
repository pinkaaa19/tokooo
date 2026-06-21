@extends('layouts.admin')

@section('admin_content')
<div class="mb-10 flex justify-between items-center">
    <div class="space-y-1">
        <span class="inline-block bg-stone-100 text-stone-800 px-3 py-1 rounded-full font-black text-[9px] uppercase tracking-widest">💼 Standard Operating Procedure</span>
        <h2 class="text-3xl font-black text-gray-900 uppercase italic tracking-tighter">Detail <span class="text-[#8B0000]">Regulasi Kerja</span></h2>
    </div>
    <a href="{{ route('admin.knowledge.index') }}" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-black transition">&larr; Kembali</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
    <div class="lg:col-span-2">
        <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-gray-100 min-h-[400px]">
            <h1 class="text-4xl font-black uppercase italic mb-8 text-gray-800 leading-tight border-b border-stone-100 pb-6">{{ $knowledge->title }}</h1>
            <div class="text-gray-700 leading-loose text-base tracking-wide whitespace-pre-line font-medium pr-4">
                {!! nl2br(e($knowledge->description)) !!}
            </div>
        </div>
    </div>
    <div class="space-y-6">
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 space-y-3">
            <div>
                <span class="block text-[8px] font-bold text-gray-400 uppercase tracking-widest">Diterbitkan Pada</span>
                <span class="font-black text-stone-800 text-sm italic">{{ $knowledge->created_at->format('d M Y - H:i') }} WITA</span>
            </div>
            <div class="pt-2 border-t border-stone-50">
                <a href="{{ route('admin.sop.edit', $knowledge->id) }}" class="block w-full text-center bg-black text-white py-4 rounded-xl font-black uppercase text-[9px] tracking-widest hover:bg-[#8B0000] transition">Edit Regulasi Ini</a>
            </div>
        </div>
        @if($knowledge->file_path)
            <div class="bg-white p-4 rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <p class="text-[9px] uppercase font-black tracking-widest text-stone-400 italic mb-2.5 pl-1">📊 Lampiran Visual Diagram</p>
                <img src="{{ asset('storage/' . $knowledge->file_path) }}" class="w-full h-auto rounded-xl shadow-sm">
            </div>
        @endif
        @if($knowledge->video_url)
            <div class="bg-white p-4 rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <p class="text-[9px] uppercase font-black tracking-widest text-[#8B0000] italic mb-2.5 pl-1">🎬 Video Peragaan Praktik</p>
                <div class="aspect-video bg-black rounded-xl overflow-hidden shadow-md">
                    @php
                        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $knowledge->video_url, $match);
                        $video_id = $match[1] ?? null;
                    @endphp
                    @if($video_id)
                        <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $video_id }}" frameborder="0" allowfullscreen></iframe>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection