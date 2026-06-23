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
        // PERBAIKAN: Mengarah langsung ke admin/faq/create
        return view('admin.faq.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer'   => 'required|string',
            'category' => 'nullable|array' 
        ]);

        FaqContent::create([
            'question' => $request->question,
            'answer'   => $request->answer,
            'category' => $request->has('category') ? implode(',', $request->category) : null,
        ]);

        return redirect()->route('admin.knowledge.index')->with('success', 'FAQ Produk Baru Berhasil Diterbitkan!');
    }

    public function edit(int $id)
    {
        $faq = FaqContent::findOrFail($id);
        // PERBAIKAN: Mengarah langsung ke admin/faq/edit
        return view('admin.faq.edit', compact('faq'));
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
        // PERBAIKAN: Mengarah langsung ke admin/faq/show
        return view('admin.faq.show', compact('faq'));
    }

    public function searchFaq(Request $request)
    {
        $query = $request->input('query');
        
        if (empty($query) || strlen($query) < 2) {
            return view('faq.index', ['faqs' => FaqContent::all(), 'query' => $query]);
        }

        $faqs = FaqContent::where('question', 'LIKE', "%{$query}%")
                          ->orWhere('answer', 'LIKE', "%{$query}%")
                          ->get();
                          
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

        // PERBAIKAN: Mengarah langsung ke admin/faq/report
        return view('admin.faq.report', compact('faqs'));
    }
}
