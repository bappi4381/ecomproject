@extends('admin.layouts')

@section('title', 'Coupons List')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Coupons Management</h5>
        <a href="{{ route('coupons.create') }}" class="btn btn-primary btn-sm">Add New Coupon</a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Min Order</th>
                        <th>Expiry Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($coupons as $coupon)
                        <tr>
                            <td><strong>{{ $coupon->code }}</strong></td>
                            <td>{{ ucfirst($coupon->type) }}</td>
                            <td>{{ $coupon->value }}{{ $coupon->type == 'percent' ? '%' : '' }}</td>
                            <td>{{ $coupon->min_order_amount ?? 'N/A' }}</td>
                            <td>{{ $coupon->expiry_date }}</td>
                            <td>
                                <span class="badge {{ $coupon->status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $coupon->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('coupons.edit', $coupon->id) }}" class="btn btn-sm btn-info text-white">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $coupons->links() }}
        </div>
    </div>
</div>
@endsection
