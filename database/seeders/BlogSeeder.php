<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        // Remove existing blogs
        Blog::truncate();

        $blogs = [
            [
                'title' => 'From Soil to Shelf: The Honesty in Every Drop',
                'slug' => 'from-soil-to-shelf-honesty-in-every-drop',
                'excerpt' => 'Discover how our traditional farming practices ensure the highest quality A2 milk reaches your table without any processing.',
                'content' => '<p>At Nulac, we believe in complete transparency. From the moment our Gir cows graze on organic pastures to when the milk reaches your doorstep, every step is carefully monitored to maintain purity and quality.</p><p>Our traditional farming methods have been passed down through generations, ensuring that we never compromise on the natural goodness of our products.</p>',
                'tag' => 'Tradition',
                'image' => 'https://images.unsplash.com/photo-1500595046743-cd271d694d30?auto=format&fit=crop&q=80&w=800',
                'order' => 1,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'title' => 'Why A2 Gir Cow Milk is the Future of Wellness',
                'slug' => 'why-a2-gir-cow-milk-future-of-wellness',
                'excerpt' => "The science behind A2 beta-casein protein and why it's becoming the preferred choice for health-conscious families.",
                'content' => "<p>A2 milk contains only the A2 beta-casein protein, which is easier to digest and closer to human breast milk. Research shows that many people who experience discomfort with regular milk can tolerate A2 milk without issues.</p><p>Our Gir cows naturally produce 100% A2 milk, making it the perfect choice for your family's health and wellness.</p>",
                'tag' => 'Nutrition',
                'image' => 'https://images.unsplash.com/photo-1495570689269-d883b1224443?auto=format&fit=crop&q=80&w=800',
                'order' => 2,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'title' => 'A Day in the Life of Our Happy Gir Cows',
                'slug' => 'day-in-life-of-happy-gir-cows',
                'excerpt' => 'Take a peek into our pastures to see how we treat our herd with the respect and love they truly deserve.',
                'content' => '<p>Our Gir cows start their day with fresh organic feed and plenty of space to roam freely. We believe that happy cows produce better milk, which is why we prioritize their comfort and well-being above all else.</p><p>Each cow receives individual attention from our dedicated farm staff, ensuring they remain healthy and stress-free.</p>',
                'tag' => 'Behind the Scenes',
                'image' => 'https://images.unsplash.com/photo-1523348837708-15d4a09cfac2?auto=format&fit=crop&q=80&w=800',
                'order' => 3,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'title' => 'The Bilona Method: Making Liquid Gold',
                'slug' => 'bilona-method-making-liquid-gold',
                'excerpt' => 'How we hand-churn our Ghee to preserve the essential vitamins and that nostalgic, nutty aroma.',
                'content' => '<p>The traditional Bilona method involves hand-churning curd made from A2 milk to extract butter, which is then slowly heated to create pure ghee. This ancient technique preserves all the nutritional benefits and creates that distinctive aroma.</p><p>Unlike commercial ghee production, our Bilona ghee retains all the fat-soluble vitamins and beneficial fatty acids that make it a superfood.</p>',
                'tag' => 'Purity',
                'image' => 'https://images.unsplash.com/photo-1464226184884-fa280b87c399?auto=format&fit=crop&q=80&w=800',
                'order' => 4,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'title' => 'Our Zero-Waste Delivery Commitment',
                'slug' => 'zero-waste-delivery-commitment',
                'excerpt' => "Learn about our glass-bottle initiative and how we're reducing our carbon footprint one delivery at a time.",
                'content' => "<p>We've eliminated plastic from our delivery system by using reusable glass bottles. Customers return empty bottles during their next delivery, which we sanitize and reuse.</p><p>This initiative has helped us reduce plastic waste by over 10,000 bottles per month while maintaining the freshness and purity of our products.</p>",
                'tag' => 'Sustainability',
                'image' => 'https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?auto=format&fit=crop&q=80&w=800',
                'order' => 5,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'title' => 'Probiotics & Gut Health: The Power of Fresh Dahi',
                'slug' => 'probiotics-gut-health-power-of-fresh-dahi',
                'excerpt' => 'Why our thick-set, natural curd is a probiotic powerhouse for your daily digestive health.',
                'content' => '<p>Our fresh dahi is made using traditional cultures that contain billions of beneficial bacteria. These probiotics support digestive health, boost immunity, and improve nutrient absorption.</p><p>Unlike store-bought yogurt, our dahi is made fresh daily without any preservatives or artificial cultures.</p>',
                'tag' => 'Health',
                'image' => 'https://images.unsplash.com/photo-1523348837708-15d4a09cfac2?auto=format&fit=crop&q=80&w=800',
                'order' => 6,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'title' => '5 Protein-Packed Recipes with Fresh Paneer',
                'slug' => '5-protein-packed-recipes-fresh-paneer',
                'excerpt' => 'From salads to grills, explore creative ways to include our soft, farm-fresh paneer in your diet.',
                'content' => '<p>Our fresh paneer is a versatile ingredient packed with protein and calcium. Here are five delicious ways to incorporate it into your meals:</p><ul><li>Paneer Tikka Salad</li><li>Grilled Paneer Wraps</li><li>Paneer Bhurji</li><li>Paneer Butter Masala</li><li>Paneer Sandwich</li></ul>',
                'tag' => 'Recipes',
                'image' => 'https://images.unsplash.com/photo-1495570689269-d883b1224443?auto=format&fit=crop&q=80&w=800',
                'order' => 7,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'title' => 'Understanding the Gir Breed Heritage',
                'slug' => 'understanding-gir-breed-heritage',
                'excerpt' => 'A look into the history of the Gir cattle and their significance in Indian agricultural tradition.',
                'content' => "<p>The Gir breed originates from the Gir forests of Gujarat and is one of India's most prized indigenous cattle breeds. Known for their distinctive appearance and superior milk quality, Gir cows have been central to Indian agriculture for centuries.</p><p>These cows are naturally adapted to the Indian climate and produce milk with exceptional nutritional properties.</p>",
                'tag' => 'Heritage',
                'image' => 'https://images.unsplash.com/photo-1588710920403-d636ca920267?auto=format&fit=crop&q=80&w=800',
                'order' => 8,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'title' => 'Direct from Farmers: Empowering Local Villages',
                'slug' => 'direct-from-farmers-empowering-local-villages',
                'excerpt' => 'How your purchase helps support local farming communities and ensures fair wages for all.',
                'content' => '<p>By sourcing directly from local farmers, we ensure they receive fair compensation for their hard work. This direct relationship eliminates middlemen and creates sustainable livelihoods for rural communities.</p><p>Every purchase you make contributes to the economic development of these villages and helps preserve traditional farming practices.</p>',
                'tag' => 'Community',
                'image' => 'https://images.unsplash.com/photo-1464226184884-fa280b87c399?auto=format&fit=crop&q=80&w=800',
                'order' => 9,
                'is_active' => true,
                'is_featured' => false,
            ],
        ];

        foreach ($blogs as $blog) {
            Blog::create($blog);
        }
    }
}
