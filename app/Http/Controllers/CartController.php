<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function addToCart(Request $request,int $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        // Membuat ID unik agar produk yang sama tapi beda warna tidak tertumpuk
        $cartKey = $id . '-' . $request->color . '-' . $request->size;

        if(isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $request->quantity;
        } else {
            $cart[$cartKey] = [
                "name" => $product->name,
                "quantity" => $request->quantity,
                "price" => $product->price,
                "color" => $request->color,
                "size" => $request->size,
                "image" => $product->images->first()->image ?? 'default.jpg'
            ];
        }

        session()->put('cart', $cart);

        // Jika klik "Beli Sekarang", arahkan ke Checkout
        if ($request->button_action == 'add_to_checkout') {
        // Langsung lempar ke halaman checkout dengan membawa ID produk ini saja
        return redirect()->route('checkout.index', ['selected_items' => [$cartKey]]);
    }

        // Jika user klik '+ Keranjang', tetap di halaman produk
        return redirect()->back()->with('success', 'Berhasil ditambah ke keranjang!');
        }

public function index()
{
    $cart = session()->get('cart', []);
    return view('cart.index', compact('cart'));
}

public function remove(int $key)
{
    $cart = session()->get('cart');

    if(isset($cart[$key])) {
        unset($cart[$key]);
        session()->put('cart', $cart);
    }

    return redirect()->back()->with('success', 'Produk berhasil dihapus!');
}
}