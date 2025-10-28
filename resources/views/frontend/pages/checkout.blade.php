@extends('frontend.layout')

@section('title', 'Checkout')

@section('content')
<section class="py-5 bg-light">
    <div class="container">
        <h1 class="mb-5 text-center fw-bold">Checkout</h1>

        {{-- Success/Error messages --}}
        @if(session('success'))
            <div class="alert alert-success text-center mb-4">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger text-center mb-4">{{ session('error') }}</div>
        @endif

        <div class="row g-4">
            <!-- Billing & User Info -->
            <div class="col-md-6">
                <div class="card shadow-sm rounded p-4 bg-white">
                    <h4 class="mb-4 fw-bold">Billing Information</h4>
                    <form action="{{ route('checkout.placeOrder') }}" method="POST" id="checkoutForm">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Full Name</label>
                            <input type="text" class="form-control" name="name" id="name" 
                                   value="{{ old('name', Auth::user()->name ?? '') }}" required>
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control" name="email" id="email" 
                                   value="{{ old('email', Auth::user()->email ?? '') }}" required>
                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label fw-semibold">Phone</label>
                            <input type="text" class="form-control" name="phone" id="phone" 
                                   value="{{ old('phone', Auth::user()->phone ?? '') }}" required>
                            @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="city" class="form-label fw-semibold">City</label>
                            <select name="city" class="form-select" id="city" required>
                                <option value="Dhaka" {{ old('city') == 'Dhaka' ? 'selected' : '' }}>Dhaka</option>
                                <option value="Other" {{ old('city') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('city') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="shipping_address" class="form-label fw-semibold">Address</label>
                            <textarea class="form-control" name="shipping_address" id="shipping_address" rows="3" required>{{ old('shipping_address') }}</textarea>
                            @error('shipping_address') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <h5 class="mt-4 mb-3 fw-bold">Payment Method</h5>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" {{ old('payment_method', 'cod') == 'cod' ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="cod">Cash on Delivery</label>
                        </div>
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="radio" name="payment_method" id="online" value="online" {{ old('payment_method') == 'online' ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="online">Online Payment</label>
                        </div>
                        @error('payment_method') <small class="text-danger">{{ $message }}</small> @enderror

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                            Place Order
                        </button>
                    </form>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="col-md-6">
                <div class="card shadow-sm rounded p-4 bg-white">
                    <h4 class="mb-4 fw-bold">Order Summary</h4>

                    @php 
                        $cart = session()->get('cart', []);
                        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
                        $selectedCity = old('city', 'Dhaka');
                        $deliveryCharge = ($selectedCity == 'Dhaka') ? 80 : 110;
                        $grandTotal = $subtotal + $deliveryCharge;
                    @endphp

                    @if(count($cart) > 0)
                        <ul class="list-group mb-3">
                            @foreach($cart as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="img-fluid rounded" style="height:50px;">
                                        <span>{{ $item['name'] }} (x{{ $item['quantity'] }})</span>
                                    </div>
                                    <span>{{ number_format($item['price'] * $item['quantity'], 2) }} Tk</span>
                                </li>
                            @endforeach

                            <li class="list-group-item d-flex justify-content-between">
                                <span>Subtotal</span>
                                <span id="subtotal">{{ number_format($subtotal, 2) }} Tk</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Delivery Charge</span>
                                <span id="deliveryCharge">{{ number_format($deliveryCharge, 2) }} Tk</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between fw-bold">
                                <span>Total</span>
                                <span id="grandTotal">{{ number_format($grandTotal, 2) }} Tk</span>
                            </li>
                        </ul>
                    @else
                        <p class="text-muted">Your cart is empty.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Dynamic delivery and total update --}}
<script>
    const citySelect = document.getElementById('city');
    const deliverySpan = document.getElementById('deliveryCharge');
    const totalSpan = document.getElementById('grandTotal');
    const subtotal = {{ $subtotal }};

    citySelect.addEventListener('change', function() {
        let delivery = this.value === 'Dhaka' ? 80 : 110;
        deliverySpan.textContent = delivery.toFixed(2) + ' Tk';
        totalSpan.textContent = (subtotal + delivery).toFixed(2) + ' Tk';
    });
</script>
@endsection
