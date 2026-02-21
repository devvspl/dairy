<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContentSectionController extends Controller
{
    public function index(Request $request)
    {
        $query = ContentSection::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('section_key', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('kicker', 'like', "%{$search}%");
            });
        }

        $sections = $query->latest()->paginate(10);

        return view('admin.content-sections.index', compact('sections'));
    }

    public function create()
    {
        return view('admin.content-sections.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'section_key' => ['required', 'string', 'max:255', 'unique:content_sections'],
            'kicker' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'points' => ['nullable', 'string'],
            'buttons' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:255'],
            'video_id' => ['nullable', 'string', 'max:255'],
            'gallery_images' => ['nullable', 'string'],
            'meta' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->filled('points')) {
            $validated['points'] = json_decode($request->points, true);
        }
        if ($request->filled('buttons')) {
            $validated['buttons'] = json_decode($request->buttons, true);
        }
        if ($request->filled('gallery_images')) {
            $validated['gallery_images'] = json_decode($request->gallery_images, true);
        }
        if ($request->filled('meta')) {
            $validated['meta'] = json_decode($request->meta, true);
        }

        ContentSection::create($validated);

        return redirect()->route('admin.content-sections.index')->with('success', 'Content section created successfully!');
    }

    public function edit(ContentSection $contentSection)
    {
        return view('admin.content-sections.edit', compact('contentSection'));
    }

    public function update(Request $request, ContentSection $contentSection)
    {
        $validated = $request->validate([
            'section_key' => ['required', 'string', 'max:255', 'unique:content_sections,section_key,' . $contentSection->id],
            'kicker' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'points' => ['nullable', 'string'],
            'buttons' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:255'],
            'video_id' => ['nullable', 'string', 'max:255'],
            'gallery_images' => ['nullable', 'string'],
            'meta' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->filled('points')) {
            $validated['points'] = json_decode($request->points, true);
        }
        if ($request->filled('buttons')) {
            $validated['buttons'] = json_decode($request->buttons, true);
        }
        if ($request->filled('gallery_images')) {
            $validated['gallery_images'] = json_decode($request->gallery_images, true);
        }
        if ($request->filled('meta')) {
            $validated['meta'] = json_decode($request->meta, true);
        }

        $contentSection->update($validated);

        return redirect()->route('admin.content-sections.index')->with('success', 'Content section updated successfully!');
    }

    public function destroy(ContentSection $contentSection)
    {
        $contentSection->delete();

        return redirect()->route('admin.content-sections.index')->with('success', 'Content section deleted successfully!');
    }
}
