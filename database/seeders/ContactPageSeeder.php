<?php

namespace Database\Seeders;

use App\Models\ContactPage;
use Illuminate\Database\Seeder;

class ContactPageSeeder extends Seeder
{
    public function run(): void
    {
        ContactPage::create([
            'section_key' => 'main',
            'hero_title' => 'Let\'s talk—quick support, clear answers.',
            'hero_description' => 'Have a question about products, Share your details and we\'ll connect shortly.',
            'hero_image' => 'images/contact-us.webp',
            'hero_phone' => '+911234567890',
            'hero_email' => 'hello@example.com',
            
            'phone_title' => 'Call Us',
            'phone_description' => 'Talk to our support team for quick help and guidance.',
            'phone_number' => '+91 12345 67890',
            
            'email_title' => 'Email',
            'email_description' => 'Send your query and we\'ll reply with clear details.',
            'email_address' => 'hello@example.com',
            
            'address_title' => 'Visit',
            'address_description' => 'Office / Store address line will come here for users.',
            'address_full' => 'Delhi, India',
            
            'map_title' => 'Find us on map',
            'map_embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d224345.83945573586!2d77.0688980770208!3d28.527582004050263!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390ce2f8a6c5f0ff%3A0x2b3b0a5e3d2b8a1a!2sDelhi!5e0!3m2!1sen!2sin!4v1700000000000',
            'map_link' => 'https://maps.google.com',
            
            'faqs' => [
                [
                    'question' => 'How soon do you respond?',
                    'answer' => 'We usually respond within working hours. For urgent queries, calling is faster.',
                ],
                [
                    'question' => 'Can I get product guidance?',
                    'answer' => 'Yes—share your need and we\'ll suggest the right options.',
                ],
                [
                    'question' => 'Do you provide order support?',
                    'answer' => 'Yes—delivery, tracking, and post-purchase support is available.',
                ],
                [
                    'question' => 'What details should I share in message?',
                    'answer' => 'Mention your product/issue, preferred contact method, and best time to call.',
                ],
            ],
            
            'is_active' => true,
        ]);
    }
}
