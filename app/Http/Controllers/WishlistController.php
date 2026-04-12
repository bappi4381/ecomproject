<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = Wishlist::where('user_id', Auth::id())->with('product.images')->get();
        return view('frontend.pages.wishlist', compact('wishlistItems'));
    }

    public function add($productId)
    {
        if (!Auth::check()) {
            return redirect()->route('user.auth.login')->with('error', 'Please login to add items to wishlist.');
        }

        $exists = Wishlist::where('user_id', Auth::id())->where('product_id', $productId)->first();

        if (!$exists) {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $productId
            ]);
            return redirect()->back()->with('success', 'Product added to wishlist!');
        }

        return redirect()->back()->with('info', 'Product is already in your wishlist.');
    }

    public function remove($id)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())->findOrFail($id);
        $wishlist->delete();

        return redirect()->back()->with('success', 'Product removed from wishlist.');
    }
}
