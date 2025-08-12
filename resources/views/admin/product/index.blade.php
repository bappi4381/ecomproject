@extends('admin.layouts')
@section('title', 'Product List')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Product List</h4>
        <a href="{{ route('products.create') }}" class="btn btn-primary">Add New Product</a>
    </div>

    <div class="mb-3">
        <form action="{{ route('products.index') }}" method="GET" class="row g-4 align-items-center">
            <div class="col-auto" style="flex:1; min-width:250px;">
                <input type="text" name="search" class="form-control" placeholder="Search products by name, category..." value="{{ request('search') }}" autofocus autocomplete="off" style="width:100%;">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
            @if(request('search'))
                <div class="col-auto">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Clear</a>
                </div>
            @endif
        </form>
    </div>

    {{-- Display success message if any --}}

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Subcategory</th>
                <th>Price</th>
                <th>Discount (%)</th>
                <th>Sizes & Stocks</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name ?? '-' }}</td>
                    <td>{{ $product->subcategory->name ?? '-' }}</td>
                    <td>tk. {{ number_format($product->price, 2) }}</td>
                    <td>{{ $product->discount ?? '0' }}</td>
                    <td>
                        @foreach($product->sizes as $size)
                            <div>Size: {{ $size->size }}, Stock: {{ $size->stock }}</div>
                        @endforeach
                    </td>
                    <td>
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" width="60" height="60" class="rounded">
                        @else
                            <em>No image</em>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">Edit</a>

                        <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center">No products found.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $products->links() }}
</div>
@endsection
