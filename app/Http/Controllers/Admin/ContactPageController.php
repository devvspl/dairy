<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactPage;
use Illuminate\Http\Request;

class ContactPageController extends Controller
{
    public function index()
    {
        $contactPage = ContactPage::byKey('main')->first();
        
        if (!$contactPage) {
            $contactPage = ContactPage::create([
                'section_key' => 'main',
                'is_active' => true,
            ]);
        }
        
        return view('admin.contact-page.index', compact('contactPage'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_description' => ['nullable', 'string'],
            'hero_image' => ['nullable', 'string', 'max:255'],
            'hero_image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'hero_phone' => ['nullable', 'string', 'max:255'],
            'hero_email' => ['nullable', 'email', 'max:255'],
            
            'phone_title' => ['nullable', 'string', 'max:255'],
            'phone_description' => ['nullable', 'string'],
            'phone_number' => ['nullable', 'string', 'max:255'],
            
            'email_title' => ['nullable', 'string', 'max:255'],
            'email_description' => ['nullable', 'string'],
            'email_address' => ['nullable', 'email', 'max:255'],
            
            'address_title' => ['nullable', 'string', 'max:255'],
            'address_description' => ['nullable', 'string'],
            'address_full' => ['nullable', 'string'],
            
            'map_title' => ['nullable', 'string', 'max:255'],
            'map_embed_url' => ['nullable', 'string'],
            'map_link' => ['nullable', 'url', 'max:255'],
            
            'faq_questions' => ['nullable', 'array'],
            'faq_questions.*' => ['nullable', 'string'],
            'faq_answers' => ['nullable', 'array'],
            'faq_answers.*' => ['nullable', 'string'],
            
            'is_active' => ['boolean'],
        ]);

        $contactPage = ContactPage::byKey('main')->first();
        
        if (!$contactPage) {
            $contactPage = new ContactPage(['section_key' => 'main']);
        }

        $validated['is_active'] = $request->has('is_active');

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

        // Handle file upload
        if ($request->hasFile('hero_image_file')) {
            $file = $request->file('hero_image_file');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            
            $uploadPath = public_path('uploads/contact');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Delete old image if exists
            if ($contactPage->hero_image && file_exists(public_path($contactPage->hero_image))) {
                unlink(public_path($contactPage->hero_image));
            }
            
            $file->move($uploadPath, $filename);
            $validated['hero_image'] = 'uploads/contact/' . $filename;
        }

        $contactPage->fill($validated);
        $contactPage->save();

        return redirect()->route('admin.contact-page.index')->with('success', 'Contact page updated successfully!');
    }
}
