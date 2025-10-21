<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $products = Product::with(['category', 'subcategory', 'images'])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhereHas('category', fn($q) => $q->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('subcategory', fn($q) => $q->where('name', 'like', "%{$search}%"));
            })
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('admin.product.index', compact('products'));
    }

    public function create(Request $request)
    {
        $categories = Category::all();
        $selectedCategory = $request->input('category_id');
        $subcategories = $selectedCategory
            ? Subcategory::where('category_id', $selectedCategory)->get()
            : collect();

        // Generate next product ID for display
        $latest = Product::latest('id')->first();
        $nextProductId = $latest ? intval(substr($latest->product_id ?? 'PROD-0000', 5)) + 1 : 1;
        $nextProductId = 'PROD-' . str_pad($nextProductId, 4, '0', STR_PAD_LEFT);

        return view('admin.product.create', compact('categories', 'subcategories', 'selectedCategory', 'nextProductId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string',
            'author'          => 'required|string',
            'publisher'       => 'nullable|string',
            'publishing_date' => 'required|date',
            'category_id'     => 'required|exists:categories,id',
            'subcategory_id'  => 'nullable|exists:subcategories,id',
            'description'     => 'nullable|string',
            'price'           => 'required|numeric|min:0',
            'discount'        => 'nullable|numeric|min:0|max:100',
            'stock'           => 'required|integer|min:0',
            'images.*'        => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($request) {
            $data = $request->only([
                'name', 'author', 'publisher', 'publishing_date', 
                'category_id', 'subcategory_id', 'description', 
                'price', 'discount', 'stock'
            ]);

            $data['discounted_price'] = $data['price'] - ($data['price'] * ($data['discount'] ?? 0)/100);

            $product = Product::create($data);

            // Save images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $path = $file->store('products', 'public');
                    $product->images()->create([
                        'image' => $path,
                    ]);
                }
            }
        });

        return redirect()->route('products.index')->with('success', 'Book created successfully.');
    }


    public function edit(Product $product)
    {
        $categories = Category::all();
        $subcategories = Subcategory::where('category_id', $product->category_id)->get();
        $product->load(['sizes', 'images']);


        return view('admin.product.create', compact('product', 'categories', 'subcategories'));
    }


    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'            => 'required|string',
            'author'          => 'required|string',
            'publisher'       => 'nullable|string',
            'publishing_date' => 'required|date',
            'category_id'     => 'required|exists:categories,id',
            'subcategory_id'  => 'nullable|exists:subcategories,id',
            'description'     => 'nullable|string',
            'price'           => 'required|numeric|min:0',
            'discount'        => 'nullable|numeric|min:0|max:100',
            'stock'           => 'required|integer|min:0',
            'images.*'        => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($request, $product) {
            $data = $request->only([
                'name', 'author', 'publisher', 'publishing_date', 
                'category_id', 'subcategory_id', 'description', 
                'price', 'discount', 'stock'
            ]);

            $data['discounted_price'] = $data['price'] - ($data['price'] * ($data['discount'] ?? 0)/100);

            $product->update($data);

            // Replace images if new ones uploaded
            if ($request->hasFile('images')) {
                // Delete old images
                foreach ($product->images as $img) {
                    Storage::disk('public')->delete($img->image);
                    $img->delete();
                }
                // Save new images
                foreach ($request->file('images') as $file) {
                    $path = $file->store('products', 'public');
                    $product->images()->create([
                        'image' => $path,
                    ]);
                }
            }
        });

        return redirect()->route('products.index')->with('success', 'Book updated successfully.');
    }

    public function destroy(Product $product)
    {
        DB::transaction(function () use ($product) {
            // Delete images
            foreach ($product->images as $img) {
                Storage::disk('public')->delete($img->image);
                $img->delete();
            }
            // Delete sizes & product
            $product->sizes()->delete();
            $product->delete();
        });

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
