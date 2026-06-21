<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeContent;
use App\Models\SopContent;
use App\Models\FaqContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KnowledgeController extends Controller
{
    /**
     * Menampilkan daftar semua konten KMS (Dashboard Global Index)
     * Otomatis mendukung Eager Loading relasi agar mempercepat loading database.
     */
public function index()
{
    // 1. Ambil data motif dari tabel knowledge_contents
    $motifs = KnowledgeContent::with('relatedMotifs')
        ->whereIn('type', ['image', 'video', 'animation'])
        ->latest()
        ->get();
    
    // 2. Ambil data SOP dari tabel sop_contents menggunakan model baru
    $sops = \App\Models\SopContent::latest()->get();
    
    $faqs = \App\Models\FaqContent::latest()->get(); // 2. Tarik data FAQ dari tabel baru
        
        // 3. Masukkan 'faqs' ke dalam compact
        return view('admin.knowledge.index', compact('motifs', 'sops', 'faqs'));

}
    /**
     * Menghapus seluruh data konten KMS secara Global (Motif / SOP / FAQ)
     */
    public function destroy(int $id)
    {
        $knowledge = KnowledgeContent::findOrFail($id);
        
        // Hapus aset file fisik dari storage jika ada
        if ($knowledge->file_path) {
            if (Storage::disk('public')->exists($knowledge->file_path)) {
                Storage::disk('public')->delete($knowledge->file_path);
            }
        }
        
        // Putus hubungan relasi di tabel pivot agar tidak terjadi data yatim (orphan data)
        $knowledge->relatedMotifs()->detach();
        
        $knowledge->delete();
        
        return redirect()->route('admin.knowledge.index')->with('success', 'Data komparatif KMS berhasil dihapus secara permanen.');
    }

    /**
     * Menghapus file gambar tanpa menghapus data teks secara Global
     */
    public function deleteImage(int $id)
    {
        $knowledge = KnowledgeContent::findOrFail($id);

        if ($knowledge->file_path) {
            if (Storage::disk('public')->exists($knowledge->file_path)) {
                Storage::disk('public')->delete($knowledge->file_path);
            }
            
            $knowledge->update([
                'file_path' => null
            ]);
        }

        return back()->with('success', 'Aset media gambar berhasil dihapus dari server!');
    }
}