@extends('frontend.layout')

@section('title','Books')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Left Sidebar -->
            <aside class="col-lg-3 col-md-4">
                <div class="bg-light p-4 rounded shadow-sm sticky-top" style="top: 100px;">
                    <h5 class="mb-3 fw-bold">Filter by Category</h5>
                    <ul class="list-group mb-4">
                        @foreach($categories as $category)
                            <li class="list-group-item py-2 px-3">
                                <a href="{{ route('books.index', ['category' => $category->id]) }}" class="text-decoration-none text-dark d-block">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <h5 class="mb-3 fw-bold">Filter by Price</h5>
                    <form action="{{ route('books.index') }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label">Min Price</label>
                            <input type="number" name="min_price" class="form-control" placeholder="0" value="{{ request('min_price') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Max Price</label>
                            <input type="number" name="max_price" class="form-control" placeholder="1000" value="{{ request('max_price') }}">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Apply Filter</button>
                    </form>
                </div>
            </aside>

            <!-- Books Grid -->
            <div class="col-lg-9 col-md-8">
                <!-- Section Header -->
            
                <!-- Books Grid -->
                <div class="row">
                    @forelse($books as $book)
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="product-item">
                                <figure class="product-style">
                                    <a href="{{ route('books.show', $book->id) }}">
                                        <img src="{{ asset('storage/' . $book->images->first()->image) }}" class="product-item" alt="{{ $book->title }}">
                                    </a>
                                    <button type="button"  class="add-to-cart" data-product-tile="add-to-cart">
                                        Add to Cart
                                    </button>
                                </figure>
                                <figcaption>
									<h3>{{ $book->name }}</h3>
									<span>{{ $book->author }}</span>
									<div class="item-price">tk. {{ $book->price }}</div>
								</figcaption>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center">
                            <p class="text-muted fs-5">No books found.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="row mt-4">
                    <div class="col-12 d-flex justify-content-center">
                        {{ $books->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>

        </div> <!-- row -->
    </div> <!-- container -->
</section>
@endsection
