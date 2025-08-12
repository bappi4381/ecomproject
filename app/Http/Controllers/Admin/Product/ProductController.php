<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSize;
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

        $products = Product::with(['category', 'subcategory', 'sizes'])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('subcategory', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->paginate(10)
            ->appends(['search' => $search]); // Keep search term in pagination links

        return view('admin.product.index', compact('products'));
    }

    public function create(Request $request)
    {
        
        $categories = Category::all();

        // Get selected category id from request to filter subcategories
        $selectedCategory = $request->input('category_id');

        // Get subcategories based on selected category or empty collection
        $subcategories = $selectedCategory
            ? Subcategory::where('category_id', $selectedCategory)->get()
            : collect();

        return view('admin.product.create', compact('categories', 'subcategories', 'selectedCategory'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'image' => 'nullable|image|max:2048',
            'sizes' => 'required|array|min:1',
            'sizes.*.size' => 'required|string',
            'sizes.*.stock' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $data = $request->only(['name', 'category_id', 'subcategory_id', 'description', 'price', 'discount']);

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('products', 'public');
            }

            $product = Product::create($data);

            foreach ($request->sizes as $sizeData) {
                $product->sizes()->create([
                    'size' => $sizeData['size'],
                    'stock' => $sizeData['stock'],
                ]);
            }
        });

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $subcategories = Subcategory::where('category_id', $product->category_id)->get();
        $product->load('sizes');
        return view('admin.product.create', compact('product', 'categories', 'subcategories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'image' => 'nullable|image|max:2048',
            'sizes' => 'required|array|min:1',
            'sizes.*.size' => 'required|string',
            'sizes.*.stock' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request, $product) {
            $data = $request->only(['name', 'category_id', 'subcategory_id', 'description', 'price', 'discount']);

            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $data['image'] = $request->file('image')->store('products', 'public');
            }

            $product->update($data);

            $product->sizes()->delete();

            foreach ($request->sizes as $sizeData) {
                $product->sizes()->create([
                    'size' => $sizeData['size'],
                    'stock' => $sizeData['stock'],
                ]);
            }
        });

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->sizes()->delete();
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
