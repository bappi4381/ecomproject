<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

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
            'address' => 'required|string|max:500',
            'payment_method' => 'required|in:cod,online',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('books.index')->with('error','Cart is empty!');
        }

        $password = null;

        // Auto registration for guest
        if (!Auth::check()) {
            $password = uniqid('pass_');
            $user = User::firstOrCreate(
                ['email' => $request->email],
                [
                    'name' => $request->name,
                    'password' => Hash::make($password),
                    'phone' => $request->phone,
                    'address' => $request->address,
                ]
            );
            Auth::login($user);
        }

        $user = Auth::user();

        // Create unique order id
        $orderId = 'ORD' . strtoupper(uniqid());

        $totalPrice = 0;
        foreach($cart as $id => $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        // Create Order
        $order = Order::create([
            'order_id' => $orderId,
            'user_id' => $user->id,
            'total_price' => $totalPrice,
            'status' => $request->payment_method == 'cod' ? 'pending' : 'processing',
        ]);

        // Create Order Items
        foreach($cart as $id => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['price'] * $item['quantity'],
            ]);
        }

        session()->forget('cart');

        // Send order confirmation email
        Mail::send('emails.order_confirmation', [
            'name' => $user->name,
            'order_id' => $order->order_id,
            'total' => $totalPrice,
            'password' => $password,
            'payment_method' => $request->payment_method,
        ], function($message) use($user){
            $message->to($user->email, $user->name)->subject('Order Confirmation - BookSaw');
        });

        return redirect()->back()->with('success', 'Order placed successfully! A confirmation email has been sent to your email address.');
    }
}
