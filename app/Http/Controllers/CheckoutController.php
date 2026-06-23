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
                        'id' => $product->id, 
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

public function process(Request $request)
    {
        // 1. Ambil array product_ids dari form input eksternal Blade
        $productIds = $request->input('product_ids', []);
        
        // Cadangan: Jika form kiriman kosong, paksa baca isi session cart
        if (empty($productIds)) {
            $cartBackup = $request->session()->get('cart', []);
            foreach ($cartBackup as $key => $details) {
                $productIds[] = explode('-', $key)[0];
            }
        }

        // 2. Validasi kelengkapan data form utama
        $request->validate([
            'address_detail' => 'required|string',
            'shipping_cost'  => 'required|numeric',
            'grand_total'    => 'required|numeric',
        ]);

        if (empty($productIds)) {
            return back()->with('error', 'Gagal memproses transaksi: Tidak ada produk yang terdeteksi untuk dicheckout.')->withInput();
        }

        DB::beginTransaction();
        try {
            // PENGAMAN NILAI DEFAULT: Mencegah error 'doesn't have a default value' di MySQL
            $shippingCost = $request->shipping_cost ?? 0;
            $grandTotal = $request->grand_total ?? 0;
            $totalPriceItems = $grandTotal - $shippingCost;

            if ($totalPriceItems <= 0) {
                $totalPriceItems = 0;
                foreach ($productIds as $pId) {
                    $prod = Product::find($pId);
                    if ($prod) $totalPriceItems += $prod->price;
                }
                $grandTotal = $totalPriceItems + $shippingCost;
            }

            // 3. Buat data transaksi utama di tabel orders
            $order = Order::create([
                'user_id'            => Auth::id(),
                'invoice_number'     => 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
                'total_price_items'  => $totalPriceItems,
                'shipping_cost'      => $shippingCost,
                'grand_total'        => $grandTotal,
                'address_detail'     => $request->address_detail ?? 'Alamat tidak terisi',
                'status'             => 'pending',
            ]);

            // 4. Simpan rincian barang menggunakan nama kolom tunggal database 'product_id'
            foreach ($productIds as $productId) {
                $product = Product::find($productId);
                if ($product) {
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $product->id, 
                        'quantity'   => 1,
                        'price'      => $product->price,
                    ]);
                    
                    // KODE DI SINI SUDAH DIBERSIHKAN: 
                    // Tidak ada lagi query DB::table('cart')->delete() yang memicu eror 1146!
                }
            }

            // 5. Hapus data session lama dan simpan perubahan permanen
            $request->session()->forget('cart');
            DB::commit();

            // Mengalihkan secara instan menuju halaman pembayaran bukti transfer
            return redirect()->route('order.payment', $order->id);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage())->withInput();
        }
    }

    public function payment(int $id)
    {
        $order = Order::findOrFail($id);
        
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('checkout.payment', compact('order'));
    }

    public function uploadProof(Request $request, int $id)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $order = Order::findOrFail($id);

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payments', 'public');
            
            $order->update([
                'payment_proof' => $path,
                'status'        => 'waiting_confirmation'
            ]);

            // Mengalihkan secara instan menuju halaman pembayaran bukti transfer yang sesuai dengan web.php
            return redirect()->route('checkout.payment.page', $order->id);
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
        $currentStatus = $request->get('status', 'all');
        $user = Auth::user();

        $query = Order::with(['items.product'])->where('user_id', $user->id);

        if ($currentStatus !== 'all') {
            $query->where('status', $currentStatus);
        }

        $orders = $query->latest()->get();

        return view('pesanansaya', compact('orders'));
    }

    public function show(int $id)
    {
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
            $distance = $request->query('distance'); 
            $weightInGram = $request->query('weight') ?? 1000;
            $weightKg = ceil($weightInGram / 1000);

            $pricePerKm = 200;   
            $pricePerKg = 15000; 

            $totalOngkir = ($distance * $pricePerKm) + ($weightKg * $pricePerKg);
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
