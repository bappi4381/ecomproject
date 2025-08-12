@extends('admin.layouts')
@section('title', (isset($product) && $product) ? 'Edit Product' : 'Add New Product')

@section('content')
<div class="container">
    <h2>{{ (isset($product) && $product) ? 'Edit Product' : 'Add New Product' }}</h2>

    <form action="{{ (isset($product) && $product) ? route('products.update', $product) : route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($product) && $product)
            @method('PUT')
        @endif

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label>Category</label>
            <select name="category_id" class="form-control" id="category-select" required>
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Subcategory</label>
            <select name="subcategory_id" class="form-control" id="subcategory-select">
                <option value="">Select Subcategory</option>
                @foreach($subcategories as $sub)
                    <option value="{{ $sub->id }}" {{ old('subcategory_id', $product->subcategory_id ?? '') == $sub->id ? 'selected' : '' }}>
                        {{ $sub->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ old('description', $product->description ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label>Price</label>
            <input type="number" name="price" step="0.01" class="form-control" value="{{ old('price', $product->price ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label>Discount (%)</label>
            <input type="number" name="discount" step="0.01" min="0" max="100" class="form-control" value="{{ old('discount', $product->discount ?? '') }}">
        </div>

        <div class="mb-3">
            <label>Sizes & Stock</label>
            <div id="sizes-container">
                @php
                    $oldSizes = old('sizes', $product->sizes ?? []);
                @endphp

                @if(count($oldSizes) > 0)
                    @foreach($oldSizes as $i => $sizeData)
                        <div class="row mb-2 size-row">
                            <div class="col-5">
                                <input type="text" name="sizes[{{ $i }}][size]" class="form-control" placeholder="Size" value="{{ is_array($sizeData) ? $sizeData['size'] : $sizeData->size }}" required>
                            </div>
                            <div class="col-5">
                                <input type="number" name="sizes[{{ $i }}][stock]" class="form-control" placeholder="Stock" min="0" value="{{ is_array($sizeData) ? $sizeData['stock'] : $sizeData->stock }}" required>
                            </div>
                            <div class="col-2">
                                <button type="button" class="btn btn-danger btn-remove-size">X</button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="row mb-2 size-row">
                        <div class="col-5">
                            <input type="text" name="sizes[0][size]" class="form-control" placeholder="Size" required>
                        </div>
                        <div class="col-5">
                            <input type="number" name="sizes[0][stock]" class="form-control" placeholder="Stock" min="0" required>
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-danger btn-remove-size">X</button>
                        </div>
                    </div>
                @endif
            </div>
            <button type="button" class="btn btn-secondary" id="add-size-btn">Add Size</button>
        </div>

        <div class="mb-3">
            <label>Image</label>
            <input type="file" name="image" accept="image/*" class="form-control">
            @if(isset($product) && $product && $product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" width="80" class="mt-2 rounded">
            @endif
        </div>

        <button type="submit" class="btn btn-primary">{{ (isset($product) && $product) ? 'Update' : 'Add' }} Product</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let sizeIndex = {{ count(old('sizes', $product->sizes ?? [])) }};

        document.getElementById('add-size-btn').addEventListener('click', function() {
            const container = document.getElementById('sizes-container');

            const row = document.createElement('div');
            row.classList.add('row', 'mb-2', 'size-row');
            row.innerHTML = `
                <div class="col-5">
                    <input type="text" name="sizes[${sizeIndex}][size]" class="form-control" placeholder="Size" required>
                </div>
                <div class="col-5">
                    <input type="number" name="sizes[${sizeIndex}][stock]" class="form-control" placeholder="Stock" min="0" required>
                </div>
                <div class="col-2">
                    <button type="button" class="btn btn-danger btn-remove-size">X</button>
                </div>
            `;
            container.appendChild(row);
            sizeIndex++;
        });

        document.getElementById('sizes-container').addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-remove-size')) {
                e.target.closest('.size-row').remove();
            }
        });

        document.getElementById('category-select').addEventListener('change', function() {
            const categoryId = this.value;
            const subcategorySelect = document.getElementById('subcategory-select');

            if (!categoryId) {
                subcategorySelect.innerHTML = '<option value="">Select Subcategory</option>';
                return;
            }

            fetch("{{ route('get.subcategories') }}?category_id=" + categoryId)
                .then(response => response.json())
                .then(data => {
                    let options = '<option value="">Select Subcategory</option>';
                    data.forEach(sub => {
                        options += `<option value="${sub.id}">${sub.name}</option>`;
                    });
                    subcategorySelect.innerHTML = options;
                })
                .catch(() => {
                    subcategorySelect.innerHTML = '<option value="">Select Subcategory</option>';
                });
        });

    });
</script>
@endsection
