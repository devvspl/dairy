<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoMeta;
use Illuminate\Http\Request;

class SeoMetaController extends Controller
{
    public function index(Request $request)
    {
        $query = SeoMeta::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('page_url', 'like', "%{$search}%")
                  ->orWhere('meta_title', 'like', "%{$search}%");
            });
        }

        $seoMetas = $query->orderBy('page_url')->paginate(10);

        return view('admin.seo-metas.index', compact('seoMetas'));
    }

    public function create()
    {
        return view('admin.seo-metas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'page_url' => ['required', 'string', 'max:255', 'unique:seo_metas,page_url'],
            'meta_title' => ['required', 'string', 'max:255'],
            'meta_description' => ['required', 'string'],
            'meta_keywords' => ['nullable', 'string'],
            'canonical_url' => ['nullable', 'string', 'max:255', 'url'],
            'robots' => ['required', 'string', 'max:100'],
        ]);

        SeoMeta::create($validated);

        return redirect()->route('admin.seo-metas.index')->with('success', 'SEO Meta created successfully!');
    }

    public function show(SeoMeta $seoMeta)
    {
        return view('admin.seo-metas.show', compact('seoMeta'));
    }

    public function edit(SeoMeta $seoMeta)
    {
        return view('admin.seo-metas.edit', compact('seoMeta'));
    }

    public function update(Request $request, SeoMeta $seoMeta)
    {
        $validated = $request->validate([
            'page_url' => ['required', 'string', 'max:255', 'unique:seo_metas,page_url,' . $seoMeta->id],
            'meta_title' => ['required', 'string', 'max:255'],
            'meta_description' => ['required', 'string'],
            'meta_keywords' => ['nullable', 'string'],
            'canonical_url' => ['nullable', 'string', 'max:255', 'url'],
            'robots' => ['required', 'string', 'max:100'],
        ]);

        $seoMeta->update($validated);

        return redirect()->route('admin.seo-metas.index')->with('success', 'SEO Meta updated successfully!');
    }

    public function destroy(SeoMeta $seoMeta)
    {
        $seoMeta->delete();

        return redirect()->route('admin.seo-metas.index')->with('success', 'SEO Meta deleted successfully!');
    }
}
