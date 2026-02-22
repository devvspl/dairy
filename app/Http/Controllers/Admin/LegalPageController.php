<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LegalPage;
use Illuminate\Http\Request;

class LegalPageController extends Controller
{
    public function index($pageKey)
    {
        $legalPage = LegalPage::byKey($pageKey)->first();
        
        if (!$legalPage) {
            $legalPage = LegalPage::create([
                'page_key' => $pageKey,
                'title' => $pageKey === 'privacy-policy' ? 'Privacy Policy' : 'Terms & Conditions',
                'hero_description' => $pageKey === 'privacy-policy' 
                    ? 'We are committed to protecting your privacy. This Privacy Policy explains how we collect, use, and safeguard your personal information.'
                    : 'These Terms and Conditions govern how we manage user information and protect privacy on our website.',
                'content' => '<p>Content goes here...</p>',
                'last_updated' => date('F j, Y'),
                'is_active' => true,
            ]);
        }
        
        $pageTitle = $pageKey === 'privacy-policy' ? 'Privacy Policy' : 'Terms & Conditions';
        
        return view('admin.legal-pages.index', compact('legalPage', 'pageTitle', 'pageKey'));
    }

    public function update(Request $request, $pageKey)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'hero_description' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'last_updated' => ['nullable', 'string'],
            'contact_email' => ['nullable', 'email'],
            'contact_phone' => ['nullable', 'string'],
            'contact_address' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $legalPage = LegalPage::byKey($pageKey)->first();
        
        if (!$legalPage) {
            $legalPage = new LegalPage(['page_key' => $pageKey]);
        }

        $validated['is_active'] = $request->has('is_active');

        $legalPage->fill($validated);
        $legalPage->save();

        return redirect()->route('admin.legal-pages.index', $pageKey)->with('success', ucfirst(str_replace('-', ' ', $pageKey)) . ' updated successfully!');
    }
}
