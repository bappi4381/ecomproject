@extends('admin.layouts')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-4">Admin Dashboard</h1>
            <p>Welcome to the admin dashboard!</p>
        </div>

        <!-- Total Products Card -->
        <div class="col-md-4">
            <div class="card mb-3 shadow-sm" style="background-color: #6f4e37; color: #fff;">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <p class="card-text fs-4 fw-bold">{{ $totalProducts }}</p>
                </div>
            </div>
        </div>

        <!-- Total Categories Card -->
        <div class="col-md-4">
            <div class="card mb-3 shadow-sm" style="background-color: #b75c1c; color: #fff;">
                <div class="card-body">
                    <h5 class="card-title">Total Categories</h5>
                    <p class="card-text fs-4 fw-bold">{{ $totalCategories }}</p>
                </div>
            </div>
        </div>

        <!-- Total Subcategories Card -->
        <div class="col-md-4">
            <div class="card mb-3 shadow-sm" style="background-color: #b77307; color: #fff;">
                <div class="card-body">
                    <h5 class="card-title">Total Subcategories</h5>
                    <p class="card-text fs-4 fw-bold">{{ $totalSubcategories }}</p>
                </div>
            </div>
        </div>  
    </div>
</div>
@endsection
