<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipFaq;
use Illuminate\Http\Request;

class MembershipFaqController extends Controller
{
    public function index()
    {
        $faqs = MembershipFaq::orderBy('order')->get();
        return view('admin.membership-faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.membership-faqs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        MembershipFaq::create($validated);

        return redirect()->route('admin.membership-faqs.index')
            ->with('success', 'FAQ created successfully.');
    }

    public function edit($id)
    {
        $faq = MembershipFaq::findOrFail($id);
        return view('admin.membership-faqs.edit', compact('faq'));
    }

    public function show($id)
    {
        $faq = MembershipFaq::findOrFail($id);
        return view('admin.membership-faqs.show', compact('faq'));
    }

    public function update(Request $request, $id)
    {
        $faq = MembershipFaq::findOrFail($id);

        $validated = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $faq->update($validated);

        return redirect()->route('admin.membership-faqs.index')
            ->with('success', 'FAQ updated successfully.');
    }

    public function destroy($id)
    {
        $faq = MembershipFaq::findOrFail($id);
        $faq->delete();

        return redirect()->route('admin.membership-faqs.index')
            ->with('success', 'FAQ deleted successfully.');
    }
}
