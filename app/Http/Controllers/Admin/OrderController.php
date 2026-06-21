<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // 1. Menampilkan semua daftar pesanan
    public function index()
    {
        $orders = Order::with('user')->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    // 2. Menampilkan detail pesanan (Alamat, Produk, Bukti Transfer)
    public function show(int $id)
    {
        // Eager loading: mengambil order, user, item, dan produk sekaligus
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
        
        $order = Order::with(['user', 'items.product.images'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    // 3. Mengupdate status pesanan (Logistik)
    public function updateStatus(Request $request,int $id)
    {
        $request->validate([
            'status' => 'required'
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan berhasil diperbarui!');
    }
}