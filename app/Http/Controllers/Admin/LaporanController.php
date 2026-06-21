<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order; // Pastikan Model Order sudah ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
public function mingguan()
{
    // Kita ambil status yang sudah dianggap lunas/selesai
    // Sesuaikan string status di bawah ini dengan yang ada di database Anda
    $statusLunas = ['Selesai', 'success', 'settlement', 'Pesanan Dikirim'];

    $laporanMingguan = Order::whereIn('status', $statusLunas)
        ->select(
            DB::raw('YEAR(created_at) as tahun'),
            DB::raw('WEEK(created_at, 1) as minggu'),
            DB::raw('MIN(created_at) as tanggal_mulai'),
            DB::raw('COUNT(*) as total_transaksi'),
            DB::raw('SUM(grand_total) as total_pendapatan')
        )
        ->groupBy('tahun', 'minggu')
        ->orderBy('tahun', 'desc')
        ->orderBy('minggu', 'desc')
        ->get();

    return view('admin.laporan.mingguan', compact('laporanMingguan'));
}
}