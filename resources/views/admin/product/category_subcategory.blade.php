@extends('admin.layouts') {{-- or your admin layout --}}

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">Category & Subcategory Management</h4>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="row">
        <!-- Category Form -->
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">Add Category</div>
                <div class="card-body">
                    <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="category_name" class="form-label">Category Name</label>
                            <input type="text" name="name" id="category_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Category Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary">Add Category</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Subcategory Form -->
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">Add Subcategory</div>
                <div class="card-body">
                    <form action="{{ route('subcategories.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Select Category</label>
                            <select name="category_id" id="category_id" class="form-select" required>
                                <option value="">-- Choose Category --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="subcategory_name" class="form-label">Subcategory Name</label>
                            <input type="text" name="name" id="subcategory_name" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success">Add Subcategory</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Category & Subcategory List -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">Categories & Subcategories List</div>
        <div class="card-body">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 20%">Category</th>
                        <th style="width: 20%">Category Image</th>
                        <th style="width: 40%">Subcategories</th>
                        <th style="width: 20%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <!-- Category Name -->
                            <td><strong>{{ $category->name }}</strong></td>
                            <!-- Category Image -->
                            <td>
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="img-fluid" style="max-width: 100px;">
                                @else
                                    <em>No image</em>
                                @endif
                            </td>
                            <!-- Subcategories -->
                            <td>
                                @if($category->subcategories->count())
                                    <table class="table table-sm table-bordered mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Subcategory Name</th>
                                                <th style="width: 20%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($category->subcategories as $sub)
                                                <tr>
                                                    <td>{{ $sub->name }}</td>
                                                    <td>
                                                        <form action="{{ route('subcategories.destroy', $sub->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Delete this subcategory?')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <em>No subcategories</em>
                                @endif
                            </td>

                            <!-- Category Actions -->
                            <td>
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" 
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Delete this category?')">
                                        <i class="bi bi-trash"></i> Delete Category
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No categories found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
