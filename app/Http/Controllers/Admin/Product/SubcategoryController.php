<?php

namespace App\Http\Controllers\Admin\Product;
use App\Http\Controllers\Controller;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    // Store subcategory
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required',
        ]);

        Subcategory::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
        ]);

        return back()->with('success', 'Subcategory created successfully.');
    }

    // Delete subcategory
    public function destroy($id)
    {
        $subcategory = Subcategory::findOrFail($id);
        $subcategory->delete();

        return back()->with('success', 'Subcategory deleted successfully.');
    }
    /**
     * Get subcategories by category ID (for AJAX requests)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSubcategories(Request $request)
    {
        // Validate category ID
        $request->validate([
            'category_id' => 'required|exists:categories,id',
        ]);

        // Get subcategories
        $subcategories = Subcategory::where('category_id', $request->category_id)->get();

        // Return subcategories as JSON
        return response()->json($subcategories);
    }
}
