<?php

namespace App\Http\Controllers;

use App\Models\Preference;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreferenceController extends Controller
{
    /**
     * Menampilkan Halaman Beranda dengan Logika Rekomendasi
     */
    public function index(Request $request)
    {
        // Mengambil kategori, membersihkan spasi, dan memastikan unik
        $categories = Product::distinct()->pluck('category')->map(function($item) {
            return trim($item);
        })->unique();

        $query = Product::with('images');
        $label = "Katalog Terbaru";

        if (Auth::check()) {
            /** @var \App\Models\User $user */ 
            // Baris di atas memberi tahu VS Code bahwa $user adalah model User
            $user = Auth::user();
            
            // Mengambil preferensi terbaru user
            $myInterests = $user->preferences()->pluck('category_name')->toArray();

            if (!empty($myInterests) && !$request->has('category')) {
                $query->whereIn('category', $myInterests);
                $label = "Rekomendasi Untuk Anda";
            }
        }

        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
            $label = "Kategori: " . $request->category;
        }

        $products = $query->latest()->get();

        return view('welcome', compact('products', 'categories', 'label'));
    }

    /**
     * Fitur Pencarian berdasarkan Nama, Kategori, dan Digitalisasi Budaya (KMS)
     */
    public function search(Request $request)
    {
        $queryText = trim($request->input('query'));

        if (empty($queryText)) {
            return view('products.search', ['products' => collect(), 'query' => $queryText]);
        }

        $products = Product::where(function($q) use ($queryText) {
                // Cari di tabel Produk
                $q->where('name', 'LIKE', "%{$queryText}%")
                  ->orWhere('category', 'LIKE', "%{$queryText}%")
                  ->orWhere('description', 'LIKE', "%{$queryText}%");
            })
            ->orWhereHas('knowledgeData', function($q) use ($queryText) {
                // Cari di tabel Digitalisasi Budaya (KMS)
                $q->where('title', 'LIKE', "%{$queryText}%")
                  ->orWhere('group_name', 'LIKE', "%{$queryText}%")
                  ->orWhere('description', 'LIKE', "%{$queryText}%");
            })
            ->with(['images', 'knowledgeData'])
            ->get();

        return view('products.search', ['products' => $products, 'query' => $queryText]);
    }

    /**
     * Menyimpan Hasil Pilihan dari Modal Pop-up
     */
    public function store(Request $request)
    {
        $request->validate([
            'interests' => 'required|array|min:1',
            'goal' => 'required'
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // LOGIKA REFRESH: Hapus semua preferensi lama sebelum simpan yang baru
        $user->preferences()->delete();

        foreach ($request->interests as $category) {
            Preference::create([
                'user_id' => $user->id,
                'category_name' => $category,
                'goal' => $request->goal 
            ]);
        }

        return redirect('/')->with('success', 'Preferensi Anda telah diperbarui!');
    }
}