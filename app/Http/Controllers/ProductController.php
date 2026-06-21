<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\SopContent; // PERBAIKAN 1: Impor jalur model SOP yang benar
use App\Models\FaqContent;
use App\Models\KnowledgeContent; // Tambahkan ini
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
{
$products = Product::with('images')->get();
return view('koleksi', compact('products'));
}

public function show(int $id)
{
    // Eager load knowledge agar tidak terjadi query tambahan saat di view
    $product = Product::with(['images', 'knowledge'])->findOrFail($id);

    // 1. Ambil motif yang mungkin sudah terikat (via knowledge_id) 
    // ATAU cari secara dinamis jika belum ada keterikatan
    $foundMotif = $product->knowledge; // Coba ambil dari relasi database dulu

    if (!$foundMotif) {
        // Jika tidak ada relasi di DB, baru lakukan pencarian otomatis (Rule-Based Matching)
        $allMotifs = KnowledgeContent::with('relatedMotifs')
                     ->where('type', 'motif')
                     ->get();
        
        $foundMotif = $allMotifs->first(function ($motif) use ($product) {
            return mb_stripos($product->name, trim($motif->title)) !== false;
        });
    }

    // 2. Ambil SOP/FAQ berdasarkan motif yang ditemukan
    $sops = collect();
    $faqs = collect();
    
    if ($foundMotif && !empty($foundMotif->group_name)) {
        $sops = SopContent::where('category', 'LIKE', '%' . $foundMotif->group_name . '%')->get();
        $faqs = FaqContent::where('category', 'LIKE', '%' . $foundMotif->group_name . '%')->get();
    }

    return view('products.show', compact('product', 'foundMotif', 'sops', 'faqs'));
}
    public function create()
{
return view('products.create');
}

    public function store(Request $request)
{

$image = $request->file('image')->store('products','public');

Product::create([
'name'=>$request->name,
'price'=>$request->price,
'category'    => $request->category,
'description'=>$request->description,
'knowledge'=>$request->knowledge,
'image'=>$image,
'stock'=>$request->stock
]);

return redirect('/koleksi');

}

public function search(Request $request)
{
    $query = $request->input('query');

    // 1. Menghapus tanda titik di akhir kalimat jika ada
    $cleanQuery = rtrim($query, '.');

    // 2. Menghapus kata perintah agar hanya mengambil kata benda (produk)
    $filters = ['carikan saya', 'cari'];
    $cleanQuery = str_ireplace($filters, '', $cleanQuery);
    
    // 3. Menghapus spasi berlebih
    $cleanQuery = trim($cleanQuery);

    // 4. Pencarian berdasarkan Nama Produk ATAU Kategori
    $products = Product::where('name', 'LIKE', "%{$cleanQuery}%")
                ->orWhere('category', 'LIKE', "%{$cleanQuery}%")
                ->get();

    return view('products.search', [
        'products' => $products,
        'query' => $query // Tetap menampilkan kalimat asli di header
    ]);
}
}