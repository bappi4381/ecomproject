@extends('admin.layouts')
@section('title', $article->title)

@section('content')
<div class="container py-4">

    {{-- Back Button --}}
    <div class="mb-3">
        <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Articles
        </a>
    </div>

    {{-- Article Card --}}
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-light text-dark fw-bold">
            <i class="bi bi-file-earmark-text me-2"></i> {{ $article->title }}
        </div>
        <div class="card-body">
            <p><strong>Category:</strong> {{ $article->category->name ?? '-' }}</p>
            <p><strong>Published At:</strong> 
                {{ $article->published_at ? \Carbon\Carbon::parse($article->published_at)->format('d M, Y') : '-' }}
            </p>
            <p><strong>Slug:</strong> {{ $article->slug }}</p>

            @if($article->image)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="img-fluid rounded border">
                </div>
            @endif

            <hr>
            <div>
                {!! $article->content !!}
            </div>
        </div>

        <div class="card-footer text-end">
            <a href="{{ route('articles.edit', $article) }}" class="btn btn-warning me-1">
                <i class="bi bi-pencil-square"></i> Edit
            </a>
            <form action="{{ route('articles.destroy', $article) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                    <i class="bi bi-trash"></i> Delete
                </button>
            </form>
        </div>
    </div>

</div>

<style>
    .card-header {
        background-color: #F3F2EC;
        color: #6f4e37;
    }
    .btn-warning {
        background-color: #FFC107;
        border-color: #FFC107;
    }
    .btn-warning:hover {
        background-color: #E0A800;
        border-color: #E0A800;
    }
</style>
@endsection
