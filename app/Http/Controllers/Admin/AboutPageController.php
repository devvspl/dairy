<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use Illuminate\Http\Request;

class AboutPageController extends Controller
{
    public function index()
    {
        $aboutPage = AboutPage::byKey('main')->first();
        
        if (!$aboutPage) {
            $aboutPage = AboutPage::create([
                'section_key' => 'main',
                'is_active' => true,
            ]);
        }
        
        return view('admin.about-page.index', compact('aboutPage'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'hero_title' => ['nullable', 'string'],
            'hero_description' => ['nullable', 'string'],
            'hero_image' => ['nullable', 'string'],
            'hero_image_file' => ['nullable', 'image', 'max:2048'],
            'hero_button_1_text' => ['nullable', 'string'],
            'hero_button_1_link' => ['nullable', 'string'],
            'hero_button_2_text' => ['nullable', 'string'],
            'hero_button_2_link' => ['nullable', 'string'],
            
            'overview_title' => ['nullable', 'string'],
            'overview_description' => ['nullable', 'string'],
            'overview_image' => ['nullable', 'string'],
            'overview_image_file' => ['nullable', 'image', 'max:2048'],
            'overview_badge_rating' => ['nullable', 'string'],
            'overview_badge_text' => ['nullable', 'string'],
            'overview_button_text' => ['nullable', 'string'],
            'overview_button_link' => ['nullable', 'string'],
            
            'why_promise_title' => ['nullable', 'string'],
            'why_promise_description' => ['nullable', 'string'],
            'why_promise_button_text' => ['nullable', 'string'],
            'why_promise_button_link' => ['nullable', 'string'],
            
            'contact_form_title' => ['nullable', 'string'],
            'contact_form_description' => ['nullable', 'string'],
            
            'team_image_files.*' => ['nullable', 'image', 'max:2048'],
            
            'is_active' => ['boolean'],
        ]);

        $aboutPage = AboutPage::byKey('main')->first();
        
        if (!$aboutPage) {
            $aboutPage = new AboutPage(['section_key' => 'main']);
        }

        $validated['is_active'] = $request->has('is_active');

        // Handle hero badges
        $heroBadges = [];
        if ($request->has('hero_badge_icons') && is_array($request->hero_badge_icons)) {
            foreach ($request->hero_badge_icons as $index => $icon) {
                if (!empty($icon) && !empty($request->hero_badge_texts[$index])) {
                    $heroBadges[] = [
                        'icon' => $icon,
                        'text' => $request->hero_badge_texts[$index],
                    ];
                }
            }
        }
        $validated['hero_badges'] = !empty($heroBadges) ? $heroBadges : null;

        // Handle overview checks
        $overviewChecks = [];
        if ($request->has('overview_check_icons') && is_array($request->overview_check_icons)) {
            foreach ($request->overview_check_icons as $index => $icon) {
                if (!empty($icon)) {
                    $overviewChecks[] = [
                        'icon' => $icon,
                        'title' => $request->overview_check_titles[$index] ?? '',
                        'description' => $request->overview_check_descriptions[$index] ?? '',
                    ];
                }
            }
        }
        $validated['overview_checks'] = !empty($overviewChecks) ? $overviewChecks : null;

        // Handle USPs
        $usps = [];
        if ($request->has('usp_icons') && is_array($request->usp_icons)) {
            foreach ($request->usp_icons as $index => $icon) {
                if (!empty($icon)) {
                    $usps[] = [
                        'icon' => $icon,
                        'title' => $request->usp_titles[$index] ?? '',
                        'description' => $request->usp_descriptions[$index] ?? '',
                    ];
                }
            }
        }
        $validated['usps'] = !empty($usps) ? $usps : null;

        // Handle Counters
        $counters = [];
        if ($request->has('counter_icons') && is_array($request->counter_icons)) {
            foreach ($request->counter_icons as $index => $icon) {
                if (!empty($icon)) {
                    $counters[] = [
                        'icon' => $icon,
                        'number' => $request->counter_numbers[$index] ?? '0',
                        'text' => $request->counter_texts[$index] ?? '',
                    ];
                }
            }
        }
        $validated['counters'] = !empty($counters) ? $counters : null;

        // Handle Why Items
        $whyItems = [];
        if ($request->has('why_titles') && is_array($request->why_titles)) {
            foreach ($request->why_titles as $index => $title) {
                if (!empty($title)) {
                    $whyItems[] = [
                        'title' => $title,
                        'description' => $request->why_descriptions[$index] ?? '',
                    ];
                }
            }
        }
        $validated['why_items'] = !empty($whyItems) ? $whyItems : null;

        // Handle Team Members with image uploads
        $teamMembers = [];
        $uploadPath = public_path('uploads/about/team');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        if ($request->has('team_names') && is_array($request->team_names)) {
            $teamImageFiles = $request->file('team_image_files') ?? [];
            
            foreach ($request->team_names as $index => $name) {
                if (!empty($name)) {
                    $imagePath = $request->team_images[$index] ?? '';
                    
                    // Handle file upload for this team member
                    if (isset($teamImageFiles[$index]) && $teamImageFiles[$index]) {
                        $file = $teamImageFiles[$index];
                        $filename = time() . '_team_' . $index . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
                        $file->move($uploadPath, $filename);
                        $imagePath = 'uploads/about/team/' . $filename;
                    }
                    
                    $teamMembers[] = [
                        'name' => $name,
                        'role' => $request->team_roles[$index] ?? '',
                        'image' => $imagePath,
                    ];
                }
            }
        }
        $validated['team_members'] = !empty($teamMembers) ? $teamMembers : null;

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

        // Handle hero image upload
        if ($request->hasFile('hero_image_file')) {
            $file = $request->file('hero_image_file');
            $filename = time() . '_hero_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            
            $uploadPath = public_path('uploads/about');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            if ($aboutPage->hero_image && file_exists(public_path($aboutPage->hero_image))) {
                @unlink(public_path($aboutPage->hero_image));
            }
            
            $file->move($uploadPath, $filename);
            $validated['hero_image'] = 'uploads/about/' . $filename;
        }

        // Handle overview image upload
        if ($request->hasFile('overview_image_file')) {
            $file = $request->file('overview_image_file');
            $filename = time() . '_overview_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            
            $uploadPath = public_path('uploads/about');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            if ($aboutPage->overview_image && file_exists(public_path($aboutPage->overview_image))) {
                @unlink(public_path($aboutPage->overview_image));
            }
            
            $file->move($uploadPath, $filename);
            $validated['overview_image'] = 'uploads/about/' . $filename;
        }

        $aboutPage->fill($validated);
        $aboutPage->save();

        return redirect()->route('admin.about-page.index')->with('success', 'About page updated successfully!');
    }
}
