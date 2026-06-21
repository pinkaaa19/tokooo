@extends('layouts.admin')

@section('admin_content')
{{-- HEADER ATAS --}}
<div class="mb-10 flex justify-between items-center">
    <div class="space-y-1">
        <span class="inline-block bg-stone-100 text-stone-800 px-3 py-1 rounded-full font-black text-[9px] uppercase tracking-widest">💬 Frequently Asked Questions</span>
        <h2 class="text-3xl font-black text-gray-900 uppercase italic tracking-tighter">Detail <span class="text-[#8B0000]">Edukasi Produk</span></h2>
    </div>
    <a href="{{ route('admin.knowledge.index') }}" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-black transition">&larr; Kembali</a>
</div>

{{-- GRID BLOK UTAMA FAQ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
    <div class="lg:col-span-2 space-y-6">
        {{-- BLOK PERTANYAAN (QUESTION) --}}
        <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-gray-100 border-l-8 border-[#8B0000]">
            <span class="block text-[8px] font-black text-[#8B0000] uppercase tracking-widest mb-3">Pertanyaan Konsumen (Q)</span>
            <h1 class="text-2xl font-black italic text-gray-800 leading-tight">
                "{{ $faq->question }}"
            </h1>
        </div>

        {{-- BLOK JAWABAN (ANSWER) --}}
        <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-gray-100 min-h-[250px]">
            <span class="block text-[8px] font-black text-gray-400 uppercase tracking-widest mb-4 border-b border-stone-50 pb-2">Jawaban Solutif Admin (A)</span>
            <div class="text-gray-700 leading-loose text-base tracking-wide whitespace-pre-line font-medium pr-4">
                {!! nl2br(e($faq->answer)) !!}
            </div>
        </div>
    </div>

    {{-- SIDEBAR AKSI & METADATA --}}
    <div class="space-y-6">
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 space-y-4">
            <div>
                <span class="block text-[8px] font-bold text-gray-400 uppercase tracking-widest">Diterbitkan Pada</span>
                <span class="font-black text-stone-800 text-sm italic">{{ $faq->created_at->format('d M Y - H:i') }} WITA</span>
            </div>

            <div class="pt-2 border-t border-stone-50">
                <span class="block text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-2">Kategori Terkait</span>
                <div class="flex flex-wrap gap-1">
                    @foreach(explode(',', $faq->category ?? 'Umum') as $cat)
                        <span class="bg-stone-100 text-[#8B0000] px-2 py-1 rounded text-[9px] font-black uppercase tracking-wider">
                            {{ trim($cat) }}
                        </span>
                    @endforeach
                </div>
            </div>

            <div class="pt-2 border-t border-stone-50">
                <a href="{{ route('admin.faq.edit', $faq->id) }}" class="block w-full text-center bg-black text-white py-4 rounded-xl font-black uppercase text-[9px] tracking-widest hover:bg-[#8B0000] transition shadow-md">
                    Edit FAQ Ini
                </a>
            </div>
        </div>

        {{-- FITUR BARU: Statistik Feedback --}}
        <div class="bg-white p-6 rounded-[2rem] border border-stone-100 shadow-sm">
            <h3 class="font-black text-stone-900 uppercase text-[9px] mb-4 tracking-widest border-b border-stone-50 pb-2">Analisis Kepuasan User</h3>
            <div class="grid grid-cols-2 gap-4 text-center">
                <div>
                    <span class="block text-2xl font-black text-green-600">{{ $faq->feedbacks()->where('is_helpful', 1)->count() }}</span>
                    <span class="text-[8px] font-bold text-gray-400 uppercase">Membantu</span>
                </div>
                <div>
                    <span class="block text-2xl font-black text-red-600">{{ $faq->feedbacks()->where('is_helpful', 0)->count() }}</span>
                    <span class="text-[8px] font-bold text-gray-400 uppercase">Tidak</span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection