@extends('layouts.admin')

@section('admin_content')
<div class="mb-10 flex justify-between items-center">
    <h2 class="text-3xl font-black text-gray-900 uppercase italic tracking-tighter">
        Tambah <span class="text-[#8B0000]">SOP Operasional Baru</span>
    </h2>
    <a href="{{ route('admin.knowledge.index') }}" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-black transition">
        &larr; Batal
    </a>
</div>

<div class="bg-white p-10 rounded-[3rem] shadow-sm border border-gray-100 max-w-3xl mx-auto">
    <form action="{{ route('admin.sop.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <div>
            <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">
                Pilih Kategori (Bisa pilih lebih dari satu)
            </label>
            <div class="grid grid-cols-2 gap-4 border border-stone-200 p-5 rounded-xl bg-white">
                @foreach(['Pakaian', 'Kain', 'Tas', 'Aksesoris', 'Ukiran & Pajangan'] as $category)
                    <label class="flex items-center space-x-3 cursor-pointer group">
                        <input type="checkbox" name="categories[]" value="{{ $category }}" 
                               class="w-4 h-4 rounded border-stone-300 text-black focus:ring-black">
                        <span class="text-sm text-stone-700 font-medium group-hover:text-black transition">
                            {{ $category }}
                        </span>
                    </label>
                @endforeach
            </div>
        </div>

        <div>
            <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">Judul / Nama Prosedur Kerja</label>
            <input type="text" name="title" placeholder="Contoh: SOP Perawatan Kain Tenun Toraja" class="w-full px-5 py-3 rounded-xl border border-stone-200 focus:outline-none focus:border-black font-medium text-sm" required>
        </div>
        
        <div>
            <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">Rincian Instruksi & Langkah Kerja</label>
            <textarea name="description" rows="10" placeholder="Masukkan tujuan, langkah pengerjaan, dan catatan perawatan di sini..." class="w-full px-5 py-3 rounded-xl border border-stone-200 focus:outline-none focus:border-black font-medium text-sm text-stone-700 leading-relaxed" required></textarea>
        </div>
        
        <div class="border-b border-stone-100 pb-6">
            <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">Upload Lampiran Visual / Flowchart (Gambar)</label>
            <input type="file" name="image" class="w-full text-stone-500 text-sm">
        </div>
        
        <div class="bg-stone-50 p-6 rounded-2xl border border-stone-100 space-y-2">
            <label class="block text-[10px] font-black uppercase tracking-wider text-[#8B0000] mb-2">🎬 Tautan Video Tutorial (YouTube)</label>
            <input type="url" name="video_url" placeholder="Contoh: https://www.youtube.com/watch?v=..." class="w-full px-5 py-3 rounded-xl border border-stone-200 bg-white focus:outline-none focus:border-black font-medium text-sm">
        </div>
        
        <div class="pt-4 flex justify-end">
            <button type="submit" class="bg-black text-white px-8 py-4 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-[#8B0000] transition shadow-md cursor-pointer">
                Terbitkan Dokumen SOP
            </button>
        </div>
    </form>
</div>
@endsection