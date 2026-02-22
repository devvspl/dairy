<?php

namespace Database\Seeders;

use App\Models\AboutPage;
use Illuminate\Database\Seeder;

class AboutPageSeeder extends Seeder
{
    public function run(): void
    {
        AboutPage::create([
            'section_key' => 'main',
            
            // Hero Section
            'hero_title' => 'Premium Quality. Clean Sourcing. Honest Process.',
            'hero_description' => 'We build everyday essentials with a refined, premium feel—clean ingredients.',
            'hero_image' => 'images/about-banner.webp',
            'hero_badges' => [
                ['icon' => 'fa-shield-heart', 'text' => 'Clean Standards'],
                ['icon' => 'fa-circle-check', 'text' => 'Batch Consistency'],
                ['icon' => 'fa-truck-fast', 'text' => 'Fast Dispatch'],
            ],
            'hero_button_1_text' => 'Explore Products',
            'hero_button_1_link' => '/products',
            'hero_button_2_text' => 'Talk to Us',
            'hero_button_2_link' => '/contact',
            
            // Overview Section
            'overview_title' => 'We focus on quality you can feel—everyday.',
            'overview_description' => 'Our approach is simple: choose better inputs, maintain clean processing standards, and deliver products that feel premium, consistent and trustworthy.',
            'overview_image' => 'images/overviews.png',
            'overview_badge_rating' => '4.8/5',
            'overview_badge_text' => 'Average customer rating',
            'overview_checks' => [
                [
                    'icon' => 'fa-magnifying-glass',
                    'title' => 'Ingredient-first selection',
                    'description' => 'We prioritize clean inputs and reliable sourcing standards.',
                ],
                [
                    'icon' => 'fa-layer-group',
                    'title' => 'Batch-level consistency',
                    'description' => 'Stable quality across batches—same experience, every time.',
                ],
                [
                    'icon' => 'fa-box',
                    'title' => 'Better packaging',
                    'description' => 'Designed to protect freshness and improve shelf stability.',
                ],
            ],
            'overview_button_text' => 'Contact Us',
            'overview_button_link' => '/contact',
            
            // USPs
            'usps' => [
                [
                    'icon' => 'fa-location-dot',
                    'title' => 'Transparent Sourcing',
                    'description' => 'Clear inputs and clear standards so you always know what you\'re buying.',
                ],
                [
                    'icon' => 'fa-certificate',
                    'title' => 'Consistency',
                    'description' => 'Quality that remains reliable across batches—taste, freshness and results.',
                ],
                [
                    'icon' => 'fa-sparkles',
                    'title' => 'Premium Feel',
                    'description' => 'From packaging to experience—everything is designed to feel refined.',
                ],
                [
                    'icon' => 'fa-headset',
                    'title' => 'Responsive Support',
                    'description' => 'Quick help, clear communication, and smooth purchase experience.',
                ],
            ],
            
            // Counters
            'counters' => [
                ['icon' => 'fa-users', 'number' => '50000', 'text' => 'Happy Customers'],
                ['icon' => 'fa-box-open', 'number' => '120', 'text' => 'Products'],
                ['icon' => 'fa-truck-fast', 'number' => '48', 'text' => 'Avg. Dispatch (hrs)'],
                ['icon' => 'fa-heart', 'number' => '98', 'text' => 'Repeat Customers (%)'],
            ],
            
            // Why Choose Us
            'why_items' => [
                [
                    'title' => 'Quality that stays consistent',
                    'description' => 'We keep standards stable across batches so results remain reliable.',
                ],
                [
                    'title' => 'Premium packaging & presentation',
                    'description' => 'Clean, premium packaging that protects freshness and feels refined.',
                ],
                [
                    'title' => 'Fast support',
                    'description' => 'Quick responses and clear communication—before and after purchase.',
                ],
            ],
            'why_promise_title' => 'Premium experience, every order.',
            'why_promise_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.',
            'why_promise_button_text' => 'Shop Now',
            'why_promise_button_link' => '/products',
            
            // Team Members
            'team_members' => [
                ['name' => 'Founder Name', 'role' => 'Leadership', 'image' => 'https://images.unsplash.com/photo-1607746882042-944635dfe10e?auto=format&fit=crop&w=900&q=75'],
                ['name' => 'Quality Head', 'role' => 'Quality & Process', 'image' => 'https://images.unsplash.com/photo-1599566150163-29194dcaad36?auto=format&fit=crop&w=900&q=75'],
                ['name' => 'Operations Lead', 'role' => 'Dispatch & Delivery', 'image' => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=900&q=75'],
                ['name' => 'Customer Lead', 'role' => 'Support & Experience', 'image' => 'https://images.unsplash.com/photo-1544723795-3fb6469f5b39?auto=format&fit=crop&w=900&q=75'],
            ],
            
            // FAQs
            'faqs' => [
                [
                    'question' => 'What makes your process "clean"?',
                    'answer' => 'We focus on clean sourcing, consistent checks, and careful handling so quality stays stable and trustworthy.',
                ],
                [
                    'question' => 'How do you ensure batch consistency?',
                    'answer' => 'Standardized inputs, controlled processing, and routine checks help keep the same experience across repeat orders.',
                ],
                [
                    'question' => 'Do you offer help choosing products?',
                    'answer' => 'Yes—share your requirement and our team can guide you to the right option based on your preference and use-case.',
                ],
                [
                    'question' => 'How do you handle packaging & freshness?',
                    'answer' => 'We use protective packaging designed to maintain freshness and shelf stability while keeping a premium unboxing feel.',
                ],
                [
                    'question' => 'What is your dispatch timeline?',
                    'answer' => 'Dispatch timelines depend on location and stock, but we aim for quick processing and clear updates on every order.',
                ],
                [
                    'question' => 'Can I track my order?',
                    'answer' => 'Yes. Once shipped, tracking details are shared so you can follow your order journey smoothly.',
                ],
                [
                    'question' => 'What if I need support after purchase?',
                    'answer' => 'Our team provides responsive support for queries, guidance, and resolution—before and after you receive the order.',
                ],
                [
                    'question' => 'Do you maintain transparent sourcing standards?',
                    'answer' => 'Yes, we keep sourcing and quality standards clear so customers always know what they\'re choosing.',
                ],
            ],
            
            // Contact Form
            'contact_form_title' => 'Want help choosing the right products?',
            'contact_form_description' => 'Share your details and our team will reach out shortly.',
            
            'is_active' => true,
        ]);
    }
}
