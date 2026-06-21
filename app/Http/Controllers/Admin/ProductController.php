<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\KnowledgeContent;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar semua produk beserta relasi gambar dan ilmu budaya (KMS).
     */
    public function index()
    {
        $products = Product::with(['knowledge', 'images'])->latest()->get();
        $knowledges = KnowledgeContent::all();
        return view('admin.products.index', compact('products', 'knowledges'));
    }

    /**
     * Menampilkan formulir untuk menambahkan produk baru.
     */
    public function create()
    {
        $knowledges = KnowledgeContent::all();
        return view('admin.products.create', compact('knowledges'));
    }

    /**
     * Menampilkan formulir edit produk berdasarkan ID.
     */
    public function edit(int $id)
    {
        $product = Product::with('images')->findOrFail($id);
        $knowledges = KnowledgeContent::all();
        return view('admin.products.edit', compact('product', 'knowledges'));
    }

    /**
     * Menyimpan produk baru ke database dan melakukan auto-binding ke ilmu budaya KMS.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        try {
            // Mengambil semua data form input mengecualikan images dan field status jika terbawa
            $data = $request->except(['images', 'status']);
            $autoLinkedMotif = null;
            $matchedId = null;

            // 1. PREPROCESSING INPUT NAMA PRODUK (Mengurangi kegagalan akibat salah ketik/tanda baca)
            $cleanedProductName = strtolower(trim($data['name']));
            $cleanedProductName = preg_replace('/[[:punct:]]/', ' ', $cleanedProductName);
            $cleanedProductName = preg_replace('/\s+/', ' ', $cleanedProductName);

            // 2. OTOMASI KMS: Mengambil data ilmu budaya yang bertipe 'image' sesuai database Anda
            // Diurutkan berdasarkan judul terpanjang agar pencocokan kata majemuk menjadi sangat akurat
            $allMotifs = KnowledgeContent::where('type', 'image')
                ->orderByRaw('LENGTH(TRIM(title)) DESC')
                ->get();

            foreach ($allMotifs as $motif) {
                $motifTitle = strtolower(trim($motif->title));
                $motifTitleClean = preg_replace('/[[:punct:]]/', ' ', $motifTitle);
                $motifTitleClean = preg_replace('/\s+/', ' ', $motifTitleClean);

                // 3. EVALUASI PENCOCOKAN STRING MULTI-LAYER
                if (
                    mb_stripos($cleanedProductName, $motifTitle) !== false || 
                    mb_stripos($cleanedProductName, $motifTitleClean) !== false ||
                    mb_stripos(str_replace(' ', '', $cleanedProductName), str_replace(' ', '', $motifTitleClean)) !== false
                ) {
                    $matchedId = $motif->id; 
                    $autoLinkedMotif = $motif->title;
                    break; // Berhenti mencari jika sudah menemukan kata yang cocok murni terpanjang
                }
            }

            // 4. FALLBACK CADANGAN JIKA TIDAK ADA YANG COCOK SAMA SEKALI (Wajib Terisi)
            if (is_null($matchedId)) {
                // Cari data alternatif di database yang namanya mengandung unsur kata 'umum'
                $fallbackMotif = KnowledgeContent::where('type', 'image')
                    ->whereRaw('LOWER(title) LIKE ?', ['%umum%'])
                    ->first();

                if ($fallbackMotif) {
                    $matchedId = $fallbackMotif->id;
                    $autoLinkedMotif = $fallbackMotif->title;
                } else {
                    // Jika data 'umum' murni belum dibuat di DB, kunci sementara ke ID 1 agar database aman tidak null
                    $matchedId = 1; 
                    $autoLinkedMotif = "Umum (Default ID 1)";
                }
            }

            // Masukkan ID hasil pencocokan murni ke field database produk
            $data['knowledge_id'] = $matchedId;

            // 5. TRANSACTION DATABASE: Menjamin konsistensi penyimpanan data ganda
            $product = DB::transaction(function () use ($data, $request) {
                $newProduct = Product::create($data);
                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $file) {
                        $path = $file->store('products', 'public');
                        ProductImage::create([
                            'product_id' => $newProduct->id, 
                            'image' => $path
                        ]);
                    }
                }
                return $newProduct;
            });

            $message = 'Produk berhasil ditambahkan. (KMS Terhubung: ' . $autoLinkedMotif . ')';
            return redirect()->route('admin.products.index')->with('success', $message);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menyimpan produk: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Memperbarui data produk dan memperbarui relasi otomatis ilmu budaya KMS.
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        try {
            $product = Product::with('images')->findOrFail($id);
            $data = $request->except(['images', 'status']);
            $autoLinkedMotif = null;
            $matchedId = null;

            // Terapkan teknik pembersihan yang sama saat proses update nama produk
            $cleanedProductName = strtolower(trim($data['name']));
            $cleanedProductName = preg_replace('/[[:punct:]]/', ' ', $cleanedProductName);
            $cleanedProductName = preg_replace('/\s+/', ' ', $cleanedProductName);

            $allMotifs = KnowledgeContent::where('type', 'image')
                ->orderByRaw('LENGTH(TRIM(title)) DESC')
                ->get();
                
            foreach ($allMotifs as $motif) {
                $motifTitle = strtolower(trim($motif->title));
                $motifTitleClean = preg_replace('/[[:punct:]]/', ' ', $motifTitle);
                $motifTitleClean = preg_replace('/\s+/', ' ', $motifTitleClean);

                if (
                    mb_stripos($cleanedProductName, $motifTitle) !== false || 
                    mb_stripos($cleanedProductName, $motifTitleClean) !== false ||
                    mb_stripos(str_replace(' ', '', $cleanedProductName), str_replace(' ', '', $motifTitleClean)) !== false
                ) {
                    $matchedId = $motif->id;
                    $autoLinkedMotif = $motif->title;
                    break;
                }
            }

            if (is_null($matchedId)) {
                $fallbackMotif = KnowledgeContent::where('type', 'image')
                    ->whereRaw('LOWER(title) LIKE ?', ['%umum%'])
                    ->first();

                if ($fallbackMotif) {
                    $matchedId = $fallbackMotif->id;
                    $autoLinkedMotif = $fallbackMotif->title;
                } else {
                    $matchedId = 1;
                    $autoLinkedMotif = "Umum (Default ID 1)";
                }
            }

            $data['knowledge_id'] = $matchedId;

            DB::transaction(function () use ($product, $data, $request) {
                // Garbage Collection: Hapus berkas fisik lama di server jika admin mengunggah file foto baru
                if ($request->hasFile('images')) {
                    foreach ($product->images as $oldImage) {
                        if (Storage::disk('public')->exists($oldImage->image)) {
                            Storage::disk('public')->delete($oldImage->image);
                        }
                        $oldImage->delete(); // Hapus baris lama di tabel product_images
                    }
                }

                $product->update($data);

                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $file) {
                        $path = $file->store('products', 'public');
                        ProductImage::create([
                            'product_id' => $product->id, 
                            'image' => $path
                        ]);
                    }
                }
            });

            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil diperbarui! (KMS Diperbarui: '.$autoLinkedMotif.')');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal memperbarui produk: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Menghapus salah satu berkas gambar produk tertentu (Pembersihan via Edit Form).
     */
    public function deleteImage(int $id)
    {
        $image = ProductImage::findOrFail($id);
        if (Storage::disk('public')->exists($image->image)) {
            Storage::disk('public')->delete($image->image);
        }
        $image->delete();
        return back()->with('success', 'Gambar berhasil dihapus.');
    }

    /**
     * Menghapus produk beserta seluruh relasi gambar fisiknya dari hardisk server.
     */
    public function destroy(int $id)
    {
        $product = Product::with('images')->findOrFail($id);
        foreach ($product->images as $image) {
            if (Storage::disk('public')->exists($image->image)) {
                Storage::disk('public')->delete($image->image);
            }
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}