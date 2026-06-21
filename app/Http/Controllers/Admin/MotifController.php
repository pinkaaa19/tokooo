<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeContent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MotifController extends Controller
{
    /**
     * Halaman Tambah Data Motif
     */
    public function create()
    {
        // SINKRONISASI: Mengubah penyaringan dari 'filosofi' menjadi 'motif'
        $allMotifs = KnowledgeContent::whereIn('type', ['motif', 'image', 'video'])
                                      ->orderBy('title', 'asc')
                                      ->get();

        return view('admin.knowledge.motif.create', compact('allMotifs'));
    }

    /**
     * Menyimpan Data Motif Baru ke Database
     */
public function store(Request $request)
{
    // 1. Validasi Input Form
    $request->validate([
        'title' => 'required|max:255',
        'description' => 'required',
        'image' => 'nullable|image|mimes:jpg,png,jpeg|max:5120',
        'video_url' => 'nullable|url',
        'group_name' => 'nullable|max:255',
        'source' => 'nullable|string|max:255',
        'related_motifs' => 'nullable|array'
    ]);

    // 2. Handle Unggah Gambar Fisik
    $filePath = null;
    if ($request->hasFile('image')) {
        $filePath = $request->file('image')->store('knowledge', 'public');
    }

    // 3. Penentuan Tipe
    $dbType = $request->filled('video_url') ? 'video' : 'image';

    // 4. Generator Slug Unik
    $slug = \Illuminate\Support\Str::slug($request->title);
    $originalSlug = $slug;
    $count = 1;
    while (KnowledgeContent::where('slug', $slug)->exists()) {
        $slug = $originalSlug . '-' . $count;
        $count++;
    }

    // 5. Simpan ke Database
    $knowledge = KnowledgeContent::create([
        'title'       => $request->title,
        'group_name'  => $request->group_name,
        'source'      => $request->source,
        'description' => $request->description,
        'video_url'   => $request->video_url,
        'type'        => $dbType,
        'slug'        => $slug,
        'file_path'   => $filePath,
    ]);

    // --- PERBAIKAN: AUTOMATED KNOWLEDGE LINKING ---
    // Sistem secara otomatis memindai produk yang relevan 
    // dan mengaitkannya dengan ID motif (KnowledgeContent) yang baru dibuat.
    \App\Models\Product::where('name', 'like', '%' . $request->title . '%')
                       ->whereNull('motif_id')
                       ->update(['motif_id' => $knowledge->id]);
    // ----------------------------------------------

    // 6. Sinkronisasi Hubungan Jaringan Antar-Motif (Pivot)
    if ($request->has('related_motifs')) {
        $knowledge->relatedMotifs()->sync($request->related_motifs);
    }

    return redirect()->route('admin.knowledge.index')
                     ->with('success', 'Digitalisasi Motif Budaya Toraja Berhasil Diterbitkan & Produk terkait telah terhubung!');
}
    /**
     * Halaman Detail Konten Visualisasi Motif
     */
    public function show(int $id)
    {
        $knowledge = KnowledgeContent::with('relatedMotifs')->findOrFail($id);
        return view('admin.knowledge.motif.show', compact('knowledge'));
    }

    /**
     * Halaman Edit Data Motif
     */
    public function edit(int $id)
    {
        $knowledge = KnowledgeContent::findOrFail($id);
        
        // SINKRONISASI: Mengubah penyaringan dari 'filosofi' menjadi 'motif' untuk daftar relasi
        $allMotifs = KnowledgeContent::where('id', '!=', $id)
                                      ->whereIn('type', ['motif', 'image', 'video'])
                                      ->orderBy('title', 'asc')
                                      ->get();
        
        // Ambil daftar ID motif yang saat ini berelasi dari tabel pivot
        $relatedMotifsIds = $knowledge->relatedMotifs()->pluck('related_id')->toArray(); 
        
        return view('admin.knowledge.motif.edit', compact('knowledge', 'allMotifs', 'relatedMotifsIds'));
    }

    /**
     * Memproses Perubahan Data Motif (Update)
     */
public function update(Request $request, int $id)
{
    $knowledge = KnowledgeContent::findOrFail($id);
    
    // 1. Validasi Input Form Edit
    $request->validate([
        'title' => 'required|max:255',
        'description' => 'required',
        'image' => 'nullable|image|mimes:jpg,png,jpeg|max:5120',
        'video_url' => 'nullable|url',
        'group_name' => 'nullable|max:255',
        'source' => 'nullable|string|max:255',
        'related_motifs' => 'nullable|array'
    ]);

    // 2. Ambil data input kecuali berkas gambar fisik
    $data = $request->except('image');
    
    // 3. STRATEGI KUNCI ENUM: Tentukan tipe data yang diizinkan MySQL ('image' atau 'video')
    // Jika kolom video_url diisi, set ke 'video'. Jika kosong, default ke 'image'
    $data['type'] = $request->filled('video_url') ? 'video' : 'image'; 

    // 4. Regenerasi slug secara aman jika judul diubah oleh admin
    if ($request->title != $knowledge->title) {
        $slug = \Illuminate\Support\Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;
        while (KnowledgeContent::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        $data['slug'] = $slug;
    }

    // 5. Handle Penggantian Berkas Gambar Fisik Baru
    if ($request->hasFile('image')) {
        // Hapus gambar lama dari public storage jika file fisiknya ada
        if ($knowledge->file_path && Storage::disk('public')->exists($knowledge->file_path)) {
            Storage::disk('public')->delete($knowledge->file_path);
        }
        // Simpan gambar baru yang diunggah
        $data['file_path'] = $request->file('image')->store('knowledge', 'public');
    }

    // 6. Eksekusi Pembaruan Data ke Database
    $knowledge->update($data);

    // 7. Sinkronisasi Ulang Jaringan Relasi Motif Tradisional (Tabel Pivot)
    if ($request->has('related_motifs')) {
        $knowledge->relatedMotifs()->sync($request->related_motifs);
    } else {
        $knowledge->relatedMotifs()->detach(); // Kosongkan relasi jika semua checkbox tidak dicentang
    }

    return redirect()->route('admin.knowledge.index')->with('success', 'Filosofi Motif berhasil diperbarui!');
}
    /**
     * Menghapus Data Motif secara Permanen beserta Berkas & Relasinya
     */
    public function destroy(int $id)
    {
        $knowledge = KnowledgeContent::findOrFail($id);
        
        if ($knowledge->file_path && Storage::disk('public')->exists($knowledge->file_path)) {
            Storage::disk('public')->delete($knowledge->file_path);
        }
        
        // Putus hubungan relasi terlebih dahulu agar integrity constraint aman
        $knowledge->relatedMotifs()->detach();
        $knowledge->delete();
        
        return redirect()->route('admin.knowledge.index')->with('success', 'Data motif berhasil dihapus dari sistem.');
    }
}