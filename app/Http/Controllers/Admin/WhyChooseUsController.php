<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhyChooseUs;
use Illuminate\Http\Request;

class WhyChooseUsController extends Controller
{
    public function index(Request $request)
    {
        $query = WhyChooseUs::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('order')->paginate(10);

        return view('admin.why-choose-us.index', compact('items'));
    }

    public function create()
    {
        return view('admin.why-choose-us.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'svg_path' => ['nullable', 'string'],
            'order' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        WhyChooseUs::create($validated);

        return redirect()->route('admin.whychooseus.index')->with('success', 'Item created successfully!');
    }

    public function show(WhyChooseUs $whychooseu)
    {
        return view('admin.why-choose-us.show', ['whyChooseUs' => $whychooseu]);
    }

    public function edit(WhyChooseUs $whychooseu)
    {
        return view('admin.why-choose-us.edit', ['whyChooseUs' => $whychooseu]);
    }

    public function update(Request $request, WhyChooseUs $whychooseu)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'svg_path' => ['nullable', 'string'],
            'order' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        $whychooseu->update($validated);

        return redirect()->route('admin.whychooseus.index')->with('success', 'Item updated successfully!');
    }

    public function destroy(WhyChooseUs $whychooseu)
    {
        $whychooseu->delete();

        return redirect()->route('admin.whychooseus.index')->with('success', 'Item deleted successfully!');
    }
}
