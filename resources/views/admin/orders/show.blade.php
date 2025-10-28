@extends('admin.layouts')
@section('title', 'Order Details')

@section('content')
<div class="container py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h4 class="fw-bold">🧾 Order Details — {{ $order->order_id }}</h4>
        </div>
        <div class="col-md-6 text-md-end">
            <div class="mt-3">
                <!-- Generate Invoice Button -->
                <a href="{{ route('admin.orders.invoice.generate', $order->id) }}" class="btn btn-sm btn-secondary">
                    <i class="bi bi-file-earmark-text"></i> Generate Invoice
                </a>

                <!-- Download Invoice Button -->
                @if($order->invoice_path)
                    <a href="{{ route('admin.orders.invoice.download', $order->id) }}" class="btn btn-sm btn-success">
                        <i class="bi bi-download"></i> Download Invoice
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5>Customer Info</h5>
            <p><strong>Name:</strong> {{ $order->user->name ?? 'Guest' }}</p>
            <p><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
            <p><strong>Delivery Area:</strong> 
                {{ ucfirst(str_replace('_', ' ', $order->delivery_area ?? 'Dhaka')) }}
            </p>
            <p><strong>Status:</strong> 
                <span class="badge bg-{{ 
                    $order->status == 'pending' ? 'warning' :
                    ($order->status == 'processing' ? 'info' :
                    ($order->status == 'completed' ? 'success' : 'danger'))
                }}">
                    {{ ucfirst($order->status) }}
                </span>
            </p>

            <!-- Status update buttons -->
            <form action="{{ route('orders.updateStatus', [$order->id, 'processing']) }}" method="POST" class="d-inline">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-outline-info btn-sm">Mark Processing</button>
            </form>
            <form action="{{ route('orders.updateStatus', [$order->id, 'completed']) }}" method="POST" class="d-inline">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-outline-success btn-sm">Mark Completed</button>
            </form>
            <form action="{{ route('orders.updateStatus', [$order->id, 'cancelled']) }}" method="POST" class="d-inline">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-outline-danger btn-sm">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Order Items -->
    <div class="card">
        <div class="card-header fw-semibold">Order Items</div>
        <div class="card-body">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Book</th>
                        <th>Quantity</th>
                        <th>Price (Tk)</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                        @foreach($order->orderItems as $item)
                            <tr>
                                <td>{{ $item->product->name ?? 'N/A' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>tk. {{ number_format($item->price, 2) }}</td>
                                <td>tk. {{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Subtotal</th>
                            <th>tk. {{ number_format($order->total_price - $order->delivery_charge, 2) }}</th>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-end">Delivery Charge</th>
                            <th>tk. {{ number_format($order->delivery_charge, 2) }}</th>
                        </tr>
                        <tr class="table-success">
                            <th colspan="3" class="text-end">Grand Total</th>
                            <th>tk. {{ number_format($order->total_price, 2) }}</th>
                        </tr>
                    </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
