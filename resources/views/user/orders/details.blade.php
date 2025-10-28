@extends('user.layout')

@section('title', 'Order Details')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Order #{{ $order->id }} Details</h3>

    <div class="card mb-4">
        <div class="card-body">
            <h5>Order Summary</h5>
            <p><strong>Order ID:</strong> {{ $order->order_id }}</p>
            <p><strong>Date:</strong> {{ $order->created_at->format('d M, Y h:i A') }}</p>
            <p><strong>Status:</strong> 
                <span class="badge 
                    @switch($order->status)
                        @case('pending') bg-warning @break
                        @case('processing') bg-info @break
                        @case('completed') bg-success @break
                        @case('cancelled') bg-danger @break
                        @default bg-secondary
                    @endswitch">
                    {{ ucfirst($order->status) }}
                </span>
            </p>
            <p><strong>Delivery Charge:</strong> tk. {{ number_format($order->delivery_charge ?? 0, 2) }}</p>
            <p><strong>Total Amount:</strong> tk. {{ number_format($order->total_price, 2) }}</p>
        </div>
    </div>

    {{-- Ordered Items --}}
    @if(isset($order->orderItems) && $order->orderItems->count() > 0)
        <div class="card">
            <div class="card-header">
                <h5>Ordered Items</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                            <tr>
                                <td>{{ $item->product->name ?? 'N/A' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>tk. {{ number_format($item->price, 2) }}</td>
                                <td>tk. {{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                        {{-- Delivery Charge --}}
                        <tr>
                            <td colspan="3" class="text-end"><strong>Delivery Charge:</strong></td>
                            <td>tk. {{ number_format($order->delivery_charge ?? 0, 2) }}</td>
                        </tr>
                        {{-- Total Price --}}
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total Price:</strong></td>
                            <td>tk. {{ number_format($order->total_price, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    @else
        <div class="alert alert-info">No items found for this order.</div>
    @endif

    <div class="mt-3">
        <a href="{{ route('user.orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Orders
        </a>
    </div>
</div>
@endsection
