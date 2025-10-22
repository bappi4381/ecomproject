@extends('admin.layouts')

@section('content')
<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-uppercase mb-0" style="letter-spacing: 2px; color: #6f4e37;">
            <i class="bi bi-folder2-open me-2"></i> Category & Subcategory Management
        </h4>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Category Form -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header fw-semibold">
                    <i class="bi bi-tags-fill me-2"></i> Add Category
                </div>
                <div class="card-body">
                    <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_name" class="form-label fw-semibold">Category Name</label>
                                    <input type="text" name="name" id="category_name" class="form-control" placeholder="Enter category name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_description" class="form-label fw-semibold">Category type</label>
                                    <select name="type" id="category_description" class="form-select" required>
                                        <option value="product">Product</option>
                                        <option value="blog">Article</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-plus-circle me-1"></i> Add Category
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Subcategory Form -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header fw-semibold">
                    <i class="bi bi-diagram-3 me-2"></i> Add Subcategory
                </div>
                <div class="card-body">
                    <form action="{{ route('subcategories.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <!-- Step 1: Select Type -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label fw-semibold">Select Category Type</label>
                                    <select name="type" id="type" class="form-select" required>
                                        <option value="">-- Choose Type --</option>
                                        <option value="blog">Article</option>
                                        <option value="product">Product</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Step 2: Select Category (populated dynamically) -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label fw-semibold">Select Category</label>
                                    <select name="category_id" id="category_id" class="form-select" required>
                                        <option value="">-- Choose Category --</option>
                                        <!-- Options will be filled by JS based on type -->
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Subcategory Name -->
                        <div class="mb-3">
                            <label for="subcategory_name" class="form-label fw-semibold">Subcategory Name</label>
                            <input type="text" name="name" id="subcategory_name" class="form-control" placeholder="Enter subcategory name" required>
                        </div>

                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-plus-circle me-1"></i> Add Subcategory
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Category & Subcategory List --}}
    <div class="card mt-4 border-0 shadow-sm rounded-3">
        <div class="card-header  fw-semibold">
            <i class="bi bi-table me-2"></i> Categories & Subcategories List
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover align-middle ">
                <thead class="table-primary">
                    <tr>
                        <th style="width: 20%">Category</th>
                        <th style="width: 20%">CategoryType</th>
                        <th style="width: 20%">Image</th>
                        <th style="width: 30%">Subcategories</th>
                        <th class="text-center" style="width: 10%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td class="text-start fw-semibold">{{ $category->name }}</td>
                            @php
                                $typeNames = [
                                    'product' => 'Product',
                                    'blog' => 'Article',
                                ];
                            @endphp

                            <td class="fw-semibold">
                                {{ $typeNames[$category->type] ?? $category->type }}
                            </td>
                            <td>
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" 
                                         alt="{{ $category->name }}" 
                                         class="img-thumbnail rounded" 
                                         style="max-width: 80px;">
                                @else
                                    <span class="text-muted">No image</span>
                                @endif
                            </td>
                            <td>
                                @if($category->subcategories->count())
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover table-bordered mb-0">
                                            <tbody>
                                                @foreach($category->subcategories as $sub)
                                                    <tr>
                                                        <td class="text-start align-middle">{{ $sub->name }}</td>
                                                        <td class="text-center" style="width: 15%;">
                                                            <form action="{{ route('subcategories.destroy', $sub->id) }}" method="POST" class="d-inline">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" 
                                                                    class="btn btn-sm btn-outline-danger"
                                                                    onclick="return confirm('Delete this subcategory?')">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <em class="text-muted">No subcategories</em>
                                @endif
                            </td>
                            <td class="text-center" >
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" 
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Delete this category and all subcategories?')">
                                        <i class="bi bi-trash3 me-1"></i> 
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const typeSelect = document.getElementById('type');
        const categorySelect = document.getElementById('category_id');

        typeSelect.addEventListener('change', function () {
            const selectedType = this.value;
            categorySelect.innerHTML = '<option value="">Loading...</option>';

            if(selectedType) {
                fetch(`/admin/categories/by-type/${selectedType}`)
                    .then(res => res.json())
                    .then(data => {
                        categorySelect.innerHTML = '<option value="">-- Choose Category --</option>';
                        data.forEach(cat => {
                            const option = document.createElement('option');
                            option.value = cat.id;
                            option.textContent = cat.name;
                            categorySelect.appendChild(option);
                        });
                    })
                    .catch(err => {
                        categorySelect.innerHTML = '<option value="">Error loading categories</option>';
                        console.error(err);
                    });
            } else {
                categorySelect.innerHTML = '<option value="">-- Choose Category --</option>';
            }
        });
    });
</script>
{{-- Optional: subtle hover animation for buttons --}}
<style>
    .btn-primary {
        transition: all 0.2s ease-in-out;
    }
    .btn-primary:hover {
        background-color: #e5986b;
        transform: translateY(-1px);
    }
    .card-header {
        letter-spacing: 0.3px;
    }
</style>
@endsection
