@extends('admin.layouts')
@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard Overview</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Analytics & Stats</li>
    </ol>

    <div class="row">
        <!-- Sales Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-50 small">Total Sales</div>
                            <div class="fs-4 fw-bold">tk. {{ number_format($totalSales, 2) }}</div>
                        </div>
                        <i class="bi bi-currency-dollar fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Orders Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-50 small">Total Orders</div>
                            <div class="fs-4 fw-bold">{{ $totalOrders }}</div>
                        </div>
                        <i class="bi bi-cart-check fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Users Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-50 small">Total Customers</div>
                            <div class="fs-4 fw-bold">{{ $totalUsers }}</div>
                        </div>
                        <i class="bi bi-people fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Products Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-50 small">Total Products</div>
                            <div class="fs-4 fw-bold">{{ $totalProducts }}</div>
                        </div>
                        <i class="bi bi-book fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Sales Chart -->
        <div class="col-xl-8">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white">
                    <i class="bi bi-bar-chart-fill me-1"></i>
                    Sales Revenue (Last 6 Months)
                </div>
                <div class="card-body">
                    <canvas id="salesChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Low Stock Alerts -->
        <div class="col-xl-6">
            <div class="card mb-4 shadow-sm border-danger">
                <div class="card-header bg-danger text-white">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    Low Stock Alerts (< 5 items)
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Stock</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lowStockProducts as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td><span class="badge bg-danger">{{ $product->stock }}</span></td>
                                    <td><a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary">Update</a></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-success py-3">All products are well stocked!</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Recent Orders (Moved to col-6) -->
        <div class="col-xl-6">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white">
                    <i class="bi bi-clock-history me-1"></i>
                    Recent Orders
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td>#{{ $order->order_id }}</td>
                                    <td>{{ $order->user->name ?? 'Guest' }}</td>
                                    <td>tk. {{ number_format($order->total_amount_with_delivery, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($salesData->pluck('month')) !!},
            datasets: [{
                label: 'Revenue (Tk)',
                data: {!! json_encode($salesData->pluck('total')) !!},
                backgroundColor: 'rgba(13, 110, 253, 0.7)',
                borderColor: 'rgb(13, 110, 253)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
