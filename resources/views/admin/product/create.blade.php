@extends('admin.layouts')
@section('title', isset($product) ? 'Edit Product' : 'Add New Product')

@section('content')
<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-uppercase mb-0" style="letter-spacing: 2px; color: #6f4e37;">
            <i class="bi bi-box-seam me-2"></i> {{ isset($product) ? 'Edit Product' : 'Add New Product' }}
        </h4>
    </div>

    @if(isset($product->id))
        <p class="mb-4 text-muted"><strong>Product ID:</strong> {{ $product->product_id }}</p>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}" 
          method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($product)) @method('PUT') @endif

        {{-- Basic Information --}}
        <div class="card mb-3 border-0 shadow-sm rounded-3">
            <div class="card-header fw-semibold">
                <i class="bi bi-info-circle me-2"></i> Basic Information
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Name</label>
                        <input type="text" name="name" class="form-control"value="{{ old('name', $product->name ?? '') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Author Name</label>
                        <input type="text" name="author" class="form-control" value="{{ old('author', $product->author ?? '') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Publisher</label>
                        <input type="text" name="publisher" class="form-control" value="{{ old('publisher', $product->publisher ?? '') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Publishing Date</label>
                        <input type="date" name="publishing_date" class="form-control" value="{{ old('publishing_date', $product->publishing_date ?? '') }}" required>                    
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Category</label>
                        <select name="category_id" id="category-select" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Subcategory</label>
                        <select name="subcategory_id" id="subcategory-select" class="form-select">
                            <option value="">Select Subcategory</option>
                            @foreach($subcategories as $sub)
                                <option value="{{ $sub->id }}" 
                                    {{ old('subcategory_id', $product->subcategory_id ?? '') == $sub->id ? 'selected' : '' }}>
                                    {{ $sub->name }}
                                </option>
                            @endforeach
                        </select>
                        <div id="subcategory-loading" class="small text-muted" style="display:none;">Loading...</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description ?? '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Pricing --}}
        <div class="card mb-3 border-0 shadow-sm rounded-3">
            <div class="card-header fw-semibold">
                <i class="bi bi-cash-coin me-2"></i> Pricing & Stock
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold">In Stock</label>
                        <input type="number" name="stock" id="stock" class="form-control" value="{{ old('stock', $product->stock ?? '') }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold">Price</label>
                        <input type="number" name="price" id="price" class="form-control" step="0.01" 
                               value="{{ old('price', $product->price ?? '') }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold">Discount (%)</label>
                        <input type="number" name="discount" id="discount" class="form-control" step="0.01" 
                               min="0" max="100" value="{{ old('discount', $product->discount ?? '') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold">Discounted Price</label>
                        <input type="number" id="discounted_price" class="form-control" readonly
                               value="{{ old('discounted_price', isset($product) ? number_format($product->price - ($product->price * ($product->discount ?? 0)/100), 2) : '') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Product Images --}}
        <div class="card mb-3 border-0 shadow-sm rounded-3">
            <div class="card-header fw-semibold">
                <i class="bi bi-images me-2"></i> Product Images
            </div>
            <div class="card-body">
                <div id="image-drop-area" 
                     class="border rounded-3 text-center py-4" 
                     style="border:2px dashed #A75E30; cursor:pointer;">
                    <p class="mb-0 text-muted">Drag & Drop Images Here or Click to Upload</p>
                    <input type="file" name="images[]" accept="image/*" multiple style="display:none;" id="image-input">
                </div>
                <div id="image-preview" class="d-flex flex-wrap mt-3">
                    @if(isset($product) && $product->images)
                        @foreach($product->images as $img)
                            <div class="position-relative me-2 mb-2">
                                <img src="{{ asset('storage/' . $img->image) }}" width="80" class="rounded shadow-sm">
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-check-circle me-1"></i> {{ isset($product) ? 'Update' : 'Add' }} Product
        </button>
    </form>
</div>
<script> document.addEventListener('DOMContentLoaded', function () { // --- Sizes --- let sizeIndex = {{ count(old('sizes', $product->sizes ?? [])) }}; const container = document.getElementById('sizes-container'); document.getElementById('add-size-btn').addEventListener('click', function() { const row = document.createElement('div'); row.classList.add('row', 'align-items-center', 'mb-2', 'size-row'); row.innerHTML = <div class="col-5"> <input type="text" name="sizes[${sizeIndex}][size]" class="form-control" placeholder="Size" required> </div> <div class="col-5"> <input type="number" name="sizes[${sizeIndex}][stock]" class="form-control" placeholder="Stock" min="0" required> </div> <div class="col-2 text-end"> <button type="button" class="btn btn-outline-danger btn-remove-size">X</button> </div> ; container.appendChild(row); sizeIndex++; }); container.addEventListener('click', function(e) { if(e.target.classList.contains('btn-remove-size')) { e.target.closest('.size-row').remove(); } }); // --- Category/Subcategory --- const categorySelect = document.getElementById('category-select'); const subcategorySelect = document.getElementById('subcategory-select'); const subcategoryLoading = document.getElementById('subcategory-loading'); let oldSubcategory = "{{ old('subcategory_id', $product->subcategory_id ?? '') }}"; categorySelect.addEventListener('change', function() { const categoryId = this.value; if(!categoryId) { subcategorySelect.innerHTML = '<option value="">Select Subcategory</option>'; return; } subcategoryLoading.style.display = 'inline'; fetch("{{ route('get.subcategories') }}?category_id=" + categoryId) .then(res => res.json()) .then(data => { let options = '<option value="">Select Subcategory</option>'; data.forEach(sub => { options += <option value="${sub.id}" ${sub.id == oldSubcategory ? 'selected' : ''}>${sub.name}</option>; }); subcategorySelect.innerHTML = options; subcategoryLoading.style.display = 'none'; }); }); // --- Pricing/Discount --- const priceInput = document.getElementById('price'); const discountInput = document.getElementById('discount'); const discountedPriceInput = document.getElementById('discounted_price'); function updateDiscountedPrice() { let price = parseFloat(priceInput.value) || 0; let discount = parseFloat(discountInput.value) || 0; if(discount < 0) discount = 0; if(discount > 100) discount = 100; discountedPriceInput.value = (price - price*(discount/100)).toFixed(2); } priceInput.addEventListener('input', updateDiscountedPrice); discountInput.addEventListener('input', updateDiscountedPrice); updateDiscountedPrice(); // --- Drag & Drop Images --- const dropArea = document.getElementById('image-drop-area'); const input = document.getElementById('image-input'); const preview = document.getElementById('image-preview'); dropArea.addEventListener('click', () => input.click()); input.addEventListener('change', function () { previewFiles(this.files); }); ['dragenter', 'dragover'].forEach(eventName => { dropArea.addEventListener(eventName, (e) => { e.preventDefault(); e.stopPropagation(); dropArea.style.backgroundColor = '#e9f5ff'; }); }); ['dragleave', 'drop'].forEach(eventName => { dropArea.addEventListener(eventName, (e) => { e.preventDefault(); e.stopPropagation(); dropArea.style.backgroundColor = ''; }); }); dropArea.addEventListener('drop', (e) => { const dt = e.dataTransfer; const files = dt.files; input.files = files; previewFiles(files); }); function previewFiles(files) { preview.innerHTML = ''; Array.from(files).forEach(file => { if(!file.type.startsWith('image/')) return; const reader = new FileReader(); reader.onload = function(e) { const img = document.createElement('img'); img.src = e.target.result; img.width = 80; img.classList.add('rounded', 'me-2', 'mb-2'); preview.appendChild(img); } reader.readAsDataURL(file); }); } }); </script>

<script>
document.addEventListener('DOMContentLoaded', function () {
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

<style>
    .btn-primary {
        background-color: #A75E30;
        border-color: #A75E30;
        transition: all 0.2s ease-in-out;
    }
    .btn-primary:hover {
        background-color: #B46A3B;
        border-color: #B46A3B;
        transform: translateY(-1px);
    }
    .card-header {
        background-color: #F3F2EC;
        color: #6f4e37;
        border-bottom: 1px solid #e6ddd3;
    }
</style>

@endsection
