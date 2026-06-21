<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil kategori unik untuk navigasi menu utama
        $categories = Product::select('category')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category')
            ->map(fn($cat) => trim($cat))
            ->unique();

        $query = Product::with('images');
        $rekomendasiTitle = "Katalog Terbaru";
        $isPersonalized = false;

        // 2. LOGIKA: Jika user klik kategori manual
        if ($request->has('category') && $request->category != 'semua') {
            $query->where('category', trim($request->category));
            $rekomendasiTitle = "Koleksi " . $request->category;
            $products = $query->latest()->get();
        } 
        
        // 3. REKOMENDASI BERANDA UTAMA (Algoritma Jaccard Similarity)
        else {
            $allProducts = $query->latest()->get();

            if (Auth::check()) {
                // Ambil data preferensi berdasarkan user yang login
                $userPreference = DB::table('preferences')->where('user_id', Auth::id())->first();

                if ($userPreference && !empty($userPreference->category_name)) {
                    
                    // SOLUSI UTAMA: Bersihkan string teks murni dari database
                    $rawText = trim($userPreference->category_name);
                    
                    // Hilangkan sisa-sisa karakter jika ada format JSON yang rusak
                    $cleanText = str_replace(['[', ']', '"', "'"], '', $rawText);
                    
                    // Masukkan teks murni 'Pakaian' tadi ke dalam bungkus Array PHP
                    $userInterestsLower = [mb_strtolower(trim($cleanText))];

                    foreach ($allProducts as $product) {
                        // Bersihkan kategori produk dari database
                        $productCategoryClean = mb_strtolower(trim($product->category));
                        
                        // Cek kecocokan teks (Misal: apakah kata 'pakaian' ada di dalam kategori produk)
                        $isMatch = false;
                        if (!empty($userInterestsLower[0]) && (str_contains($productCategoryClean, $userInterestsLower[0]) || str_contains($userInterestsLower[0], $productCategoryClean))) {
                            $isMatch = true;
                        }

                        // Jika cocok diberi skor tertinggi 1.0 agar meloncat ke urutan paling atas
                        $jaccardScore = $isMatch ? 1.0 : 0.0;
                        
                        // Ikat nilai skor secara dinamis ke objek produk
                        $product->similarity_score = $jaccardScore;
                    }

                    // SORTING: Urutkan produk dari skor Jaccard tertinggi ke terendah
                    $allProducts = $allProducts->sortByDesc('similarity_score')->values();
                    
                    $rekomendasiTitle = "Rekomendasi Untuk Anda";
                    $isPersonalized = true;
                }
            }

            $products = $allProducts;
        }

        return view('welcome', compact('products', 'categories', 'rekomendasiTitle', 'isPersonalized'));
    }
// Masukkan fungsi ini di bagian bawah dalam class KatalogController Anda
    public function storePreference(Request $request)
    {
        // Ambil input interests dari kuesioner, jika kosong default array
        $interests = $request->input('interests', []);
        $goal = $request->input('goal');

        // Ambil pilihan pertama pembeli sebagai teks murni (Sesuai format data HeidiSQL Anda)
        $chosenCategory = count($interests) > 0 ? $interests[0] : null;

        if ($chosenCategory) {
            DB::table('preferences')->updateOrInsert(
                ['user_id' => Auth::id()],
                [
                    'category_name' => $chosenCategory, // Menyimpan teks murni seperti 'Pakaian'
                    'goal'          => $goal,
                    'updated_at'    => now()
                ]
            );
        }

        return redirect('/')->with('show_survey', false);
    }
    public function show(int $id) {
        $product = Product::with(['images', 'knowledgeData.relatedMotifs'])->findOrFail($id);
        return view('product.show', compact('product'));
    }
}