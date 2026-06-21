@extends('layouts.admin')

@section('admin_content')
<div class="mb-10 flex justify-between items-center">
    <h2 class="text-3xl font-black text-gray-900 uppercase italic tracking-tighter">
        Tambah <span class="text-[#8B0000]">FAQ Produk Baru</span>
    </h2>
    <a href="{{ route('admin.knowledge.index') }}" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-black transition">
        &larr; Batal
    </a>
</div>

<div class="bg-white p-10 rounded-[3rem] shadow-sm border border-gray-100 max-w-2xl mx-auto">
    <form action="{{ route('admin.faq.store') }}" method="POST" class="space-y-6">
        @csrf
        
        {{-- INPUT PERTANYAAN --}}
        <div>
            <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">Pertanyaan Konsumen</label>
            <input type="text" name="question" value="{{ old('question') }}" class="w-full px-5 py-3 rounded-xl border border-stone-200 focus:outline-none focus:border-black font-medium text-sm" placeholder="Contoh: Apakah kain tenun ini bisa luntur?" required>
        </div>

        {{-- INPUT KATEGORI (FITUR KMS) --}}
        <div>
           
            <div class="flex flex-wrap gap-3">
                @foreach(['Tas', 'Kain', 'Pakaian', 'Aksesoris', 'Ukiran&Pajangan'] as $cat)
                    <label class="cursor-pointer">
                        <input type="checkbox" name="category[]" value="{{ $cat }}" class="peer sr-only">
                        <div class="px-4 py-2 border-2 border-stone-100 rounded-lg text-[10px] font-black uppercase tracking-widest peer-checked:border-[#8B0000] peer-checked:text-[#8B0000] peer-checked:bg-[#8B0000]/5 transition-all">
                            {{ $cat }}
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- INPUT JAWABAN --}}
        <div>
            <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">Jawaban Solutif Admin</label>
            <textarea name="answer" rows="6" class="w-full px-5 py-3 rounded-xl border border-stone-200 focus:outline-none focus:border-black font-medium text-sm text-stone-700 leading-relaxed" required>{{ old('answer') }}</textarea>
        </div>

        <div class="pt-4 flex justify-end">
            <button type="submit" class="bg-black text-white px-8 py-4 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-[#8B0000] transition shadow-md">
                Terbitkan FAQ Produk
            </button>
        </div>
    </form>
</div>
@endsection