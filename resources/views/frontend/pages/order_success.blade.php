@extends('frontend.layout')

@section('title', 'Order Success')

@section('content')
<div class="container py-5">
    <div class="text-center">
        <h1 class="mb-4 text-success">Thank You!</h1>
        <p class="mb-3">Your order <strong>{{ $order->order_id }}</strong> has been placed successfully.</p>
        <p>Total: <strong>{{ number_format($order->total_price, 2) }} Tk</strong></p>
        <a href="{{ route('books.index') }}" class="btn btn-primary mt-3">Continue Shopping</a>
    </div>
</div>
@endsection