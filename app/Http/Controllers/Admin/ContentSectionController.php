<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentSection;
use Illuminate\Http\Request;

class ContentSectionController extends Controller
{
    public function index(Request $request)
    {
        $query = ContentSection::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q
                    ->where('section_key', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('kicker', 'like', "%{$search}%");
            });
        }

        $sections = $query->orderBy('section_key')->paginate(10);

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
            'image_upload' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
            'gallery_images_upload' => ['nullable', 'array', 'max:2'],
            'gallery_images_upload.*' => ['image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
            'gallery_images' => ['nullable', 'string'],
            'meta' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Convert JSON strings to arrays
        foreach (['points', 'buttons', 'gallery_images', 'meta'] as $field) {
            if (!empty($validated[$field])) {
                $decoded = json_decode($validated[$field], true);
                $validated[$field] = $decoded ?: null;
            } else {
                $validated[$field] = null;
            }
        }

        if ($request->hasFile('image_upload')) {
            $file = $request->file('image_upload');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('content_sections'), $filename);
            $validated['image'] = 'content_sections/' . $filename;
        }

        if ($request->hasFile('gallery_images_upload')) {
            $galleryPaths = [];
            foreach ($request->file('gallery_images_upload') as $file) {
                $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $file->move(public_path('content_sections'), $filename);
                $galleryPaths[] = 'content_sections/' . $filename;
            }
            $existing = is_array($validated['gallery_images']) ? $validated['gallery_images'] : [];
            $validated['gallery_images'] = array_slice(array_merge($existing, $galleryPaths), -2);
        }

        if (is_array($validated['gallery_images'])) {
            $validated['gallery_images'] = array_slice($validated['gallery_images'], 0, 2);
        }

        ContentSection::create($validated);

        return redirect()->route('admin.content-sections.index')->with('success', 'Content section created successfully!');
    }

    public function show(ContentSection $contentSection)
    {
        return view('admin.content-sections.show', compact('contentSection'));
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
            'image_upload' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
            'gallery_images_upload' => ['nullable', 'array', 'max:2'],
            'gallery_images_upload.*' => ['image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
            'gallery_images' => ['nullable', 'string'],
            'meta' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Convert JSON strings to arrays
        foreach (['points', 'buttons', 'gallery_images', 'meta'] as $field) {
            if (!empty($validated[$field])) {
                $decoded = json_decode($validated[$field], true);
                $validated[$field] = $decoded ?: null;
            } else {
                $validated[$field] = null;
            }
        }
        if ($request->hasFile('image_upload')) {
            $file = $request->file('image_upload');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('content_sections'), $filename);
            $validated['image'] = 'content_sections/' . $filename;
        }

        if ($request->hasFile('gallery_images_upload')) {
            $galleryPaths = [];
            foreach ($request->file('gallery_images_upload') as $file) {
                $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $file->move(public_path('content_sections'), $filename);
                $galleryPaths[] = 'content_sections/' . $filename;
            }
            $existing = is_array($validated['gallery_images']) ? $validated['gallery_images'] : [];
            $validated['gallery_images'] = array_slice(array_merge($existing, $galleryPaths), -2);
        }

        if (is_array($validated['gallery_images'])) {
            $validated['gallery_images'] = array_slice($validated['gallery_images'], 0, 2);
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
