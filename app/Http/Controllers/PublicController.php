<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use App\Models\Category;
use App\Models\Type;
use App\Models\Product;
use App\Models\Testimonial;
use App\Models\Blog;
use App\Models\WhyChooseUs;
use App\Models\Usp;
use App\Models\ContentSection;
use App\Models\AboutSection;
use App\Models\ContactPage;
use App\Models\AboutPage;
use App\Models\LegalPage;
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
        $aboutSection = AboutSection::active()->orderBy('order')->first();
        
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
            'aboutSection',
            'whyItWorks',
            'videoSection',
            'ctaSection'
        ));
    }
    
    public function about()
    {
        $aboutPage = AboutPage::active()->byKey('main')->first();
        
        return view('pages.about', compact('aboutPage'));
    }
    
    public function membership()
    {
        return view('pages.membership');
    }
    
    public function products(Request $request)
    {
        $query = Product::active()->with(['category', 'type']);
        
        // Filter by type (using slug)
        if ($request->has('type') && $request->type) {
            $typeSlugs = explode(',', $request->type);
            $query->whereHas('type', function($q) use ($typeSlugs) {
                $q->whereIn('slug', $typeSlugs);
            });
        }
        
        // Filter by category
        if ($request->has('category') && $request->category) {
            $categoryIds = explode(',', $request->category);
            $query->whereIn('category_id', $categoryIds);
        }
        
        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Price range
        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Rating filter
        if ($request->has('min_rating') && $request->min_rating) {
            $query->where('rating', '>=', $request->min_rating);
        }
        
        // Sorting
        $sort = $request->get('sort', 'featured');
        switch ($sort) {
            case 'priceLow':
                $query->orderBy('price', 'asc');
                break;
            case 'priceHigh':
                $query->orderBy('price', 'desc');
                break;
            case 'ratingHigh':
                $query->orderBy('rating', 'desc');
                break;
            case 'nameAZ':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->orderBy('is_featured', 'desc')->orderBy('order');
        }
        
        $products = $query->paginate(12);
        $types = Type::active()->get();
        $categories = Category::active()->get();
        
        return view('pages.products', compact('products', 'types', 'categories'));
    }
    
    public function productDetail($slug)
    {
        $product = Product::active()->with('category')->where('slug', $slug)->firstOrFail();
        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();
        
        return view('pages.product-detail', compact('product', 'relatedProducts'));
    }

    public function searchProducts(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::active()
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('short_description', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('category', 'like', "%{$query}%");
            })
            ->limit(8)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'short_description' => $product->short_description ?? $product->meta,
                    'price' => $product->price,
                    'mrp' => $product->mrp,
                    'image' => asset($product->main_image),
                    'url' => route('product.detail', $product->slug),
                    'badge' => $product->badge,
                    'category' => $product->category,
                ];
            });

        return response()->json($products);
    }

    public function filterProducts(Request $request)
    {
        $query = Product::active()->with(['category', 'type']);
        
        // Filter by type (using slug)
        if ($request->has('type') && $request->type) {
            $typeSlugs = explode(',', $request->type);
            $query->whereHas('type', function($q) use ($typeSlugs) {
                $q->whereIn('slug', $typeSlugs);
            });
        }
        
        // Filter by category
        if ($request->has('category') && $request->category) {
            $categoryIds = explode(',', $request->category);
            $query->whereIn('category_id', $categoryIds);
        }
        
        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Price range
        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Rating filter
        if ($request->has('min_rating') && $request->min_rating) {
            $query->where('rating', '>=', $request->min_rating);
        }
        
        // Sorting
        $sort = $request->get('sort', 'featured');
        switch ($sort) {
            case 'priceLow':
                $query->orderBy('price', 'asc');
                break;
            case 'priceHigh':
                $query->orderBy('price', 'desc');
                break;
            case 'ratingHigh':
                $query->orderBy('rating', 'desc');
                break;
            case 'nameAZ':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->orderBy('is_featured', 'desc')->orderBy('order');
        }
        
        $products = $query->get()->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'short_description' => $product->short_description ?? $product->meta,
                'price' => $product->price,
                'mrp' => $product->mrp,
                'image' => asset($product->main_image),
                'url' => route('product.detail', $product->slug),
                'badge' => $product->badge,
                'badge_color' => $product->badge_color,
                'rating' => $product->rating,
                'category' => $product->category ? $product->category->title : null,
                'category_id' => $product->category_id,
                'type' => $product->type ? $product->type->name : null,
                'type_slug' => $product->type ? $product->type->slug : null,
                'type_id' => $product->type_id,
            ];
        });

        return response()->json([
            'products' => $products,
            'total' => $products->count(),
        ]);
    }
    
    public function blogs()
    {
        return view('pages.blogs');
    }
    
    public function contact()
    {
        $contactPage = ContactPage::active()->byKey('main')->first();
        
        return view('pages.contact', compact('contactPage'));
    }
    
    public function privacyPolicy()
    {
        $legalPage = LegalPage::active()->byKey('privacy-policy')->first();
        
        return view('pages.privacy-policy', compact('legalPage'));
    }
    
    public function termsConditions()
    {
        $legalPage = LegalPage::active()->byKey('terms-conditions')->first();
        
        return view('pages.terms-and-conditions', compact('legalPage'));
    }
}
