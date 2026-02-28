<?php

namespace Database\Seeders;

use App\Models\SeoMeta;
use Illuminate\Database\Seeder;

class SeoMetaSeeder extends Seeder
{
    public function run(): void
    {
        $seoMetas = [
            [
                'page_url' => '/',
                'meta_title' => 'Premium Dairy Products - Fresh Milk & Dairy Delivered',
                'meta_description' => 'Discover premium quality dairy products delivered fresh to your door. Browse our selection of milk, cheese, yogurt, and more from trusted local farms.',
                'meta_keywords' => 'dairy products, fresh milk, cheese, yogurt, dairy delivery, organic dairy',
                'canonical_url' => url('/'),
                'robots' => 'index,follow',
            ],
            [
                'page_url' => '/about',
                'meta_title' => 'About Us - Our Story & Commitment to Quality Dairy',
                'meta_description' => 'Learn about our journey, values, and commitment to delivering the finest dairy products. Discover what makes us different and why customers trust us.',
                'meta_keywords' => 'about us, dairy company, quality dairy, our story, company values',
                'canonical_url' => url('/about'),
                'robots' => 'index,follow',
            ],
            [
                'page_url' => '/products',
                'meta_title' => 'Our Products - Fresh Dairy Products & Milk Varieties',
                'meta_description' => 'Explore our wide range of fresh dairy products including milk, cheese, butter, yogurt, and more. All sourced from trusted farms with quality guaranteed.',
                'meta_keywords' => 'dairy products, milk varieties, cheese types, butter, yogurt, dairy catalog',
                'canonical_url' => url('/products'),
                'robots' => 'index,follow',
            ],
            [
                'page_url' => '/contact',
                'meta_title' => 'Contact Us - Get in Touch for Dairy Product Inquiries',
                'meta_description' => 'Have questions about our dairy products or services? Contact us today. Our team is ready to help with orders, inquiries, and support.',
                'meta_keywords' => 'contact us, customer support, dairy inquiries, get in touch',
                'canonical_url' => url('/contact'),
                'robots' => 'index,follow',
            ],
            [
                'page_url' => '/membership',
                'meta_title' => 'Membership Plans - Exclusive Benefits & Dairy Subscriptions',
                'meta_description' => 'Join our membership program for exclusive benefits, discounts, and priority delivery. Choose from flexible plans designed for your dairy needs.',
                'meta_keywords' => 'membership, subscription, dairy subscription, membership benefits, exclusive offers',
                'canonical_url' => url('/membership'),
                'robots' => 'index,follow',
            ],
            [
                'page_url' => '/blogs',
                'meta_title' => 'Blog - Dairy Tips, Recipes & Industry News',
                'meta_description' => 'Read our latest articles about dairy products, healthy recipes, nutrition tips, and industry insights. Stay informed with our expert content.',
                'meta_keywords' => 'dairy blog, recipes, nutrition tips, dairy news, health articles',
                'canonical_url' => url('/blogs'),
                'robots' => 'index,follow',
            ],
        ];

        foreach ($seoMetas as $seoMeta) {
            SeoMeta::updateOrCreate(
                ['page_url' => $seoMeta['page_url']],
                $seoMeta
            );
        }
    }
}
