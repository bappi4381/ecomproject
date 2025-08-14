@extends('admin.layouts')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Create Order</h3>

    <form action="{{ route('orders.store') }}" method="POST" id="orderForm">
        @csrf

        {{-- Select Customer --}}
        <div class="mb-4">
            <label class="form-label fw-bold">Customer</label>
            <select name="user_id" class="form-select" required>
                <option value="">-- Select Customer --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
        </div>

        {{-- Products Section --}}
        <h5 class="mb-3">Products</h5>
        <div id="products-wrapper" class="mb-3">
            <div class="product-row row g-2 align-items-center mb-2">
                <div class="col-md-6">
                    <select name="products[0][id]" class="form-select product-select" required>
                        <option value="">-- Select Product --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                {{ $product->name }} - ${{ number_format($product->price, 2) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" name="products[0][quantity]" class="form-control quantity-input" min="1" value="1" required>
                </div>
                <div class="col-md-2">
                    <span class="subtotal badge bg-light text-dark w-100">$0.00</span>
                </div>
                <div class="col-md-2 text-end">
                    <button type="button" class="btn btn-danger btn-sm remove-product d-none">×</button>
                </div>
            </div>
        </div>

        <button type="button" id="add-product" class="btn btn-outline-secondary btn-sm mb-3">
            + Add Product
        </button>

        {{-- Total Price --}}
        <div class="mb-3">
            <h5>Total: <span id="totalPrice" class="text-primary">$0.00</span></h5>
        </div>

        <button type="submit" class="btn btn-primary">Create Order</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
let productIndex = 1;
const productsData = @json($products);

function updateSubtotal(row) {
    const price = parseFloat(row.querySelector('.product-select').selectedOptions[0]?.dataset.price || 0);
    const quantity = parseInt(row.querySelector('.quantity-input').value || 0);
    const subtotal = price * quantity;
    row.querySelector('.subtotal').textContent = `$${subtotal.toFixed(2)}`;
    updateTotal();
}

function updateTotal() {
    let total = 0;
    document.querySelectorAll('#products-wrapper .product-row').forEach(row => {
        total += parseFloat(row.querySelector('.subtotal').textContent.replace('$', '') || 0);
    });
    document.getElementById('totalPrice').textContent = `$${total.toFixed(2)}`;
}

// Event: Quantity or Product change
document.getElementById('products-wrapper').addEventListener('input', function(e) {
    if (e.target.classList.contains('product-select') || e.target.classList.contains('quantity-input')) {
        updateSubtotal(e.target.closest('.product-row'));
    }
});

// Add Product Row
document.getElementById('add-product').addEventListener('click', function() {
    const wrapper = document.getElementById('products-wrapper');
    let options = '<option value="">-- Select Product --</option>';
    productsData.forEach(p => {
        options += `<option value="${p.id}" data-price="${p.price}">${p.name} - $${parseFloat(p.price).toFixed(2)}</option>`;
    });

    const row = document.createElement('div');
    row.classList.add('product-row', 'row', 'g-2', 'align-items-center', 'mb-2');
    row.innerHTML = `
        <div class="col-md-6">
            <select name="products[${productIndex}][id]" class="form-select product-select" required>
                ${options}
            </select>
        </div>
        <div class="col-md-2">
            <input type="number" name="products[${productIndex}][quantity]" class="form-control quantity-input" min="1" value="1" required>
        </div>
        <div class="col-md-2">
            <span class="subtotal badge bg-light text-dark w-100">$0.00</span>
        </div>
        <div class="col-md-2 text-end">
            <button type="button" class="btn btn-danger btn-sm remove-product">×</button>
        </div>
    `;
    wrapper.appendChild(row);
    productIndex++;
});

// Remove Product Row
document.getElementById('products-wrapper').addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-product')) {
        e.target.closest('.product-row').remove();
        updateTotal();
    }
});

// Init first row subtotal
updateSubtotal(document.querySelector('.product-row'));
</script>
@endsection
