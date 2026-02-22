<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutSection;
use Illuminate\Http\Request;

class AboutSectionController extends Controller
{
    public function index(Request $request)
    {
        $query = AboutSection::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('kicker', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $aboutSections = $query->orderBy('order')->paginate(10);

        return view('admin.about-sections.index', compact('aboutSections'));
    }

    public function create()
    {
        return view('admin.about-sections.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kicker' => ['nullable', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'button_text' => ['nullable', 'string', 'max:255'],
            'button_link' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:255'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'badge_rating' => ['nullable', 'string', 'max:255'],
            'badge_text' => ['nullable', 'string', 'max:255'],
            'mini_item_1_title' => ['nullable', 'string', 'max:255'],
            'mini_item_1_text' => ['nullable', 'string', 'max:255'],
            'mini_item_2_title' => ['nullable', 'string', 'max:255'],
            'mini_item_2_text' => ['nullable', 'string', 'max:255'],
            'order' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Handle mini items
        $miniItems = [];
        if ($request->mini_item_1_title || $request->mini_item_1_text) {
            $miniItems[] = [
                'title' => $request->mini_item_1_title,
                'text' => $request->mini_item_1_text,
            ];
        }
        if ($request->mini_item_2_title || $request->mini_item_2_text) {
            $miniItems[] = [
                'title' => $request->mini_item_2_title,
                'text' => $request->mini_item_2_text,
            ];
        }
        $validated['mini_items'] = !empty($miniItems) ? $miniItems : null;

        // Handle file upload
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            
            $uploadPath = public_path('uploads/about-sections');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $file->move($uploadPath, $filename);
            $validated['image'] = 'uploads/about-sections/' . $filename;
        }

        AboutSection::create($validated);

        return redirect()->route('admin.about-sections.index')->with('success', 'About section created successfully!');
    }

    public function show(AboutSection $aboutSection)
    {
        return view('admin.about-sections.show', compact('aboutSection'));
    }

    public function edit(AboutSection $aboutSection)
    {
        return view('admin.about-sections.edit', compact('aboutSection'));
    }

    public function update(Request $request, AboutSection $aboutSection)
    {
        $validated = $request->validate([
            'kicker' => ['nullable', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'button_text' => ['nullable', 'string', 'max:255'],
            'button_link' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:255'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'badge_rating' => ['nullable', 'string', 'max:255'],
            'badge_text' => ['nullable', 'string', 'max:255'],
            'mini_item_1_title' => ['nullable', 'string', 'max:255'],
            'mini_item_1_text' => ['nullable', 'string', 'max:255'],
            'mini_item_2_title' => ['nullable', 'string', 'max:255'],
            'mini_item_2_text' => ['nullable', 'string', 'max:255'],
            'order' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Handle mini items
        $miniItems = [];
        if ($request->mini_item_1_title || $request->mini_item_1_text) {
            $miniItems[] = [
                'title' => $request->mini_item_1_title,
                'text' => $request->mini_item_1_text,
            ];
        }
        if ($request->mini_item_2_title || $request->mini_item_2_text) {
            $miniItems[] = [
                'title' => $request->mini_item_2_title,
                'text' => $request->mini_item_2_text,
            ];
        }
        $validated['mini_items'] = !empty($miniItems) ? $miniItems : null;

        // Handle file upload
        if ($request->hasFile('image_file')) {
            // Delete old image if exists
            if ($aboutSection->image && file_exists(public_path($aboutSection->image))) {
                unlink(public_path($aboutSection->image));
            }
            
            $file = $request->file('image_file');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            
            $uploadPath = public_path('uploads/about-sections');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $file->move($uploadPath, $filename);
            $validated['image'] = 'uploads/about-sections/' . $filename;
        }

        $aboutSection->update($validated);

        return redirect()->route('admin.about-sections.index')->with('success', 'About section updated successfully!');
    }

    public function destroy(AboutSection $aboutSection)
    {
        // Delete image if exists
        if ($aboutSection->image && file_exists(public_path($aboutSection->image))) {
            unlink(public_path($aboutSection->image));
        }

        $aboutSection->delete();

        return redirect()->route('admin.about-sections.index')->with('success', 'About section deleted successfully!');
    }
}
