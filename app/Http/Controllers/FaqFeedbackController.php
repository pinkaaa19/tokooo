<?php

namespace App\Http\Controllers;

use App\Models\FaqFeedback;
use Illuminate\Http\Request;

class FaqFeedbackController extends Controller {
public function store(Request $request) {
    // Cek apakah data masuk dengan dd($request->all()); jika masih gagal
    
    $validated = $request->validate([
        'faq_id' => 'required|integer',
        'is_helpful' => 'required|boolean',
    ]);

    \App\Models\FaqFeedback::create([
        'faq_content_id' => $validated['faq_id'], // Pastikan nama key sesuai
        'is_helpful'     => $validated['is_helpful'],
        'ip_address'     => $request->ip(),
    ]);

    return response()->json(['message' => 'Terima kasih atas masukannya!']);
}
}
