<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\City;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;



class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $provinces = Province::all();
        $cities = City::all();

        $selectedItems = $request->query('selected_items', []);
        $checkoutItems = [];
        $totalHarga = 0;
        $totalWeight = 0;

        if (!empty($selectedItems)) {
            foreach ($selectedItems as $item) {
                $id = explode('-', $item)[0];
                $product = Product::with('images')->find($id);

                if ($product) {
                    $imgObj = $product->images->first();
                    $imageName = $imgObj ? $imgObj->image : 'default.jpg';

                    $checkoutItems[] = [
                        'id' => $product->id, // Tambahkan ID
                        'name' => $product->name,
                        'price' => $product->price,
                        'image' => $imageName,
                        'weight' => $product->weight ?? 250,
                        'quantity' => 1
                    ];

                    $totalHarga += $product->price;
                    $totalWeight += ($product->weight ?? 250);
                }
            }
        }

        $totalHarga = $totalHarga ?: 0;
        $totalWeight = $totalWeight ?: 1000;

        return view('checkout.index', compact(
            'provinces',
            'cities',
            'checkoutItems',
            'totalHarga',
            'totalWeight'
        ));
    }

 // Tambahkan import ini di bagian paling atas controller bersama use lainnya jika belum ada:
// use Illuminate\Support\Facades\Session;

public function process(Request $request)
{
    // PERBAIKAN BARIS 70: Menggunakan $request->session() atau Session::get()
    $cart = $request->session()->get('cart', []);
    if (empty($cart)) return back()->with('error', 'Keranjang kosong!');

    // Hitung total harga barang saja
    $totalPriceItems = 0;
    foreach ($cart as $item) {
        $totalPriceItems += ($item['price'] * $item['quantity']);
    }

    DB::beginTransaction();
    try {
        $order = Order::create([
            'user_id'            => Auth::id(),
            'invoice_number'     => 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
            'total_price_items'  => $totalPriceItems,
            'shipping_cost'      => $request->shipping_cost,
            'grand_total'        => $request->grand_total,
            'address_detail'     => $request->address_detail, // Dari input hidden
            'status'             => 'pending',
        ]);

        // Simpan rincian barang ke orders_item
        foreach ($cart as $key => $details) {
            $productId = explode('-', $key)[0];
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $productId,
                'quantity'   => $details['quantity'],
                'price'      => $details['price'],
            ]);
        }

        // PERBAIKAN BARIS 102: Menggunakan $request->session() atau Session::forget()
        $request->session()->forget('cart'); // Kosongkan keranjang
        DB::commit();

        return redirect()->route('order.payment', $order->id);

    } catch (\Exception $e) {
        DB::rollback();
        return back()->with('error', 'Gagal: ' . $e->getMessage());
    }
    
    // Catatan: Kode dd($request->all()) di bawah catch sebaiknya dihapus karena tidak akan pernah dieksekusi setelah perintah return
}
public function payment(int $id)
{
    $order = Order::findOrFail($id);
    
    // Keamanan: Pastikan hanya pemilik order yang bisa akses
    if ($order->user_id !== Auth::id()) {
        abort(403);
    }

    return view('checkout.payment', compact('order'));
}

public function uploadProof(Request $request,int $id)
{
    $request->validate([
        'payment_proof' => 'required|image|mimes:jpg,png,jpeg|max:2048',
    ]);

    $order = Order::findOrFail($id);

    if ($request->hasFile('payment_proof')) {
        // Simpan foto ke folder storage/app/public/payments
        $path = $request->file('payment_proof')->store('payments', 'public');
        
        // Update database: simpan path foto dan ubah status
        $order->update([
            'payment_proof' => $path,
            'status'        => 'waiting_confirmation' // Status berubah setelah upload
        ]);

        return redirect()->route('order.success', $order->id);
    }

    return back()->with('error', 'Gagal mengunggah gambar.');
}

public function success(int $id)
{
    $order = Order::findOrFail($id);
    return view('checkout.success', compact('order'));
}

public function history(Request $request)
{
    $currentStatus = $request->get('status', 'all'); // Ambil parameter status, default 'all'
    $user = Auth::user();

    // Query dasar: ambil pesanan milik user yang sedang login
    $query = Order::with(['items.product'])->where('user_id', $user->id);

    // Logika Filter Status
    if ($currentStatus !== 'all') {
        // Sesuaikan string status ('pending', 'dikemas', dll) dengan yang ada di database Anda
        $query->where('status', $currentStatus);
    }

    $orders = $query->latest()->get();

    return view('pesanansaya', compact('orders'));
}
public function show(int $id)
{
    // Ambil order berdasarkan ID, pastikan hanya milik user yang login
    $order = Order::with('items.product')->where('user_id', Auth::id())->findOrFail($id);

    return view('rinciansaya', compact('order'));
}
    public function getCities(int $provinceId)
    {
        $cities = City::where('province_id', $provinceId)->get();
        return response()->json($cities);
    }
public function getOngkirDistance(Request $request)
{
    try {
        // 1. DATA INPUT
        $distance = $request->query('distance'); 
        $weightInGram = $request->query('weight') ?? 1000;
        $weightKg = ceil($weightInGram / 1000); // Pembulatan ke atas (misal 1.2kg jadi 2kg)

        // 2. KONFIGURASI TARIF SERAGAM
        $pricePerKm = 200;   // Tarif Jarak
        $pricePerKg = 15000; // Tarif Berat 

        // 3. RUMUS TUNGGAL
        // Menghitung biaya berdasarkan jarak tempuh dan beban paket
        $totalOngkir = ($distance * $pricePerKm) + ($weightKg * $pricePerKg);

        // 4. BATAS MINIMAL (Safety Net)
        // Menjamin biaya operasional dasar (seperti packing) tertutup
        $totalOngkir = max($totalOngkir, 10000); 

        return response()->json([
            'status' => 'success',
            'cost' => round($totalOngkir),
            'details' => [
                'jarak' => round($distance, 2) . ' km',
                'berat' => $weightKg . ' kg'
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}
}