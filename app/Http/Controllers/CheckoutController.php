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
        // 1. VALIDASI DATA BACKEND (Mencegah submit kosong/bypass lokasi peta)
        $request->validate([
            'address_detail' => 'required|string',
            'shipping_cost'  => 'required|numeric',
            'grand_total'    => 'required|numeric',
            'latitude'       => 'required',
            'longitude'      => 'required',
        ]);

        // 2. AMBIL DATA DARI SESSION JIKA ADA, JIKA KOSONG AMBIL DARI DATABASE BERDASARKAN PROSES BELANJA
        $cart = $request->session()->get('cart', []);
        
        // Pengaman Cadangan: Jika session kosong (karena beli langsung), buat array dummy dari produk aktif demi kelancaran simulasi
        if (empty($cart)) {
            $activeCart = Cart::where('user_id', Auth::id())->get();
            if ($activeCart->isEmpty()) {
                return back()->with('error', 'Gagal memproses checkout: Keranjang belanja Anda terdeteksi kosong.');
            }
            foreach ($activeCart as $cItem) {
                $cart[$cItem->product_id] = [
                    'price' => $cItem->product->price,
                    'quantity' => $cItem->quantity ?? 1
                ];
            }
        }

        // Hitung total harga barang
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
                'address_detail'     => $request->address_detail,
                'latitude'           => $request->latitude,
                'longitude'          => $request->longitude,
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

            // Hapus data keranjang lama yang sudah terbeli
            $request->session()->forget('cart');
            Cart::where('user_id', Auth::id())->delete();
            
            DB::commit();

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
