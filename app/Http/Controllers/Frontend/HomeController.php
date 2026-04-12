<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get latest 4 non-discounted products
        $products = Product::where(function ($query) {
                $query->where('discount', 0)
                    ->orWhereNull('discount');
            })
            ->with('images')
            ->latest()
            ->take(4)
            ->get();

        // Get latest 4 discounted products
        $discountedProducts = Product::where('discount', '>', 0)->with('images')->latest()->take(4)->get();

        // Get latest 3 articles
        $articles = \App\Models\Article::where('status', 'published')->latest()->take(3)->get();

        return view('frontend.pages.home', compact('products', 'discountedProducts', 'articles'));
    }
    public function books(Request $request)
    {
        $categories = Category::where('type', 'product')->get();

        $books = Product::query();

        // Search
        if ($request->search) {
            $books->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('author', 'like', '%' . $request->search . '%');
        }

        if ($request->category) {
            $books->where('category_id', $request->category);
        }

        if ($request->min_price) {
            $books->where('price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $books->where('price', '<=', $request->max_price);
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $books->orderBy('price', 'asc');
                break;
            case 'price_high':
                $books->orderBy('price', 'desc');
                break;
            case 'newest':
            default:
                $books->latest();
                break;
        }

        $books = $books->paginate(12);

        return view('frontend.pages.books', compact('categories', 'books'));
    }
    public function show($id)
    {
        $book = Product::with('images', 'category')->findOrFail($id);
        
        // Related products from the same category
        $relatedBooks = Product::where('category_id', $book->category_id)
            ->where('id', '!=', $id)
            ->with('images')
            ->take(4)
            ->get();

        return view('frontend.pages.singleBook', compact('book', 'relatedBooks'));
    }
    
}
