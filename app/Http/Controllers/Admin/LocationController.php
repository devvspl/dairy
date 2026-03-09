<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::latest()->paginate(15);
        
        $stats = [
            'total' => Location::count(),
            'active' => Location::active()->count(),
            'inactive' => Location::where('is_active', false)->count(),
        ];
        
        return view('admin.locations.index', compact('locations', 'stats'));
    }

    public function create()
    {
        return view('admin.locations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:locations,slug',
            'area' => 'nullable|string|max:255',
            'sector' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'banner_image' => 'nullable|string',
            'banner_image_file' => 'nullable|image|max:2048',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'building_name' => 'nullable|string|max:255',
            'building_type' => 'nullable|string|max:255',
            'delivery_timing' => 'nullable|string|max:255',
            'delivery_point' => 'nullable|string|max:255',
            'handling_info' => 'nullable|string',
            'address' => 'nullable|string',
            'map_embed_url' => 'nullable|string',
            'contact_phone' => 'nullable|string|max:20',
            'contact_whatsapp' => 'nullable|string|max:20',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'is_active' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);

        // Handle hero badges
        $heroBadges = [];
        if ($request->has('badge_icons') && is_array($request->badge_icons)) {
            foreach ($request->badge_icons as $index => $icon) {
                if (!empty($icon) && !empty($request->badge_texts[$index])) {
                    $heroBadges[] = [
                        'icon' => $icon,
                        'text' => $request->badge_texts[$index],
                    ];
                }
            }
        }
        $validated['hero_badges'] = !empty($heroBadges) ? $heroBadges : null;

        // Handle route steps
        $routeSteps = [];
        if ($request->has('step_numbers') && is_array($request->step_numbers)) {
            foreach ($request->step_numbers as $index => $number) {
                if (!empty($number)) {
                    $routeSteps[] = [
                        'number' => $number,
                        'title' => $request->step_titles[$index] ?? '',
                        'description' => $request->step_descriptions[$index] ?? '',
                    ];
                }
            }
        }
        $validated['route_steps'] = !empty($routeSteps) ? $routeSteps : null;

        // Handle highlights
        $highlights = [];
        if ($request->has('highlight_icons') && is_array($request->highlight_icons)) {
            foreach ($request->highlight_icons as $index => $icon) {
                if (!empty($icon)) {
                    $highlights[] = [
                        'icon' => $icon,
                        'title' => $request->highlight_titles[$index] ?? '',
                        'description' => $request->highlight_descriptions[$index] ?? '',
                    ];
                }
            }
        }
        $validated['highlights'] = !empty($highlights) ? $highlights : null;

        // Handle mini items
        $miniItems = [];
        if ($request->has('mini_titles') && is_array($request->mini_titles)) {
            foreach ($request->mini_titles as $index => $title) {
                if (!empty($title)) {
                    $miniItems[] = [
                        'title' => $title,
                        'description' => $request->mini_descriptions[$index] ?? '',
                    ];
                }
            }
        }
        $validated['mini_items'] = !empty($miniItems) ? $miniItems : null;

        // Handle guidelines
        $guidelines = [];
        if ($request->has('guideline_icons') && is_array($request->guideline_icons)) {
            foreach ($request->guideline_icons as $index => $icon) {
                if (!empty($icon)) {
                    $guidelines[] = [
                        'icon' => $icon,
                        'title' => $request->guideline_titles[$index] ?? '',
                        'description' => $request->guideline_descriptions[$index] ?? '',
                    ];
                }
            }
        }
        $validated['guidelines'] = !empty($guidelines) ? $guidelines : null;

        // Handle coverage areas
        $coverageAreas = [];
        if ($request->has('coverage_names') && is_array($request->coverage_names)) {
            foreach ($request->coverage_names as $index => $name) {
                if (!empty($name)) {
                    $coverageAreas[] = [
                        'name' => $name,
                        'details' => $request->coverage_details[$index] ?? '',
                    ];
                }
            }
        }
        $validated['coverage_areas'] = !empty($coverageAreas) ? $coverageAreas : null;

        // Handle FAQs
        $faqs = [];
        if ($request->has('faq_questions') && is_array($request->faq_questions)) {
            foreach ($request->faq_questions as $index => $question) {
                if (!empty($question) && !empty($request->faq_answers[$index])) {
                    $faqs[] = [
                        'question' => $question,
                        'answer' => $request->faq_answers[$index],
                    ];
                }
            }
        }
        $validated['faqs'] = !empty($faqs) ? $faqs : null;

        // Handle banner image upload
        if ($request->hasFile('banner_image_file')) {
            $file = $request->file('banner_image_file');
            $filename = time() . '_banner_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            
            $uploadPath = public_path('uploads/locations');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $file->move($uploadPath, $filename);
            $validated['banner_image'] = 'uploads/locations/' . $filename;
        }

        Location::create($validated);

        return redirect()->route('admin.locations.index')
            ->with('success', 'Location created successfully!');
    }

    public function edit(Location $location)
    {
        return view('admin.locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:locations,slug,' . $location->id,
            'area' => 'nullable|string|max:255',
            'sector' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'banner_image' => 'nullable|string',
            'banner_image_file' => 'nullable|image|max:2048',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'building_name' => 'nullable|string|max:255',
            'building_type' => 'nullable|string|max:255',
            'delivery_timing' => 'nullable|string|max:255',
            'delivery_point' => 'nullable|string|max:255',
            'handling_info' => 'nullable|string',
            'address' => 'nullable|string',
            'map_embed_url' => 'nullable|string',
            'contact_phone' => 'nullable|string|max:20',
            'contact_whatsapp' => 'nullable|string|max:20',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'is_active' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);

        // Handle hero badges
        $heroBadges = [];
        if ($request->has('badge_icons') && is_array($request->badge_icons)) {
            foreach ($request->badge_icons as $index => $icon) {
                if (!empty($icon) && !empty($request->badge_texts[$index])) {
                    $heroBadges[] = [
                        'icon' => $icon,
                        'text' => $request->badge_texts[$index],
                    ];
                }
            }
        }
        $validated['hero_badges'] = !empty($heroBadges) ? $heroBadges : null;

        // Handle route steps
        $routeSteps = [];
        if ($request->has('step_numbers') && is_array($request->step_numbers)) {
            foreach ($request->step_numbers as $index => $number) {
                if (!empty($number)) {
                    $routeSteps[] = [
                        'number' => $number,
                        'title' => $request->step_titles[$index] ?? '',
                        'description' => $request->step_descriptions[$index] ?? '',
                    ];
                }
            }
        }
        $validated['route_steps'] = !empty($routeSteps) ? $routeSteps : null;

        // Handle highlights
        $highlights = [];
        if ($request->has('highlight_icons') && is_array($request->highlight_icons)) {
            foreach ($request->highlight_icons as $index => $icon) {
                if (!empty($icon)) {
                    $highlights[] = [
                        'icon' => $icon,
                        'title' => $request->highlight_titles[$index] ?? '',
                        'description' => $request->highlight_descriptions[$index] ?? '',
                    ];
                }
            }
        }
        $validated['highlights'] = !empty($highlights) ? $highlights : null;

        // Handle mini items
        $miniItems = [];
        if ($request->has('mini_titles') && is_array($request->mini_titles)) {
            foreach ($request->mini_titles as $index => $title) {
                if (!empty($title)) {
                    $miniItems[] = [
                        'title' => $title,
                        'description' => $request->mini_descriptions[$index] ?? '',
                    ];
                }
            }
        }
        $validated['mini_items'] = !empty($miniItems) ? $miniItems : null;

        // Handle guidelines
        $guidelines = [];
        if ($request->has('guideline_icons') && is_array($request->guideline_icons)) {
            foreach ($request->guideline_icons as $index => $icon) {
                if (!empty($icon)) {
                    $guidelines[] = [
                        'icon' => $icon,
                        'title' => $request->guideline_titles[$index] ?? '',
                        'description' => $request->guideline_descriptions[$index] ?? '',
                    ];
                }
            }
        }
        $validated['guidelines'] = !empty($guidelines) ? $guidelines : null;

        // Handle coverage areas
        $coverageAreas = [];
        if ($request->has('coverage_names') && is_array($request->coverage_names)) {
            foreach ($request->coverage_names as $index => $name) {
                if (!empty($name)) {
                    $coverageAreas[] = [
                        'name' => $name,
                        'details' => $request->coverage_details[$index] ?? '',
                    ];
                }
            }
        }
        $validated['coverage_areas'] = !empty($coverageAreas) ? $coverageAreas : null;

        // Handle FAQs
        $faqs = [];
        if ($request->has('faq_questions') && is_array($request->faq_questions)) {
            foreach ($request->faq_questions as $index => $question) {
                if (!empty($question) && !empty($request->faq_answers[$index])) {
                    $faqs[] = [
                        'question' => $question,
                        'answer' => $request->faq_answers[$index],
                    ];
                }
            }
        }
        $validated['faqs'] = !empty($faqs) ? $faqs : null;

        // Handle banner image upload
        if ($request->hasFile('banner_image_file')) {
            $file = $request->file('banner_image_file');
            $filename = time() . '_banner_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            
            $uploadPath = public_path('uploads/locations');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            if ($location->banner_image && file_exists(public_path($location->banner_image))) {
                @unlink(public_path($location->banner_image));
            }
            
            $file->move($uploadPath, $filename);
            $validated['banner_image'] = 'uploads/locations/' . $filename;
        }

        $location->update($validated);

        return redirect()->route('admin.locations.index')
            ->with('success', 'Location updated successfully!');
    }

    public function destroy(Location $location)
    {
        if ($location->banner_image && file_exists(public_path($location->banner_image))) {
            @unlink(public_path($location->banner_image));
        }
        
        $location->delete();

        return redirect()->route('admin.locations.index')
            ->with('success', 'Location deleted successfully!');
    }
}
