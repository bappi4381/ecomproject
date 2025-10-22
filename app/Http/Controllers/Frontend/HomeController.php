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
        return view('frontend.pages.home');
    }
    public function books(Request $request)
    {
        $categories = Category::where('type', 'product')->get();

        $books = Product::query();

        if ($request->category) {
            $books->where('category_id', $request->category);
        }

        if ($request->min_price) {
            $books->where('price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $books->where('price', '<=', $request->max_price);
        }

        $books = $books->paginate(12);

        return view('frontend.pages.books', compact('categories', 'books'));
    }
    public function show($id)
    {
        $book = Product::with('images', 'category')->findOrFail($id);
        return view('frontend.pages.singleBook', compact('book'));
    }
}
