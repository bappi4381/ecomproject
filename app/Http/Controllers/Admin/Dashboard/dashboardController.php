<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index()
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalSubcategories = Subcategory::count();
        return view('admin.dashboard.index', compact('totalProducts', 'totalCategories', 'totalSubcategories'));
    }
}
