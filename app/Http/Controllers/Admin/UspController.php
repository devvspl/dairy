<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usp;
use Illuminate\Http\Request;

class UspController extends Controller
{
    public function index(Request $request)
    {
        $query = Usp::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $usps = $query->orderBy('order')->paginate(10);

        return view('admin.usps.index', compact('usps'));
    }

    public function create()
    {
        return view('admin.usps.create');
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

        Usp::create($validated);

        return redirect()->route('admin.usps.index')->with('success', 'USP created successfully!');
    }

    public function show(Usp $usp)
    {
        return view('admin.usps.show', compact('usp'));
    }

    public function edit(Usp $usp)
    {
        return view('admin.usps.edit', compact('usp'));
    }

    public function update(Request $request, Usp $usp)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'svg_path' => ['nullable', 'string'],
            'order' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        $usp->update($validated);

        return redirect()->route('admin.usps.index')->with('success', 'USP updated successfully!');
    }

    public function destroy(Usp $usp)
    {
        $usp->delete();

        return redirect()->route('admin.usps.index')->with('success', 'USP deleted successfully!');
    }
}
