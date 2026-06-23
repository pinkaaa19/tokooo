<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FaqContent;
use App\Models\SearchLog;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function create()
    {
        // PERBAIKAN: Menggunakan 'FAQ' kapital sesuai nama folder fisik di views
        return view('admin.knowledge.FAQ.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer'   => 'required|string',
            'category' => 'nullable|array' // Validasi untuk checkbox
        ]);

        FaqContent::create([
            'question' => $request->question,
            'answer'   => $request->answer,
            // Ubah array kategori menjadi string koma-terpisah untuk disimpan di DB
            'category' => $request->has('category') ? implode(',', $request->category) : null,
        ]);

        return redirect()->route('admin.knowledge.index')->with('success', 'FAQ Produk Baru Berhasil Diterbitkan!');
    }

    public function edit(int $id)
    {
        $faq = FaqContent::findOrFail($id);
        // PERBAIKAN: Menggunakan 'FAQ' kapital sesuai nama folder fisik di views
        return view('admin.knowledge.FAQ.edit', compact('faq'));
    }

    public function update(Request $request, int $id)
    {
        $faq = FaqContent::findOrFail($id);

        $request->validate([
            'question' => 'required|string|max:255',
            'answer'   => 'required|string',
            'category' => 'nullable|array'
        ]);

        $faq->update([
            'question' => $request->question,
            'answer'   => $request->answer,
            'category' => $request->has('category') ? implode(',', $request->category) : null,
        ]);

        return redirect()->route('admin.knowledge.index')->with('success', 'FAQ Produk Berhasil Diperbarui!');
    }

    public function destroy(int $id)
    {
        $faq = FaqContent::findOrFail($id);
        $faq->delete();

        return redirect()->route('admin.knowledge.index')->with('success', 'FAQ Produk Berhasil Dihapus!');
    }

    public function show(int $id)
    {
        $faq = FaqContent::findOrFail($id);
        // PERBAIKAN: Menggunakan 'FAQ' kapital sesuai nama folder fisik di views
        return view('admin.knowledge.FAQ.show', compact('faq'));
    }

    public function searchFaq(Request $request)
    {
        $query = $request->input('query');
        
        // Validasi: Abaikan jika query terlalu pendek atau kosong
        if (empty($query) || strlen($query) < 2) {
            return view('faq.index', ['faqs' => FaqContent::all(), 'query' => $query]);
        }

        $faqs = FaqContent::where('question', 'LIKE', "%{$query}%")
                          ->orWhere('answer', 'LIKE', "%{$query}%")
                          ->get();
                          
        // Knowledge Gap Detection: Catat jika pencarian tidak membuahkan hasil
        if ($faqs->isEmpty()) {
            SearchLog::create([
                'keyword' => $query,
                'ip_address' => $request->ip(),
            ]);
        }

        return view('faq.index', compact('faqs', 'query'));
    }

    public function report() 
    {
        $faqs = FaqContent::withCount([
            'feedbacks as ya' => fn($q) => $q->where('is_helpful', 1),
            'feedbacks as tidak' => fn($q) => $q->where('is_helpful', 0)
        ])->get();

        // PERBAIKAN: Menggunakan 'FAQ' kapital sesuai nama folder fisik di views
        return view('admin.knowledge.FAQ.report', compact('faqs'));
    }
}
