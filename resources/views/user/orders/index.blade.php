@extends('user.layout')

@section('title', 'My Orders')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">My Orders</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($orders->isEmpty())
        <div class="alert alert-info">You have no orders yet.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->order_id }}</td>
                            <td>{{ $order->created_at->format('d M, Y') }}</td>
                            <td>tk. {{ number_format($order->total_price, 2) }}</td>
                            <td>
                                @php
                                    $statusClass = match($order->status) {
                                        'pending' => 'badge bg-warning',
                                        'processing' => 'badge bg-info',
                                        'completed' => 'badge bg-success',
                                        'cancelled' => 'badge bg-danger',
                                        default => 'badge bg-secondary',
                                    };
                                @endphp
                                <span class="{{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('user.orders.details', $order->id) }}" class="btn btn-sm btn-primary">View</a>

                                @if($order->status === 'pending')
                                    <form action="{{ route('user.orders.cancel', $order->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to cancel this order?')">Cancel</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
{{-- <style>
    .order-status-badge {
        padding: 0.4em 0.8em;
        border-radius: 0.25rem;
        color: #fff;
        font-weight: 600;
    }
    .status-pending {
        background-color: #ffc107;
    }
    .status-processing {
        background-color: #17a2b8;
    }
    .status-completed {
        background-color: #28a745;
    }
    .status-cancelled {
        background-color: #dc3545;
    }
</style> --}}       