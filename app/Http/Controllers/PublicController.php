<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use App\Models\Category;
use App\Models\Product;
use App\Models\Testimonial;
use App\Models\Blog;
use App\Models\WhyChooseUs;
use App\Models\Usp;
use App\Models\ContentSection;
use App\Models\Setting;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        $sliders = Slider::active()->get();
        $categories = Category::active()->get();
        $products = Product::featured()->get();
        $testimonials = Testimonial::active()->get();
        $blogs = Blog::featured()->limit(3)->get();
        $whyChooseUs = WhyChooseUs::active()->get();
        $usps = Usp::active()->get();
        
        $whyItWorks = ContentSection::active()->byKey('why_it_works')->first();
        $videoSection = ContentSection::active()->byKey('video_section')->first();
        $ctaSection = ContentSection::active()->byKey('cta_section')->first();

        return view('pages.home', compact(
            'sliders', 
            'categories', 
            'products', 
            'testimonials', 
            'blogs',
            'whyChooseUs',
            'usps',
            'whyItWorks',
            'videoSection',
            'ctaSection'
        ));
    }
    
    public function about()
    {
        return view('pages.about');
    }
    
    public function membership()
    {
        return view('pages.membership');
    }
    
    public function products()
    {
        return view('pages.products');
    }
    
    public function blogs()
    {
        return view('pages.blogs');
    }
    
    public function contact()
    {
        return view('pages.contact');
    }
}
