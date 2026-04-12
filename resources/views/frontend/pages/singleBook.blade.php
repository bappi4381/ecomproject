@extends('frontend.layout')

@section('title', $book->title)

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row g-3 align-items-start">
            <!-- Image Section -->
            <div class="col-md-5">
                <div class="main-image mb-3 text-center">
                    <img src="{{ asset('storage/' . $book->images->first()->image) }}" 
                         id="mainBookImage"
                         alt="{{ $book->name }}" 
                         class="img-fluid rounded shadow-sm"
                         style="max-height: 450px; object-fit: cover;">
                </div>
                @if($book->images->count() > 1)
                <div class="image-gallery d-flex gap-2 justify-content-center overflow-auto pb-2">
                    @foreach($book->images as $image)
                        <img src="{{ asset('storage/' . $image->image) }}" 
                             class="img-thumbnail cursor-pointer" 
                             style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                             onclick="document.getElementById('mainBookImage').src = this.src">
                    @endforeach
                </div>
                @endif
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
                <p class="mb-2"><strong>Publisher:</strong> {{ $book->publisher ?? 'N/A' }}</p>
                <p class="mb-2"><strong>SKU:</strong> {{ $book->product_id }}</p>
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

        <!-- Related Products Section -->
        @if($relatedBooks->count() > 0)
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="fw-bold mb-4">Related Books</h3>
                <div class="row">
                    @foreach($relatedBooks as $related)
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="product-item">
                            <figure class="product-style">
                                <a href="{{ route('books.show', $related->id) }}">
                                    <img src="{{ asset('storage/' . $related->images->first()->image) }}" class="product-item" alt="{{ $related->name }}">
                                </a>
                                <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $related->id }}">
                                    <button type="button" class="add-to-cart" data-product-tile="add-to-cart" onclick="this.closest('form').submit();">
                                        Add to Cart
                                    </button>
                                </form>
                            </figure>
                            <figcaption>
                                <h3>{{ $related->name }}</h3>
                                <span>{{ $related->author }}</span>
                                <div class="item-price">tk. {{ $related->price }}</div>
                            </figcaption>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
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
