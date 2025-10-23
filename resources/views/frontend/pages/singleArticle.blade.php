@extends('frontend.layout')

@section('title', $article->title)

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Article Content -->
            <div class="col-lg-9">
                <article class="mb-5">
                    <h1 class="fw-bold mb-3">{{ $article->title }}</h1>
                    <div class="mb-3 text-muted">
                        <span>Published on: {{ $article->created_at->format('M d, Y') }}</span> |
                        <span> {{ $article->category->name }}</span>
                    </div>

                    @if($article->image)
                        <figure class="mb-4">
                            <img src="{{ asset('storage/' . $article->image) }}" 
                                 alt="{{ $article->title }}" 
                                 class="img-fluid rounded w-100" 
                                 style="max-height: 400px; object-fit: cover;">
                        </figure>
                    @endif

                    <div class="article-content">
                        {!! $article->content !!}
                    </div>
                </article>
            </div>

            <!-- Sidebar -->
            <aside class="col-lg-3">
                <div class="bg-light p-4 rounded shadow-sm sticky-top" style="top: 100px;">
                    <h5 class="mb-4 fw-bold text-uppercase">Other Articles</h5>
                    <ul class="list-group list-group-flush">
                        @foreach($otherArticles as $other)
                            <li class="list-group-item px-0 py-2 border-0">
                                <a href="{{ route('frontend.articles.show', $other->slug) }}" 
                                class="d-flex align-items-center text-decoration-none text-dark hover-primary">
                                    <div class="flex-grow-1 ms-3">
                                        {{ Str::limit($other->title, 25) }}
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </aside>
        </div>
    </div>
</section>
<style>
    .hover-primary:hover {
        color: #A75E30 !important; /* Bootstrap primary color */
    }
    .list-group-item a {
        transition: all 0.2s ease-in-out;
    }
</style>
@endsection
