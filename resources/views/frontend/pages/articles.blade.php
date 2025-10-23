@extends('frontend.layout')

@section('title', 'Articles')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row g-4">

            <!-- Sidebar -->
            <aside class="col-lg-3 col-md-4">
                <div class="bg-light p-4 rounded shadow-sm sticky-top" style="top: 100px;">
                    <h5 class="mb-3 fw-bold">Categories</h5>
                    <ul class="list-group mb-4">
                        @foreach($categories as $category)
                            <li class="list-group-item py-2 px-3">
                                <a href="{{ route('frontend.articles', ['category' => $category->id]) }}"
                                   class="text-decoration-none d-block {{ request('category') == $category->id ? 'fw-bold text-primary' : 'text-dark' }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <!-- Optional: Search Filter -->
                    <h5 class="mb-3 fw-bold">Search</h5>
                    <form action="{{ route('frontend.articles') }}" method="GET">
                        <div class="mb-3">
                            <input type="text" name="search" class="form-control" placeholder="Search articles..." value="{{ request('search') }}">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Search</button>
                    </form>
                </div>
            </aside>

            <!-- Articles Grid -->
            <div class="col-lg-9 col-md-8">
                <div class="row g-4">
                    @forelse($articles as $article)
                        <div class="col-sm-6 col-lg-4">
                            <div class="product-item shadow-sm rounded">
                                <figure class="product-style">
                                    <a href="{{ route('frontend.articles.show', $article->slug) }}">
                                        <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="img-fluid  w-100" style="height:220px; object-fit:cover;">
                                    </a>
                                </figure>
                                <figcaption class="p-3">
                                    <h5 class="fw-semibold">
                                        <a href="{{ route('frontend.articles.show', $article->slug) }}" class="text-dark">
                                            {{ Str::limit($article->title, 60) }}
                                        </a>
                                    </h5>
                                    <small class="text-muted">{{ $article->created_at->format('M d, Y') }}</small></br>
                                    <span>{{ $article->category->name }}</span>
                                </figcaption>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center">
                            <p class="text-muted fs-5">No Articles Available</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="row mt-4">
                    <div class="col-12 d-flex justify-content-center">
                        {{ $articles->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
