@extends('admin.layouts')
@section('title', 'Product List')

@section('content')
<div class="container py-4">
    <div class="row align-items-center mb-4">
        <!-- Left: Product List Title -->
        <div class="col-3">
            <h4 class="fw-bold text-uppercase mb-0" style="letter-spacing: 1px; color:#6f4e37;">
                <i class="bi bi-box-seam me-2"></i> Product List
            </h4>
        </div>

        <!-- Middle: Modern Search Input -->
        <div class="col-6">
            <form action="{{ route('products.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control rounded-start shadow-sm" 
                    placeholder="Search by name, category..." 
                    value="{{ request('search') }}" autocomplete="off" style="height:45px;">
                <button type="submit" class="btn btn-primary rounded-end shadow-sm ms-1" style="height:45px;">
                    <i class="bi bi-search"></i>
                </button>
                @if(request('search'))
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary ms-1 shadow-sm" style="height:45px;">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Right: Add New Product Button -->
        <div class="col-3 text-end">
            <a href="{{ route('products.create') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Add New Product
            </a>
        </div>
    </div>


    {{-- Success message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Products Table --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>Product ID</th>
                    <th>Name</th>
                    <th>Author</th>
                    <th>Publisher</th>
                    <th>Category</th>
                    <th>Subcategory</th>
                    <th>Price (TK)</th>
                    <th>Discount</th>
                    <th>Stock</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr class="text-center">
                        <td>{{ $product->id }}</td>
                        <td class="text-start">{{ $product->name }}</td>
                        <td>{{ $product->author ?? '-' }}</td>
                        <td>{{ $product->publisher ?? '-' }}</td>
                        <td>{{ $product->category->name ?? '-' }}</td>
                        <td>{{ $product->subcategory->name ?? '-' }}</td>
                        <td>{{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->discount ? (int)$product->discount.'%' : '0%' }}</td>
                        <td>{{ $product->stock ?? 0 }}</td>
                        <td>
                            @if($product->images->count())
                                <img src="{{ asset('storage/' . $product->images->first()->image) }}" 
                                     alt="{{ $product->name }}" width="60" class="rounded border">
                            @else
                                <em>No image</em>
                            @endif
                        </td>
                        <td class=" justify-content-center">
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning mb-1">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $products->links() }}
    </div>
</div>

<style>
    .table td, .table th {
        vertical-align: middle;
    }
    .btn-primary {
        background-color: #A75E30;
        border-color: #A75E30;
    }
    .btn-primary:hover {
        background-color: #B46A3B;
        border-color: #B46A3B;
    }
</style>
@endsection
