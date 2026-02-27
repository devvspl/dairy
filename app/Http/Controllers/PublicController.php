<?php

namespace App\Http\Controllers;

use App\Mail\ContactInquiryMail;
use App\Models\AboutPage;
use App\Models\AboutSection;
use App\Models\Blog;
use App\Models\Category;
use App\Models\ContactPage;
use App\Models\ContentSection;
use App\Models\LegalPage;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\Testimonial;
use App\Models\Type;
use App\Models\Usp;
use App\Models\WhyChooseUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
        $plans = \App\Models\MembershipPlan::active()->orderBy('order')->get();
        $benefits = \App\Models\MembershipBenefit::active()->orderBy('order')->get();
        $steps = \App\Models\MembershipStep::active()->orderBy('order')->get();
        $faqs = \App\Models\MembershipFaq::active()->orderBy('order')->get();

        return view('pages.membership', compact('plans', 'benefits', 'steps', 'faqs'));
    }

    public function products(Request $request)
    {
        $query = Product::active()->with(['category', 'type']);

        // Filter by type (using slug)
        if ($request->has('type') && $request->type) {
            $typeSlugs = explode(',', $request->type);
            $query->whereHas('type', function ($q) use ($typeSlugs) {
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
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
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
            ->where(function ($q) use ($query) {
                $q
                    ->where('name', 'like', "%{$query}%")
                    ->orWhere('short_description', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('category', 'like', "%{$query}%");
            })
            ->limit(8)
            ->get()
            ->map(function ($product) {
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
            $query->whereHas('type', function ($q) use ($typeSlugs) {
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
            $query->where(function ($q) use ($search) {
                $q
                    ->where('name', 'like', "%{$search}%");
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

        $products = $query->get()->map(function ($product) {
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
        $blogs = Blog::active()->orderBy('order')->paginate(9);

        return view('pages.blogs', compact('blogs'));
    }

    public function blogDetail($slug)
    {
        $blog = Blog::active()->where('slug', $slug)->firstOrFail();
        $relatedBlogs = Blog::active()
            ->where('id', '!=', $blog->id)
            ->orderBy('order')
            ->limit(3)
            ->get();

        return view('pages.blog-detail', compact('blog', 'relatedBlogs'));
    }

    public function contact()
    {
        $contactPage = ContactPage::active()->byKey('main')->first();

        return view('pages.contact', compact('contactPage'));
    }

    public function submitContactInquiry(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'plan_id' => 'nullable|exists:membership_plans,id',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        $inquiry = \App\Models\ContactInquiry::create($validated);

        // Send confirmation email to customer
        try {
            Mail::to($inquiry->email)->send(new ContactInquiryMail($inquiry, false));
        } catch (\Exception $e) {
            // optionally handle error
        }

        // Send notification email to admin
        $adminEmail = env('MAIL_ADMIN_ADDRESS', env('MAIL_FROM_ADDRESS'));

        if ($adminEmail) {
            try {
                Mail::to($adminEmail)->send(new ContactInquiryMail($inquiry, true));
            } catch (\Exception $e) {
                // optionally handle error
            }
        }

        // Check if it's an AJAX request
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you for contacting us! We will get back to you shortly.'
            ]);
        }

        return redirect()->back()->with(
            'success',
            'Thank you for contacting us! We will get back to you shortly.'
        );
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
