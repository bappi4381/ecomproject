<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
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

        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $deliveryCharge = str_contains(strtolower($request->city), 'dhaka') ? 80 : 110;
        $grandTotal = $subtotal + $deliveryCharge;

        $order = DB::transaction(function () use ($user, $cart, $request, $subtotal, $deliveryCharge, $grandTotal) {
            $order = Order::create([
                'user_id' => $user->id,
                'order_id' => 'ORD-' . now()->format('Ymd') . '-' . rand(1000, 9999), // Generate unique order ID
                'total_price' => $grandTotal,
                'delivery_charge' => $deliveryCharge,
                'shipping_address' => $request->shipping_address,
                'city' => $request->city,
                'region' => $request->region,
                'status' => $request->payment_method === 'cod' ? 'pending' : 'processing',
            ]);

            foreach ($cart as $productId => $item) {
                $order->orderItems()->create([
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }

            return $order;
        });

        // Send order confirmation email AFTER transaction
        Mail::send('emails.order_confirmation', [
            'name' => $user->name,
            'order_id' => $order->order_id,
            'total' => $grandTotal,
            'delivery_charge' => $order->delivery_charge, // <-- add this
            'password' => $password,
            'payment_method' => $request->payment_method,
        ], function ($message) use ($user) {
            $message->to($user->email, $user->name)
                    ->subject('Order Confirmation - BookSaw');
        });

        session()->forget('cart');

        return redirect()->route('orders.success', $order->id)->with('success', 'Order placed successfully! Check your email for confirmation.');
    }


    public function success(Order $order)
    {
        return view('frontend.pages.order_success', compact('order'));
    }
}
