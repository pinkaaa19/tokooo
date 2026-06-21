<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Mengambil semua produk dari database
    $products = Product::all(); 
    
    // Mengirim variabel $products ke halaman welcome
    return view('welcome', compact('products'));
});

use App\Http\Controllers\ProductController;

Route::get('/koleksi',[ProductController::class,'index']);

Route::get('/product/{id}',[ProductController::class,'show'])->name('product.show');

Route::get('/admin/products/create',[ProductController::class,'create'])->name('products.create');

Route::post('/admin/products/store',[ProductController::class,'store'])->name('products.store');

Route::get('/cari', [ProductController::class, 'search'])->name('products.search');


use App\Http\Controllers\AuthController;

Route::get('/login',[AuthController::class,'showLogin'])->name('login');

Route::post('/login',[AuthController::class,'login']);

Route::get('/register',[AuthController::class,'showRegister'])->name('register');

Route::post('/register',[AuthController::class,'register']);

use Illuminate\Support\Facades\Auth;

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

Route::view('/orders','orders');

use App\Http\Controllers\ProfileController;
Route::middleware('auth')->group(function () {
Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

use App\Http\Controllers\CartController;

Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/remove/{key}', [CartController::class, 'remove'])->name('cart.remove');

});

use App\Http\Controllers\CheckoutController;

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::get('/get-cities/{provinceId}', [CheckoutController::class, 'getCities']);
Route::get('/get-ongkir-distance', [CheckoutController::class, 'getOngkirDistance'])->name('ongkir.distance');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/payment/{id}', [CheckoutController::class, 'payment'])->name('order.payment');
// Halaman Instruksi Pembayaran
Route::get('/payment/{id}', [CheckoutController::class, 'payment'])->name('order.payment');
Route::post('/payment/{id}/upload', [CheckoutController::class, 'uploadProof'])->name('order.upload');
// Route untuk memproses upload bukti pembayaran
Route::post('/order/upload/{id}', [CheckoutController::class, 'uploadProof'])->name('order.upload');
// Route untuk halaman sukses (setelah upload)
Route::get('/order/success/{id}', [CheckoutController::class, 'success'])->name('order.success');
Route::get('/orders', [CheckoutController::class, 'history'])->name('orders.history');
Route::get('/orders/{id}', [CheckoutController::class, 'show'])->name('orders.show');

use App\Http\Controllers\KatalogController;

Route::get('/', [KatalogController::class, 'index'])->name('home');

Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index');
Route::post('/preferences/store', [KatalogController::class, 'storePreference'])->name('preferences.store');
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\KnowledgeController as AdminKnowledge;
use App\Http\Controllers\Admin\KnowledgeController;
use App\Http\Controllers\Admin\ProductController as AdminProduct;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\MotifController;
use App\Http\Controllers\Admin\SopController;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // --- Dashboard ---
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // --- Manajemen Knowledge (KMS) ---
    // PENTING: Rute custom diletakkan SEBELUM resource
    Route::get('knowledge/{id}/delete-image', [AdminKnowledge::class, 'deleteImage'])
         ->name('knowledge.deleteImage');
// --- GRUP ROUTE MANAGEMENT KMS ALDI ART (DIPISAH 3 FOLDER) ---

// 1. Rute Khusus Global Index (Untuk menampilkan halaman utama Knowledge Base Anda)
Route::get('knowledge', [AdminKnowledge::class, 'index'])->name('knowledge.index');

// 2. Cluster Rute Khusus Folder: MOTIF (Filosofi Budaya)
Route::get('motif/{id}/delete-image', [MotifController::class, 'deleteImage'])->name('motif.deleteImage');
Route::resource('motif', MotifController::class)->names([
    'index'   => 'motif.index',
    'create'  => 'motif.create',
    'store'   => 'motif.store',
    'show'    => 'motif.show',
    'edit'    => 'motif.edit',
    'update'  => 'motif.update',
    'destroy' => 'motif.destroy',
]);

// 3. Cluster Rute Khusus Folder: SOP (Standar Operasional Prosedur)
Route::resource('sop', SopController::class)->names([
    'index'   => 'sop.index',
    'create'  => 'sop.create',
    'store'   => 'sop.store',
    'show'    => 'sop.show',
    'edit'    => 'sop.edit',
    'update'  => 'sop.update',
    'destroy' => 'sop.destroy',
]);

// 4. Cluster Rute Khusus Folder: FAQ (Tanya Jawab Karakteristik Produk)
Route::resource('faq', FaqController::class)->names([
    'index'   => 'faq.index',
    'create'  => 'faq.create',
    'store'   => 'faq.store',
    'show'    => 'faq.show',
    'edit'    => 'faq.edit',
    'update'  => 'faq.update',
    'destroy' => 'faq.destroy',
]);

    // --- Manajemen Produk ---
    // PENTING: Rute custom diletakkan SEBELUM resource
    Route::get('products/image/{id}/delete', [AdminProduct::class, 'deleteImage'])
         ->name('products.deleteImage');
    Route::resource('products', AdminProduct::class);
Route::delete('/admin/products/{id}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
    // --- Manajemen Pesanan (Orders) ---
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    Route::get('/laporan/mingguan', [LaporanController::class, 'mingguan'])->name('laporan.mingguan');
    
});

use App\Http\Controllers\PreferenceController;

// Ganti route '/' yang lama dengan ini
Route::get('/', [PreferenceController::class, 'index'])->name('welcome');

// Tambahkan route untuk simpan preferensi
Route::middleware(['auth'])->group(function () {
    Route::post('/preferences/save', [PreferenceController::class, 'store'])->name('preferences.store');
});

// Halaman Tentang (About)
Route::get('/tentang', function () {
    return view('about');
})->name('about');

use App\Http\Controllers\ScrapingController;

Route::get('/scrape-ongkir', [ScrapingController::class, 'scrape']);

Route::post('/faq-feedback', [\App\Http\Controllers\FaqFeedbackController::class, 'store'])->name('faq.feedback');