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
                <div class="row mb-4 align-items-center">
                    <div class="col-md-6">
                        <form action="{{ route('books.index') }}" method="GET" class="d-flex gap-2">
                             @if(request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                            <input type="text" name="search" class="form-control" placeholder="Search by name or author..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-outline-primary">Search</button>
                        </form>
                    </div>
                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                        <form action="{{ route('books.index') }}" method="GET" class="d-inline-block">
                             @if(request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            <select name="sort" class="form-select w-auto d-inline-block" onchange="this.form.submit()">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest Arrival</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            </select>
                        </form>
                    </div>
                </div>
            
                <!-- Books Grid -->
                <div class="row">
                    @forelse($books as $book)
                        <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                            <div class="product-item">
                                <figure class="product-style">
                                    <a href="{{ route('books.show', $book->id) }}">
                                        <img src="{{ $book->images->first() ? asset('storage/' . $book->images->first()->image) : asset('frontend/images/default-book.jpg') }}" class="product-item" alt="{{ $book->name }}">
                                    </a>
                                    <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $book->id }}">
                                        <button type="submit" class="add-to-cart" data-product-tile="add-to-cart">
                                            Add to Cart
                                        </button>
                                    </form>
                                    @if($book->discount)
                                        <div class="discount-badge">-{{ intval($book->discount) }}%</div>
                                    @endif
                                </figure>
                                <figcaption class="text-center">
									<h3>{{ $book->name }}</h3>
									<span>{{ $book->author }}</span>
                                     @if ($book->discount)
                                        <div class="item-price mt-2">
                                            <span class="prev-price text-muted text-decoration-line-through me-2">tk.{{ number_format($book->price, 2) }}</span>
                                            <span class="text-danger fw-bold">tk. {{ number_format($book->discounted_price, 2) }}</span>
                                        </div>
                                    @else
                                        <div class="item-price fw-bold mt-2">tk. {{ number_format($book->price, 2) }}</div>
                                    @endif
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
