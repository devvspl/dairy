<?php

namespace Database\Seeders;

use App\Models\AboutSection;
use Illuminate\Database\Seeder;

class AboutSectionSeeder extends Seeder
{
    public function run(): void
    {
        AboutSection::create([
            'kicker' => 'About Us',
            'title' => 'Clean, Honest Essentials — Made to Feel Premium',
            'description' => 'We believe in transparency and quality. Our products are crafted with care, using only the finest ingredients sourced from trusted suppliers. Every step of our process is designed to deliver excellence while maintaining our commitment to sustainability and ethical practices.',
            'image' => 'images/transport.png',
            'button_text' => 'Know More',
            'button_link' => '/about',
            'mini_items' => [
                [
                    'title' => 'Clean Standards',
                    'text' => 'Transparent sourcing & processes',
                ],
                [
                    'title' => 'Packaging',
                    'text' => 'Refined look & better protection',
                ],
            ],
            'badge_rating' => '4.8/5',
            'badge_text' => 'Average customer rating',
            'is_active' => true,
            'order' => 0,
        ]);
    }
}
