@extends('frontend.layout')

@section('title', $book->title)

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row g-3 align-items-start">
            <!-- Image Section -->
            <div class="col-md-5 text-center">
                <img src="{{ asset('storage/' . $book->images->first()->image) }}" 
                     alt="{{ $book->name }}" 
                     class="img-fluid rounded shadow-sm"
                     style="max-height: 450px; object-fit: cover;">
            </div>

            <!-- Info Section -->
            <div class="col-md-7">
                <h2 class="fw-bold mb-2">{{ $book->name }}</h2>
                <p class="text-muted mb-1 fs-6">by {{ $book->author }}</p>

                <div class="d-flex align-items-center mb-3">
                    <h4 class="text-primary fw-bold mb-0">${{ number_format($book->price, 2) }}</h4>
                    @if($book->discount)
                        <span class="badge bg-danger ms-2">-{{ $book->discount }}%</span>
                    @endif
                </div>

                <p class="mb-2"><strong>Category:</strong> {{ $book->category->name ?? 'Uncategorized' }}</p>
                <p class="mb-2"><strong>Stock:</strong> 
                    <span class="{{ $book->stock > 0 ? 'text-success' : 'text-danger' }}">
                        {{ $book->stock > 0 ? $book->stock . ' available' : 'Out of stock' }}
                    </span>
                </p>

                <div class="mt-4 d-flex flex-wrap gap-3">

                    <!-- Add to Cart Button -->
                    <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $book->id }}">
                        <button type="submit"
                                class="btn btn-primary d-inline-flex align-items-center justify-content-center px-4 py-2 fw-semibold text-white shadow-sm action-btn">
                                 Add to Cart
                        </button>
                    </form>

                    <!-- Back Button -->
                    <form action="{{ route('books.index') }}" method="GET" class="d-inline">
                        @csrf
                        <button type="submit"
                                class="btn btn-outline-primary d-inline-flex align-items-center justify-content-center px-4 py-2 fw-semibold shadow-sm action-btn">
                                 Back to Books
                        </button>
                    </form>
                    
                </div>
            </div>
        </div>

        <!-- Description Section -->
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3 fs-5 ">Book Description</h5>
                        <hr class="my-2 text-secondary opacity-100 w-25 border-2">

                        <p class="text-muted" style="line-height: 1.8;">
                            {{ $book->description ?? 'No description available.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

{{-- <style>
    .action-btn {
        min-width: 180px;             /* Same width for both buttons */
        font-weight: 600;             /* Bold text */
        transition: transform 0.2s, box-shadow 0.2s;
    }

    /* Hover effect */
    .action-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    } 
</style> --}}
