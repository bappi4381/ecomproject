<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;

class SslcommerzController extends Controller
{ 
    public function payNow($orderId)
    {
        $order = Order::findOrFail($orderId);

        $post_data = [
            'store_id' => env('SSLC_STORE_ID'),
            'store_passwd' => env('SSLC_STORE_PASSWORD'),
            'total_amount' => $order->total_price,
            'currency' => "BDT",
            'tran_id' => $order->order_id,
            'success_url' => route('ssl.success'),
            'fail_url' => route('ssl.fail'),
            'cancel_url' => route('ssl.cancel'),
            'cus_name' => $order->user->name,
            'cus_email' => $order->user->email,
            'cus_add1' => $order->shipping_address,
            'cus_city' => $order->city,
            'cus_phone' => $order->user->phone,
            'cus_country' => "Bangladesh",
            'shipping_method' => "Courier",
            'product_name' => "Products",
            'product_category' => "Ecommerce",
            'product_profile' => "general",
        ];

        // dd($post_data);

       $api_url = env('SSLC_SANDBOX') === 'true'
            ? "https://sandbox.sslcommerz.com/gwprocess/v4/api.php"
            : "https://securepay.sslcommerz.com/gwprocess/v4/api.php";

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $api_url);
        curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($handle, CURLOPT_POST, 1);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true); // <--- important
        $content = curl_exec($handle);
        curl_close($handle);

        $response = json_decode($content, true);
        // dd($response);
        if (isset($response['status']) && $response['status'] === 'SUCCESS' && !empty($response['GatewayPageURL'])) {
            return redirect()->away($response['GatewayPageURL']);
        } else {
            // Log the response for debugging
            \Log::error('SSLCommerz Payment Failed', $response);

            // Show user-friendly error
            return back()->with('error', $response['failedreason'] ?? 'Could not connect to SSLCommerz');
        }
    }

    public function success(Request $request)
    {
        $order = Order::where('order_id', $request->tran_id)->firstOrFail();
        $order->payment_status = 'paid';
        $order->status = 'confirmed';
        $order->save();

        return redirect()->route('orders.success', $order->id)
                         ->with('success', 'Payment Successful!');
    }

    public function fail() {
        return redirect()->route('home')->with('error', 'Payment Failed!');
    }

    public function cancel() {
        return redirect()->route('home')->with('error', 'Payment Cancelled!');
    }
}

