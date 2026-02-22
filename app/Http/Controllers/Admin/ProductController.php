<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'type']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('meta', 'like', "%{$search}%");
            });
        }

        if ($request->has('type') && $request->type) {
            $query->where('type_id', $request->type);
        }

        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        $products = $query->orderBy('order')->paginate(15);
        $types = Type::active()->get();
        $categories = Category::active()->get();

        return view('admin.products.index', compact('products', 'types', 'categories'));
    }

    public function create()
    {
        $types = Type::active()->get();
        $categories = Category::active()->get();
        return view('admin.products.create', compact('types', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug'],
            'sku' => ['nullable', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'mrp' => ['nullable', 'numeric', 'min:0'],
            'discount_percent' => ['nullable', 'integer', 'min:0', 'max:100'],
            'badge' => ['nullable', 'string', 'max:255'],
            'badge_color' => ['nullable', 'string', 'max:50'],
            'meta' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'type_id' => ['nullable', 'exists:types,id'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'reviews_count' => ['nullable', 'integer', 'min:0'],
            'stock_status' => ['nullable', 'string'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'shelf_life' => ['nullable', 'string', 'max:100'],
            'storage_temp' => ['nullable', 'string', 'max:100'],
            'best_for' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:255'],
            'images.*' => ['nullable', 'image', 'max:2048'],
            'order' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        // Handle multiple image uploads
        $uploadedImages = [];
        if ($request->hasFile('images')) {
            $uploadDir = public_path('uploads/products');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move($uploadDir, $filename);
                $uploadedImages[] = 'uploads/products/' . $filename;
            }
        }

        if (!empty($uploadedImages)) {
            $validated['images'] = $uploadedImages;
            if (empty($validated['image'])) {
                $validated['image'] = $uploadedImages[0];
            }
        }

        // Handle JSON fields
        $jsonFields = ['pack_sizes', 'delivery_slots', 'specifications', 'nutrition_info', 'storage_instructions', 'features', 'variants'];
        foreach ($jsonFields as $field) {
            if ($request->has($field)) {
                $value = $request->input($field);
                $validated[$field] = is_string($value) ? json_decode($value, true) : $value;
            }
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $types = Type::active()->get();
        $categories = Category::active()->get();
        return view('admin.products.edit', compact('product', 'types', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug,' . $product->id],
            'sku' => ['nullable', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'mrp' => ['nullable', 'numeric', 'min:0'],
            'discount_percent' => ['nullable', 'integer', 'min:0', 'max:100'],
            'badge' => ['nullable', 'string', 'max:255'],
            'badge_color' => ['nullable', 'string', 'max:50'],
            'meta' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'type_id' => ['nullable', 'exists:types,id'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'reviews_count' => ['nullable', 'integer', 'min:0'],
            'stock_status' => ['nullable', 'string'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'shelf_life' => ['nullable', 'string', 'max:100'],
            'storage_temp' => ['nullable', 'string', 'max:100'],
            'best_for' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:255'],
            'images.*' => ['nullable', 'image', 'max:2048'],
            'order' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        // Handle multiple image uploads
        $existingImages = $product->images ?? [];
        $uploadedImages = [];
        
        if ($request->hasFile('images')) {
            $uploadDir = public_path('uploads/products');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move($uploadDir, $filename);
                $uploadedImages[] = 'uploads/products/' . $filename;
            }
        }

        if (!empty($uploadedImages)) {
            $validated['images'] = array_merge($existingImages, $uploadedImages);
            if (empty($validated['image'])) {
                $validated['image'] = $validated['images'][0];
            }
        }

        // Handle JSON fields
        $jsonFields = ['pack_sizes', 'delivery_slots', 'specifications', 'nutrition_info', 'storage_instructions', 'features', 'variants'];
        foreach ($jsonFields as $field) {
            if ($request->has($field)) {
                $value = $request->input($field);
                $validated[$field] = is_string($value) ? json_decode($value, true) : $value;
            }
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }
}
