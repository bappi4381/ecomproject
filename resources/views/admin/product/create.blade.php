@extends('admin.layouts')
@section('title', isset($product) ? 'Edit Product' : 'Add New Product')

@section('content')
<div class="container-fluid py-3" style="max-width: 1200px;">

    <h4 class="mb-4">{{ isset($product) ? 'Edit Product' : 'Add New Product' }}</h4>

    @if(isset($product->id))
        <p class="mb-4 text-muted"><strong>Product ID : </strong>{{ $product->product_id }}</p>
    @endif
    <form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}" 
          method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($product)) @method('PUT') @endif

        {{-- ------------------ Basic Info ------------------ --}}
        
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-primary text-white">Basic Information</div>
            <div class="card-body">
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" 
                           value="{{ old('name', $product->name ?? '') }}" required>
                    @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Category</label>
                        <select name="category_id" id="category-select" class="form-control" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Subcategory</label>
                        <select name="subcategory_id" id="subcategory-select" class="form-control">
                            <option value="">Select Subcategory</option>
                            @foreach($subcategories as $sub)
                                <option value="{{ $sub->id }}" {{ old('subcategory_id', $product->subcategory_id ?? '') == $sub->id ? 'selected' : '' }}>
                                    {{ $sub->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subcategory_id') <div class="text-danger small">{{ $message }}</div> @enderror
                        <div id="subcategory-loading" class="small text-muted" style="display:none;">Loading...</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control">{{ old('description', $product->description ?? '') }}</textarea>
                    @error('description') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- ------------------ Pricing ------------------ --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-success text-white">Pricing</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Price</label>
                        <input type="number" name="price" id="price" class="form-control" step="0.01" 
                               value="{{ old('price', $product->price ?? '') }}" required>
                        @error('price') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Discount (%)</label>
                        <input type="number" name="discount" id="discount" class="form-control" step="0.01" min="0" max="100"
                               value="{{ old('discount', $product->discount ?? '') }}">
                        @error('discount') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Discounted Price</label>
                        <input type="number" id="discounted_price" class="form-control" readonly
                               value="{{ old('discounted_price', isset($product) ? number_format($product->price - ($product->price * ($product->discount ?? 0)/100), 2) : '') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- ------------------ Sizes & Stock ------------------ --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-warning text-dark">Sizes & Stock</div>
            <div class="card-body">
                <div id="sizes-container">
                    @php $oldSizes = old('sizes', $product->sizes ?? []); @endphp
                    @if(count($oldSizes))
                        @foreach($oldSizes as $i => $sizeData)
                            <div class="row align-items-center mb-2 size-row">
                                <div class="col-5">
                                    <input type="text" name="sizes[{{ $i }}][size]" class="form-control" 
                                           placeholder="Size" value="{{ is_array($sizeData) ? $sizeData['size'] : $sizeData->size }}" required>
                                </div>
                                <div class="col-5">
                                    <input type="number" name="sizes[{{ $i }}][stock]" class="form-control" 
                                           placeholder="Stock" min="0" value="{{ is_array($sizeData) ? $sizeData['stock'] : $sizeData->stock }}" required>
                                </div>
                                <div class="col-2 text-end">
                                    <button type="button" class="btn btn-outline-danger btn-remove-size">X</button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="row align-items-center mb-2 size-row">
                            <div class="col-5">
                                <input type="text" name="sizes[0][size]" class="form-control" placeholder="Size" required>
                            </div>
                            <div class="col-5">
                                <input type="number" name="sizes[0][stock]" class="form-control" placeholder="Stock" min="0" required>
                            </div>
                            <div class="col-2 text-end">
                                <button type="button" class="btn btn-outline-danger btn-remove-size">X</button>
                            </div>
                        </div>
                    @endif
                </div>
                <button type="button" id="add-size-btn" class="btn btn-outline-primary mt-2">+ Add Size</button>
            </div>
        </div>

        {{-- ------------------ Drag & Drop Multiple Images ------------------ --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-info text-white">Product Images</div>
            <div class="card-body">
                <div id="image-drop-area" style="border:2px dashed #0d6efd; padding:20px; text-align:center; cursor:pointer;">
                    <p>Drag & Drop Images Here or Click to Upload</p>
                    <input type="file" name="images[]" accept="image/*" multiple style="display:none;" id="image-input">
                </div>
                <div id="image-preview" class="d-flex flex-wrap mt-2">
                    @if(isset($product) && $product->images)
                        @foreach($product->images as $img)
                            <div class="position-relative me-2 mb-2">
                                <img src="{{ asset('storage/' . $img->image) }}" width="80" class="rounded">
                            </div>
                        @endforeach
                    @endif
                </div>
                @error('images.*') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- ------------------ Submit Button ------------------ --}}
        <button type="submit" class="btn btn-primary btn-lg">{{ isset($product) ? 'Update' : 'Add' }} Product</button>
    </form>
</div>

{{-- ------------------ Scripts ------------------ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- Sizes ---
    let sizeIndex = {{ count(old('sizes', $product->sizes ?? [])) }};
    const container = document.getElementById('sizes-container');
    document.getElementById('add-size-btn').addEventListener('click', function() {
        const row = document.createElement('div');
        row.classList.add('row', 'align-items-center', 'mb-2', 'size-row');
        row.innerHTML = `
            <div class="col-5">
                <input type="text" name="sizes[${sizeIndex}][size]" class="form-control" placeholder="Size" required>
            </div>
            <div class="col-5">
                <input type="number" name="sizes[${sizeIndex}][stock]" class="form-control" placeholder="Stock" min="0" required>
            </div>
            <div class="col-2 text-end">
                <button type="button" class="btn btn-outline-danger btn-remove-size">X</button>
            </div>
        `;
        container.appendChild(row);
        sizeIndex++;
    });

    container.addEventListener('click', function(e) {
        if(e.target.classList.contains('btn-remove-size')) {
            e.target.closest('.size-row').remove();
        }
    });

    // --- Category/Subcategory ---
    const categorySelect = document.getElementById('category-select');
    const subcategorySelect = document.getElementById('subcategory-select');
    const subcategoryLoading = document.getElementById('subcategory-loading');
    let oldSubcategory = "{{ old('subcategory_id', $product->subcategory_id ?? '') }}";

    categorySelect.addEventListener('change', function() {
        const categoryId = this.value;
        if(!categoryId) {
            subcategorySelect.innerHTML = '<option value="">Select Subcategory</option>';
            return;
        }
        subcategoryLoading.style.display = 'inline';
        fetch("{{ route('get.subcategories') }}?category_id=" + categoryId)
            .then(res => res.json())
            .then(data => {
                let options = '<option value="">Select Subcategory</option>';
                data.forEach(sub => {
                    options += `<option value="${sub.id}" ${sub.id == oldSubcategory ? 'selected' : ''}>${sub.name}</option>`;
                });
                subcategorySelect.innerHTML = options;
                subcategoryLoading.style.display = 'none';
            });
    });

    // --- Pricing/Discount ---
    const priceInput = document.getElementById('price');
    const discountInput = document.getElementById('discount');
    const discountedPriceInput = document.getElementById('discounted_price');

    function updateDiscountedPrice() {
        let price = parseFloat(priceInput.value) || 0;
        let discount = parseFloat(discountInput.value) || 0;
        if(discount < 0) discount = 0;
        if(discount > 100) discount = 100;
        discountedPriceInput.value = (price - price*(discount/100)).toFixed(2);
    }

    priceInput.addEventListener('input', updateDiscountedPrice);
    discountInput.addEventListener('input', updateDiscountedPrice);
    updateDiscountedPrice();

    // --- Drag & Drop Images ---
    const dropArea = document.getElementById('image-drop-area');
    const input = document.getElementById('image-input');
    const preview = document.getElementById('image-preview');

    dropArea.addEventListener('click', () => input.click());

    input.addEventListener('change', function () {
        previewFiles(this.files);
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, (e) => {
            e.preventDefault(); e.stopPropagation();
            dropArea.style.backgroundColor = '#e9f5ff';
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, (e) => {
            e.preventDefault(); e.stopPropagation();
            dropArea.style.backgroundColor = '';
        });
    });

    dropArea.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        input.files = files;
        previewFiles(files);
    });

    function previewFiles(files) {
        preview.innerHTML = '';
        Array.from(files).forEach(file => {
            if(!file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.width = 80;
                img.classList.add('rounded', 'me-2', 'mb-2');
                preview.appendChild(img);
            }
            reader.readAsDataURL(file);
        });
    }
});
</script>
@endsection
