@extends('admin.layouts')
@section('title', isset($product) ? 'Edit Product' : 'Add New Product')

@section('content')
<div class="container-fluid py-3" style="max-width: 1200px;">
    <h4>Create New Order</h4>
        <p class="text-muted">Select customer and add products to create a new order.</p>

        {{-- Display validation errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>  
        @endif
        {{-- Display success message if any --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        {{-- Order creation form --}}

        <form action="{{ route('orders.store') }}" method="POST" id="order-form">
            @csrf
            <div class="mb-3">
                <label for="user_id">Select Customer</label>
                <select name="user_id" id="user_id" class="form-control">
                    @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>

            <h5>Products</h5>
            <div id="products-wrapper">
                <div class="product-item mb-2 d-flex gap-2 align-items-center">
                    <select name="products[0][product_id]" class="form-control product-select">
                        @foreach($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                            {{ $product->name }} - ${{ $product->price }}
                        </option>
                        @endforeach
                    </select>
                    <input type="number" name="products[0][quantity]" class="form-control quantity-input" placeholder="Qty" value="1" min="1">
                    <span class="subtotal">$0.00</span>
                </div>
            </div>
            <button type="button" id="add-product" class="btn btn-sm btn-secondary mb-3">Add More Product</button>

            <h5>Total: $<span id="total-price">0.00</span></h5>

            <button type="submit" class="btn btn-primary">Create Order</button>
        </form> 
       
</div>


{{-- Include necessary scripts --}}

<script>
let counter = 1;

function updateTotals() {
    let total = 0;
    document.querySelectorAll('#products-wrapper .product-item').forEach(item => {
        const select = item.querySelector('.product-select');
        const qty = parseInt(item.querySelector('.quantity-input').value) || 0;
        const price = parseFloat(select.selectedOptions[0].dataset.price) || 0;
        const subtotal = qty * price;
        item.querySelector('.subtotal').textContent = `$${subtotal.toFixed(2)}`;
        total += subtotal;
    });
    document.getElementById('total-price').textContent = total.toFixed(2);
}

// Update totals when quantity or product changes
document.getElementById('products-wrapper').addEventListener('input', updateTotals);
document.getElementById('products-wrapper').addEventListener('change', updateTotals);

// Add more product rows
document.getElementById('add-product').addEventListener('click', function(){
    let wrapper = document.getElementById('products-wrapper');
    let newItem = document.querySelector('.product-item').cloneNode(true);

    newItem.querySelectorAll('select,input').forEach(el => {
        let name = el.getAttribute('name').replace(/\d+/, counter);
        el.setAttribute('name', name);
        if(el.type === 'number') el.value = 1;
    });

    wrapper.appendChild(newItem);
    counter++;
    updateTotals();
});

// Initialize totals on page load
updateTotals();
</script>
@endsection
