<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Import Group: Controller Umum / Customer
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\ScrapingController;
use App\Http\Controllers\FaqFeedbackController;

// Import Group: Controller Admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\KnowledgeController as AdminKnowledge;
use App\Http\Controllers\Admin\ProductController as AdminProduct;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\MotifController;
use App\Http\Controllers\Admin\SopController;

// --- 1. RUTE PUBLIK / UMUM ---
Route::get('/', [PreferenceController::class, 'index'])->name('welcome');
Route::get('/koleksi', [ProductController::class, 'index']);
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/cari', [ProductController::class, 'search'])->name('products.search');
Route::get('/tentang', function () {
    return view('about');
})->name('about');

// --- 2. RUTE OTENTIKASI (LOGIN & REGISTER) ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// --- 3. RUTE KUSTOMER TERAUTENTIKASI (PROFIL, CART, PREFERENSI) ---
Route::middleware('auth')->group(function () {
    // Profil
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    
    // Simpan Preferensi (Sudah Unik)
    Route::post('/preferences/save', [PreferenceController::class, 'store'])->name('preferences.store');
    
    // Keranjang Belanja
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/remove/{key}', [CartController::class, 'remove'])->name('cart.remove');
});

// --- 4. RUTE CHECKOUT & PESANAN (PUBLIC/CUSTOMER) ---
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::get('/get-cities/{provinceId}', [CheckoutController::class, 'getCities']);
Route::get('/get-ongkir-distance', [CheckoutController::class, 'getOngkirDistance'])->name('ongkir.distance');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

// NAMA BARU AGAR TIDAK BENTROK GLOBAL
Route::get('/checkout/payment/{id}', [CheckoutController::class, 'payment'])->name('checkout.payment.page');
Route::get('/payment/{id}', [CheckoutController::class, 'payment'])->name('customer.order.payment.page'); 

// NAMA BARU AGAR TIDAK BENTROK GLOBAL
Route::post('/payment/{id}/upload', [CheckoutController::class, 'uploadProof'])->name('order.upload.proof');
Route::post('/order/upload/{id}', [CheckoutController::class, 'uploadProof'])->name('customer.order.upload.page');

Route::get('/order/success/{id}', [CheckoutController::class, 'success'])->name('order.success');
Route::get('/orders', [CheckoutController::class, 'history'])->name('orders.history');
Route::get('/orders/{id}', [CheckoutController::class, 'show'])->name('orders.show');

// --- 5. KATALOG & PREFERENSI COOKIE/SESSION ---
Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index');
// SOLUSI DUPLIKAT 'preferences.store': Diubah agar tidak bentrok dengan milik PreferenceController
Route::post('/preferences/store', [KatalogController::class, 'storePreference'])->name('katalog.preferences.store');

// --- 6. AREA DASHBOARD ADMIN (PREFIX & NAMESPACE SAFETY) ---
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Utama
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Manajemen Knowledge Base Global (KMS)
    Route::get('knowledge', [AdminKnowledge::class, 'index'])->name('knowledge.index');
    Route::get('knowledge/{id}/delete-image', [AdminKnowledge::class, 'deleteImage'])->name('knowledge.deleteImage');

    // Cluster KMS: MOTIF (Filosofi Budaya Toraja)
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

    // Cluster KMS: SOP
    Route::resource('sop', SopController::class)->names([
        'index'   => 'sop.index',
        'create'  => 'sop.create',
        'store'   => 'sop.store',
        'show'    => 'sop.show',
        'edit'    => 'sop.edit',
        'update'  => 'sop.update',
        'destroy' => 'sop.destroy',
    ]);

Route::resource('faq', FaqController::class)->names([
        'index'   => 'admin.faq.index',
        'create'  => 'admin.faq.create',
        'store'   => 'admin.faq.store',
        'show'    => 'admin.faq.show', 
        'edit'    => 'admin.faq.edit',
        'update'  => 'admin.faq.update',
        'destroy' => 'admin.faq.destroy',
    ]);

    // Manajemen Produk (Admin)
    Route::get('products/image/{id}/delete', [AdminProduct::class, 'deleteImage'])->name('products.deleteImage');
    Route::resource('products', AdminProduct::class);
    Route::delete('products/destroy-alt/{id}', [ProductController::class, 'destroy'])->name('products.destroy.alt');

    // Manajemen Pesanan & Laporan (Admin)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('/laporan/mingguan', [LaporanController::class, 'mingguan'])->name('laporan.mingguan');
});

// --- 7. UTALITAS DAN API FEEDBACK ---
Route::get('/scrape-ongkir', [ScrapingController::class, 'scrape']);
Route::post('/faq-feedback', [FaqFeedbackController::class, 'store'])->name('faq.feedback');
