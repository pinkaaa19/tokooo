<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SopContent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SopController extends Controller
{
    /**
     * Menampilkan form untuk membuat SOP baru.
     */
    public function create()
    {
        return view('admin.knowledge.sop.create');
    }

    /**
     * Menyimpan SOP baru ke database.
     */// Pastikan method ini ada di dalam class SopController
public function show(int $id)
{
    $knowledge = SopContent::findOrFail($id);
    return view('admin.knowledge.sop.show', compact('knowledge'));
}
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'title'       => 'required|max:255',
            'description' => 'required',
            'categories'  => 'required|array', // Validasi checkbox sebagai array
            'image'       => 'nullable|image|mimes:jpg,png,jpeg|max:5120',
            'video_url'   => 'nullable|url'
        ]);

        // 2. Proses Upload File
        $filePath = null;
        if ($request->hasFile('image')) {
            $filePath = $request->file('image')->store('sop', 'public');
        }

        // 3. Generate Slug yang unik
        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;
        while (SopContent::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        // 4. Simpan ke Database
        SopContent::create([
            'title'       => $request->title,
            'description' => $request->description,
            'category'    => implode(',', $request->categories), // Array ke String: "Pakaian,Tas"
            'video_url'   => $request->video_url,
            'slug'        => $slug,
            'file_path'   => $filePath,
        ]);

        return redirect()->route('admin.knowledge.index')->with('success', 'SOP Operasional Berhasil Diterbitkan!');
    }

    /**
     * Menampilkan form edit dengan data yang tersimpan.
     */
    public function edit(int $id)
    {
        $knowledge = SopContent::findOrFail($id);
        return view('admin.knowledge.sop.edit', compact('knowledge'));
    }

    /**
     * Memperbarui SOP yang sudah ada.
     */
    public function update(Request $request, int $id)
    {
        $knowledge = SopContent::findOrFail($id);

        // 1. Validasi Input
        $request->validate([
            'title'       => 'required|max:255',
            'description' => 'required',
            'categories'  => 'required|array', // Validasi array
            'image'       => 'nullable|image|mimes:jpg,png,jpeg|max:5120',
            'video_url'   => 'nullable|url'
        ]);

        // 2. Siapkan data untuk update
        $data = $request->except(['image', 'categories']);
        $data['category'] = implode(',', $request->categories); // Update kategori sebagai string

        // 3. Logika Update Slug jika Judul berubah
        if ($request->title != $knowledge->title) {
            $slug = Str::slug($request->title);
            $originalSlug = $slug;
            $count = 1;
            while (SopContent::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
            $data['slug'] = $slug;
        }

        // 4. Logika Update Gambar (Hapus lama, simpan baru)
        if ($request->hasFile('image')) {
            if ($knowledge->file_path && Storage::disk('public')->exists($knowledge->file_path)) {
                Storage::disk('public')->delete($knowledge->file_path);
            }
            $data['file_path'] = $request->file('image')->store('sop', 'public');
        }

        // 5. Simpan perubahan
        $knowledge->update($data);

        return redirect()->route('admin.knowledge.index')->with('success', 'SOP Berhasil Diperbarui!');
    }

    /**
     * Menghapus SOP dari sistem.
     */
    public function destroy(int $id)
    {
        $knowledge = SopContent::findOrFail($id);
        if ($knowledge->file_path && Storage::disk('public')->exists($knowledge->file_path)) {
            Storage::disk('public')->delete($knowledge->file_path);
        }
        $knowledge->delete();

        return redirect()->route('admin.knowledge.index')->with('success', 'SOP Berhasil Dihapus!');
    }
}