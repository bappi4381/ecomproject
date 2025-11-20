<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Mail\OrderConfirmation;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function checkoutIndex()
    {
        $cart = session()->get('cart', []);
        return view('frontend.pages.checkout', compact('cart'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'region' => 'nullable|string|max:100',
            'payment_method' => 'required|in:cod,online',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('books.index')->with('error', 'Cart is empty!');
        }

        $password = null;

        // Handle user
        if (!Auth::check()) {
            $password = uniqid('pass_');
            $user = User::firstOrCreate(
                ['email' => $request->email],
                [
                    'name' => $request->name,
                    'password' => Hash::make($password),
                    'phone' => $request->phone,
                    'address' => $request->shipping_address,
                ]
            );
            Auth::login($user);
        }

        $user = Auth::user();

        // Calculate totals
        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $deliveryCharge = str_contains(strtolower($request->city), 'dhaka') ? 80 : 110;
        $grandTotal = $subtotal + $deliveryCharge;

        // Create order + order items in a transaction
        $order = DB::transaction(function () use ($user, $cart, $request, $grandTotal, $deliveryCharge) {
            $order = Order::create([
                'user_id' => $user->id,
                'order_id' => 'ORD-' . now()->format('Ymd') . '-' . rand(1000, 9999),
                'total_price' => $grandTotal,
                'delivery_charge' => $deliveryCharge,
                'shipping_address' => $request->shipping_address,
                'city' => $request->city,
                'region' => $request->region,
                'status' => $request->payment_method === 'cod' ? 'pending' : 'processing',
            ]);

            // Batch insert order items
            $orderItems = [];
            foreach ($cart as $productId => $item) {
                $orderItems[] = [
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            OrderItem::insert($orderItems);

            return $order;
        });

        // Queue the order confirmation email
        $order->load('orderItems'); 
        Mail::to($user->email)->queue(new OrderConfirmation($order, $password, $request->payment_method));

        // Clear cart
        session()->forget('cart');

        return redirect()->route('orders.success', $order->id)
                         ->with('success', 'Order placed successfully! Check your email for confirmation.');
    }

    public function success(Order $order)
    {
        return view('frontend.pages.order_success', compact('order'));
    }
}
