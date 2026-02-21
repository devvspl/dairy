<?php

namespace Database\Seeders;

use App\Models\Slider;
use App\Models\Category;
use App\Models\Product;
use App\Models\Testimonial;
use App\Models\Blog;
use App\Models\WhyChooseUs;
use App\Models\Usp;
use App\Models\ContentSection;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class HomePageSeeder extends Seeder
{
    public function run(): void
    {
        // Sliders
        Slider::create([
            'kicker' => 'Organic • Fresh • Farm Direct',
            'title' => 'Pure Taste, Real Ingredients',
            'subtitle' => 'Premium essentials delivering purity, quality, and trust daily.',
            'button_text' => 'Shop Now',
            'button_link' => '/products',
            'link_text' => 'Explore Products',
            'link_url' => '/products',
            'image' => 'images/cow.png',
            'order' => 1,
            'is_active' => true,
        ]);

        Slider::create([
            'kicker' => 'Membership Benefits',
            'title' => 'Join & Save on Every Order',
            'subtitle' => 'Be first to explore new launches, exclusive deals, and seasonal bundles.',
            'button_text' => 'Join Membership',
            'button_link' => '/membership',
            'link_text' => 'Know More',
            'link_url' => '/membership',
            'image' => 'images/Banners-2.png',
            'order' => 2,
            'is_active' => true,
        ]);

        Slider::create([
            'kicker' => 'Farm Life Stories',
            'title' => 'From Soil to Shelf—Honestly',
            'subtitle' => 'Discover purity crafted with care and tradition.',
            'button_text' => 'Read Blogs',
            'button_link' => '/blogs',
            'link_text' => 'About Us',
            'link_url' => '/about',
            'image' => 'images/Banners-3.png',
            'order' => 3,
            'is_active' => true,
        ]);

        // Categories
        Category::create([
            'title' => 'New Launches',
            'icon_type' => 'svg',
            'svg_path' => '<path d="M10 26l22-12 22 12-22 12-22-12Z"/><path d="M10 26v22l22 12 22-12V26"/><path d="M44 10l4-4 4 4-4 4-4-4Z"/>',
            'bg_color' => 'green',
            'link' => '/products',
            'order' => 1,
            'is_active' => true,
        ]);

        Category::create([
            'title' => 'Membership Deals',
            'icon_type' => 'svg',
            'svg_path' => '<path d="M14 28h36v26H14V28Z"/><path d="M14 28h36v-8H14v8Z"/><path d="M32 20v34"/><path d="M14 36h36"/><path d="M26 20c-4 0-6-5-1-7 4-2 7 3 7 7"/><path d="M38 20c4 0 6-5 1-7-4-2-7 3-7 7"/>',
            'bg_color' => 'brown',
            'link' => '/membership',
            'order' => 2,
            'is_active' => true,
        ]);

        Category::create([
            'title' => 'Shop By Concern',
            'icon_type' => 'svg',
            'svg_path' => '<path d="M32 54s-18-10-22-24c-2-7 3-14 11-14 5 0 9 3 11 7 2-4 6-7 11-7 8 0 13 7 11 14-4 14-22 24-22 24Z"/>',
            'bg_color' => 'green',
            'link' => '/products',
            'order' => 3,
            'is_active' => true,
        ]);

        Category::create([
            'title' => 'Under ₹499',
            'icon_type' => 'price',
            'price_text' => 'Under<br>₹499',
            'bg_color' => 'brown',
            'link' => '/products',
            'order' => 4,
            'is_active' => true,
        ]);

        Category::create([
            'title' => 'All Products',
            'icon_type' => 'svg',
            'svg_path' => '<path d="M22 10h20M22 14h20"/><path d="M20 14h24v40a6 6 0 0 1-6 6H26a6 6 0 0 1-6-6V14Z"/><path d="M26 28h12"/>',
            'bg_color' => 'green',
            'link' => '/products',
            'order' => 5,
            'is_active' => true,
        ]);

        Category::create([
            'title' => 'Under ₹999',
            'icon_type' => 'price',
            'price_text' => 'Under<br>₹999',
            'bg_color' => 'brown',
            'link' => '/products',
            'order' => 6,
            'is_active' => true,
        ]);

        // Products
        Product::create([
            'name' => 'A2 Gir Cow Milk',
            'price' => 95,
            'badge' => 'Best Seller',
            'badge_color' => 'green',
            'meta' => 'Fresh • Farm sourced • A2',
            'rating' => 4.9,
            'reviews_count' => 1455,
            'variants' => ['1 L', '500 ml'],
            'image' => 'images/products-1.png',
            'order' => 1,
            'is_active' => true,
            'is_featured' => true,
        ]);

        Product::create([
            'name' => 'Farm Fresh Cow Milk',
            'price' => 80,
            'badge' => 'Popular',
            'badge_color' => 'dark',
            'meta' => 'Daily delivery • Pure & clean',
            'rating' => 4.8,
            'reviews_count' => 2217,
            'variants' => ['1 L', '500 ml'],
            'image' => 'images/products-2.png',
            'order' => 2,
            'is_active' => true,
            'is_featured' => true,
        ]);

        Product::create([
            'name' => 'Cow Milk Curd (Dahi)',
            'price' => 65,
            'badge' => 'Trending',
            'badge_color' => 'orange',
            'meta' => 'Thick set • No preservatives',
            'rating' => 4.8,
            'reviews_count' => 258,
            'variants' => ['500 g', '1 kg'],
            'image' => 'images/products-3.png',
            'order' => 3,
            'is_active' => true,
            'is_featured' => true,
        ]);

        Product::create([
            'name' => 'Cow Milk Paneer',
            'price' => 120,
            'badge' => 'Must Try',
            'badge_color' => 'purple',
            'meta' => 'Soft • High protein • Fresh',
            'rating' => 4.9,
            'reviews_count' => 167,
            'variants' => ['200 g', '500 g'],
            'image' => 'images/products-4.png',
            'order' => 4,
            'is_active' => true,
            'is_featured' => true,
        ]);

        // Testimonials
        Testimonial::create([
            'text' => 'The quality is exceptional. You can actually feel the difference in taste and freshness. It feels honest and clean.',
            'name' => 'Anita Sharma',
            'location' => 'Delhi',
            'avatar' => 'A',
            'is_featured' => false,
            'order' => 1,
            'is_active' => true,
        ]);

        Testimonial::create([
            'text' => 'We switched completely to these products for our home. The purity and consistency is what makes them stand out.',
            'name' => 'Rohit Mehta',
            'location' => 'Mumbai',
            'avatar' => 'R',
            'is_featured' => true,
            'order' => 2,
            'is_active' => true,
        ]);

        Testimonial::create([
            'text' => 'Packaging, quality, and delivery — everything feels premium. You know you are buying something genuinely good.',
            'name' => 'Pooja Verma',
            'location' => 'Bangalore',
            'avatar' => 'P',
            'is_featured' => false,
            'order' => 3,
            'is_active' => true,
        ]);

        // Blogs
        Blog::create([
            'title' => 'Why choosing organic daily essentials matters',
            'slug' => 'why-choosing-organic-daily-essentials-matters',
            'excerpt' => 'Understand how clean sourcing and simple ingredients impact everyday health.',
            'content' => 'Full blog content here...',
            'tag' => 'Clean Living',
            'image' => 'images/blog-1.png',
            'order' => 1,
            'is_active' => true,
            'is_featured' => true,
        ]);

        Blog::create([
            'title' => 'How transparent sourcing builds customer trust',
            'slug' => 'how-transparent-sourcing-builds-customer-trust',
            'excerpt' => 'Discover why clear supply chains help families choose better with confidence.',
            'content' => 'Full blog content here...',
            'tag' => 'Sourcing',
            'image' => 'images/blog-2.png',
            'order' => 2,
            'is_active' => true,
            'is_featured' => true,
        ]);

        Blog::create([
            'title' => 'Premium packaging: More than just good looks',
            'slug' => 'premium-packaging-more-than-just-good-looks',
            'excerpt' => 'Learn how better packaging protects freshness and quality.',
            'content' => 'Full blog content here...',
            'tag' => 'Wellness',
            'image' => 'images/blog-3.png',
            'order' => 3,
            'is_active' => true,
            'is_featured' => true,
        ]);

        // Why Choose Us
        WhyChooseUs::create([
            'title' => 'Quality Checked',
            'description' => 'Every batch goes through strict checks for freshness and consistency.',
            'svg_path' => '<path d="M32 10l18 8v14c0 12-8 20-18 22C22 52 14 44 14 32V18l18-8Z"/><path d="M22 32l6 6 14-14"/>',
            'order' => 1,
            'is_active' => true,
        ]);

        WhyChooseUs::create([
            'title' => 'Cleanness',
            'description' => 'No unnecessary additives—only what\'s needed for real taste and purity.',
            'svg_path' => '<path d="M50 14C24 16 14 32 14 44c0 6 4 10 10 10 12 0 30-10 26-40Z"/><path d="M22 44c10-2 18-10 22-22"/>',
            'order' => 2,
            'is_active' => true,
        ]);

        WhyChooseUs::create([
            'title' => 'Farm to Home',
            'description' => 'Sourced responsibly and delivered with care for everyday use.',
            'svg_path' => '<path d="M10 30L32 12l22 18"/><path d="M18 28v24h28V28"/><path d="M26 52V38h12v14"/>',
            'order' => 3,
            'is_active' => true,
        ]);

        WhyChooseUs::create([
            'title' => 'Transparent Process',
            'description' => 'Clear product info, real sourcing, and honest communication.',
            'svg_path' => '<path d="M14 16h36v26H26l-10 10V16Z"/><path d="M22 26h20"/><path d="M22 34h14"/>',
            'order' => 4,
            'is_active' => true,
        ]);

        // USPs
        Usp::create([
            'title' => 'Farm-Fresh Daily Collection',
            'description' => 'Freshly sourced every day for a naturally rich and pure taste.',
            'svg_path' => '<path d="M9 3h6v3l1.5 2V21H7.5V8L9 6V3Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M9 6h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M8 11h8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" opacity=".75"/>',
            'order' => 1,
            'is_active' => true,
        ]);

        Usp::create([
            'title' => '100% Pure & Adulteration-Free',
            'description' => 'No preservatives, no additives—only clean, honest dairy.',
            'svg_path' => '<path d="M12 3l8 4v6c0 5-3.5 8-8 8s-8-3-8-8V7l8-4Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M9.5 12l1.7 1.7L14.8 10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>',
            'order' => 2,
            'is_active' => true,
        ]);

        Usp::create([
            'title' => 'Ethical Cow Care',
            'description' => 'Healthy, well-cared cows with clean shelters and balanced feed.',
            'svg_path' => '<path d="M7 10c0-3 2-5 5-5h0c3 0 5 2 5 5v7c0 2-1.5 3-3.5 3h-3C8.5 20 7 19 7 17v-7Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M7 11c-1.2-.2-2.5-1.2-2.8-2.6C4 7 5 6 6.3 6.3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M17 11c1.2-.2 2.5-1.2 2.8-2.6C20 7 19 6 17.7 6.3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M10 13h0M14 13h0" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/><path d="M10 16c.8.8 3.2.8 4 0" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>',
            'order' => 3,
            'is_active' => true,
        ]);

        Usp::create([
            'title' => 'Hygienic Traditional Process',
            'description' => 'Time-tested methods with strict hygiene at every stage.',
            'svg_path' => '<path d="M12 3l1.2 4.2L17 8.5l-3.8 1.3L12 14l-1.2-4.2L7 8.5l3.8-1.3L12 3Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M6 14l.6 2.2L9 17l-2.4.8L6 20l-.6-2.2L3 17l2.4-.8L6 14Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" opacity=".8"/><path d="M18 14l.6 2.2L21 17l-2.4.8L18 20l-.6-2.2L15 17l2.4-.8L18 14Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" opacity=".8"/>',
            'order' => 4,
            'is_active' => true,
        ]);

        Usp::create([
            'title' => 'Quality Checks at Every Stage',
            'description' => 'From milking to packing—purity and quality are verified.',
            'svg_path' => '<path d="M8 6h13M8 12h13M8 18h13" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M3.5 6.2l1.2 1.2L6.8 5.3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M3.5 12.2l1.2 1.2 2.1-2.1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M3.5 18.2l1.2 1.2 2.1-2.1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>',
            'order' => 5,
            'is_active' => true,
        ]);

        Usp::create([
            'title' => 'Sustainable Farm Practices',
            'description' => 'Responsible farming that respects nature and community.',
            'svg_path' => '<path d="M20 4c-8 1-13 6-14 14 8-1 13-6 14-14Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M6 18c2-4 6-8 10-10" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>',
            'order' => 6,
            'is_active' => true,
        ]);

        // Content Sections
        ContentSection::create([
            'section_key' => 'why_it_works',
            'kicker' => 'Why It Works',
            'title' => 'Focused Range & Premium Experience',
            'description' => 'When choices are curated, decisions become easier. We keep standards high so customers get consistent quality, clean processes, and a premium feel—every single time.',
            'points' => [
                'Farm-fresh sourcing with clean, traceable handling',
                'Purity-first process with hygiene at every stage',
                'Consistent quality across batches—every single time',
                'Premium packing that keeps freshness locked in',
            ],
            'buttons' => [
                ['text' => 'Explore Membership', 'link' => '/membership', 'type' => 'primary'],
                ['text' => 'Contact Us', 'link' => '/contact', 'type' => 'outline'],
            ],
            'image' => 'images/milk-vans.webp',
            'meta' => [
                'rating' => '4.8',
                'rating_text' => 'Trusted by customers',
            ],
            'is_active' => true,
        ]);

        ContentSection::create([
            'section_key' => 'video_section',
            'title' => 'See Purity in Action',
            'description' => 'From farm to kitchen—watch how every product is crafted with care and transparency.',
            'video_id' => 'dQw4w9WgXcQ',
            'gallery_images' => [
                'images/galleries-1.png',
                'images/galleries-3.png',
            ],
            'meta' => [
                'video_duration' => '2 min video',
                'video_pill' => 'Clean • Simple • Trusted',
                'video_caption' => 'Crafted with care, built on trust.',
                'ghost_text' => 'OUR STORY',
            ],
            'is_active' => true,
        ]);

        ContentSection::create([
            'section_key' => 'cta_section',
            'kicker' => 'Ready to switch to clean food?',
            'title' => 'Bring Purity to Your Everyday Kitchen',
            'description' => 'Carefully sourced essentials that feel premium from the first use—trusted by families who value quality and honesty.',
            'points' => [
                'Clean ingredients & transparent sourcing',
                'Fresh batches, premium packaging',
                'Fast support & reliable delivery',
            ],
            'buttons' => [
                ['text' => 'Explore Products', 'link' => '/products', 'type' => 'primary'],
                ['text' => 'Membership', 'link' => '/membership', 'type' => 'secondary'],
            ],
            'meta' => [
                'rating' => '4.8/5',
                'rating_text' => 'average customer rating',
            ],
            'is_active' => true,
        ]);

        // Settings
        Setting::set('about_section_kicker', 'About Us', 'text', 'home');
        Setting::set('about_section_title', 'Clean, Honest Essentials — Made to Feel Premium', 'text', 'home');
        Setting::set('about_section_description', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'text', 'home');
        Setting::set('about_section_image', 'images/transport.png', 'text', 'home');
        Setting::set('about_section_rating', '4.8/5', 'text', 'home');
        Setting::set('about_section_rating_text', 'Average customer rating', 'text', 'home');
        Setting::set('about_section_mini_items', [
            ['title' => 'Clean Standards', 'text' => 'Transparent sourcing & processes'],
            ['title' => 'Packaging', 'text' => 'Refined look & better protection'],
        ], 'json', 'home');
    }
}
