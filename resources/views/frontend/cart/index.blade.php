@extends('frontend.layout')

@section('title', 'Shopping Cart')

@section('content')
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="mb-5 text-center fw-bold">🛒 Your Shopping Cart</h2>

        @if(session('success'))
            <div class="alert alert-success text-center mb-4">{{ session('success') }}</div>
        @endif

        @if(count($cart) > 0)
            <div class="table-responsive shadow-sm rounded bg-white mb-4">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="py-3 px-2">Image</th>
                            <th scope="col" class="py-3 px-2 text-start">Book Title</th>
                            <th scope="col" class="py-3 px-2">Price (Tk)</th>
                            <th scope="col" class="py-3 px-2">Quantity</th>
                            <th scope="col" class="py-3 px-2">Subtotal</th>
                            <th scope="col" class="py-3 px-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach($cart as $id => $item)
                            @php 
                                $subtotal = $item['price'] * $item['quantity']; 
                                $total += $subtotal; 
                            @endphp
                            <tr>
                                <td class="py-2 px-2">
                                    <img src="{{ asset('storage/' . $item['image']) }}" 
                                         alt="{{ $item['name'] }}" class="img-fluid rounded" style="height: 70px; width: auto;">
                                </td>
                                <td class="py-2 px-2 text-start">{{ $item['name'] }}</td>
                                <td class="py-2 px-2">{{ number_format($item['price'], 2) }}</td>
                                <td class="py-2 px-2 align-middle">
                                    <form action="{{ route('cart.update', $id) }}" method="POST" class="d-flex justify-content-center align-items-center gap-2">
                                        @csrf
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control form-control-sm" style="width:70px;">
                                        <button type="submit" class="btn btn-sm btn-outline-primary d-flex align-items-center justify-content-center">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    </form>
                                </td>

                                <td class="py-2 px-2 align-middle">{{ number_format($subtotal, 2) }}</td>

                                <td class="py-2 px-2 align-middle">
                                    <form action="{{ route('cart.remove', $id) }}" method="POST" class="d-flex justify-content-center align-items-center">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger d-flex align-items-center justify-content-center">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end fw-bold py-3 px-2">Total:</td>
                            <td colspan="2" class="fw-bold text-primary py-3 px-2">{{ number_format($total, 2) }} Tk</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="d-flex flex-wrap justify-content-between gap-3 mt-4">
                <a href="{{ route('books.index') }}" class="btn btn-primary px-4 py-2 d-flex align-items-center gap-2">
                    <i class="bi bi-arrow-left"></i> Continue Shopping
                </a>
                <a href="{{ route('checkout.index') }}" class="btn btn-outline-primary px-4 py-2 d-flex align-items-center gap-2">
                    Proceed to Checkout <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        @else
            <div class="text-center py-5">
                <h4 class="text-muted mb-3">Your cart is empty.</h4>
                <a href="{{ route('books.index') }}" class="btn btn-primary px-4 py-2">Browse Books</a>
            </div>
        @endif
    </div>
</section>
@endsection
