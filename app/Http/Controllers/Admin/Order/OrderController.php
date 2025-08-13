<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index()
    {
        $orders = Order::with(['user', 'orderItems.product'])->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show form for creating an order manually
     */
    public function create()
    {
        $products = Product::all();
        $users  = user::all();
        return view('admin.orders.create', compact('products','users'));
    }

    /**
     * Store a newly created order
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1'
        ]);

        // Create Order
        $order = Order::create([
            'customer_id' => $request->customer_id,
            'status' => 'pending',
            'total' => 0 // will update later
        ]);

        $total = 0;

        // Add Products to Order
        foreach ($request->products as $productData) {
            $product = Product::find($productData['id']);
            $price = $product->price;
            $subtotal = $price * $productData['quantity'];
            $total += $subtotal;

            $order->orderItems()->create([
                'product_id' => $product->id,
                'quantity' => $productData['quantity'],
                'price' => $price
            ]);
        }

        // Update total
        $order->update(['total' => $total]);

        return redirect()->route('admin.orders.index')->with('success', 'Order created successfully.');
    }

    /**
     * Show a specific order
     */
    public function show($id)
    {
        $order = Order::with(['customer', 'orderItems.product'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return back()->with('success', 'Order status updated successfully.');
    }
}
