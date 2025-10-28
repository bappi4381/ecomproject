@extends('admin.layouts')

@section('title', 'User Details')

@section('content')
<div class="container py-4">
    <div class="row align-items-center mb-4">
        <!-- Left: User List Title -->
        <div class="col-8">
            <h4 class="fw-bold text-uppercase mb-0" style="letter-spacing: 1px; color:#6f4e37;">
                <i class="bi bi-users me-2"></i>{{ $user->name }}'s Profile
            </h4>
        </div>
        <div class="col-4 text-end">
            <a href="{{ route('users.index') }}" class="btn btn-secondary mb-3">← Back to Users</a>
        </div>
    </div>
    
    {{-- User Info --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Phone:</strong> {{ $user->phone ?? '-' }}</p>
            <p><strong>Address:</strong> {{ $user->address ?? '-' }}</p>
        </div>
    </div>

    {{-- User Orders --}}
    <h5>Orders</h5>
    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order ID</th>
                        <th>Total (৳)</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $key => $order)
                    <tr>
                        <td>{{ $orders->firstItem() + $key }}</td>
                        <td>{{ $order->order_id }}</td>
                        <td>{{ number_format($order->total_price,2) }}</td>
                        <td>{{ ucfirst($order->status) }}</td>
                        <td>{{ $order->created_at->format('d M, Y') }}</td>
                        <td>
                            <a href="{{ route('orders.show', $order->id) }}" target="_blank" class="btn btn-sm btn-outline-primary">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No orders found for this user.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
