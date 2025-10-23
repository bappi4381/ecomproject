<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class FrontendArticleController extends Controller
{
    public function index(Request $request)
    {
        // Get only blog categories
        $categories = Category::where('type', 'blog')->get();

        // Article query
        $articles = Article::where('status', 'published')
            ->whereHas('category', function ($q) {
                $q->where('type', 'blog');
            });

        //Filter by category if selected
        if ($request->category) {
            $articles->where('category_id', $request->category);
        }

        // Filter by search title if requested
        if ($request->search) {
            $articles->where('title', 'like', '%'.$request->search.'%');
        }

        //Pagination
        $articles = $articles->latest()->paginate(9)->withQueryString();

        return view('frontend.pages.articles', compact('articles', 'categories'));
    }
    public function show($slug)
    {
        // Find the article by slug and ensure it's published
        $article = Article::where('slug', $slug)
                        ->where('status', 'published')
                        ->with('category')
                        ->firstOrFail();

        // Optional: get other articles for sidebar (exclude current article)
        $otherArticles = Article::where('status', 'published')
                                ->where('id', '!=', $article->id)
                                ->latest()
                                ->take(5)
                                ->get();

        return view('frontend.pages.singleArticle', compact('article', 'otherArticles'));
    }
}
