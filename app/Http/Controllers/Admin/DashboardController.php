<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\KnowledgeContent;
use App\Models\Order; // Pastikan Anda sudah membuat model Order
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
{
    // Mengambil total dari kolom grand_total di tabel orders
    $totalPendapatan = \App\Models\Order::sum('total_price_items'); 

    // Menghitung jumlah baris data di tabel orders
    $produkTerjual = \App\Models\Order::count();

    // Menghitung total motif di tabel knowledge_contents
    $totalKnowledge = \App\Models\KnowledgeContent::count();

    // Mengambil 5 data terakhir
    $pesananTerbaru = \App\Models\Order::with('user')->latest()->take(5)->get();

    return view('admin.dashboard', compact(
        'totalPendapatan', 
        'produkTerjual', 
        'totalKnowledge', 
        'pesananTerbaru'
    ));
}
}