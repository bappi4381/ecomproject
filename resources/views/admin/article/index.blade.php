@extends('admin.layouts')
@section('title', 'Article List')

@section('content')
<div class="container py-4">
    <div class="row align-items-center mb-4">
        <!-- Left: Article List Title -->
        <div class="col-3">
            <h4 class="fw-bold text-uppercase mb-0" style="letter-spacing: 1px; color:#6f4e37;">
                <i class="bi bi-newspaper me-2"></i> Article List
            </h4>
        </div>

        <!-- Middle: Modern Search Input -->
        <div class="col-6">
            <form action="{{ route('articles.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control rounded-start shadow-sm" 
                    placeholder="Search by title, category..." 
                    value="{{ request('search') }}" autocomplete="off" style="height:45px;">
                <button type="submit" class="btn btn-primary rounded-end shadow-sm ms-1" style="height:45px;">
                    <i class="bi bi-search"></i>
                </button>
                @if(request('search'))
                    <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary ms-1 shadow-sm" style="height:45px;">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Right: Add New Article Button -->
        <div class="col-3 text-end">
            <a href="{{ route('articles.create') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Add New Article
            </a>
        </div>
    </div>

    {{-- Success message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Articles Table --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th style="width: 5%">ID</th>
                    <th style="width: 20%" >Title</th>
                    <th style="width: 10%">Category</th>
                    <th style="width: 10%">Publish Date</th>
                    <th style="width: 15%">Slug</th>
                    <th style="width: 10%">Status</th>
                    <th style="width: 10%">Image</th>
                    <th style="width: 10%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $article)
                    <tr class="text-center">
                        <td>{{ $article->id }}</td>
                        <td class="text-start">{{ $article->title }}</td>
                        <td>{{ $article->category->name ?? '-' }}</td>
                        <td>{{ optional($article->published_at)->format('Y-m-d') ?? '-' }}</td>
                        <td>{{ $article->slug }}</td>
                        <td>{{ $article->status ?? 'Draft' }}</td>
                        <td>
                            @if($article->image)
                                <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" width="60" class="rounded border">
                            @else
                                <em>No image</em>
                            @endif
                        </td>
                        <td class="">
                            <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-primary mb-1">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('articles.edit', $article) }}" class="btn btn-sm btn-warning mb-1">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="{{ route('articles.destroy', $article) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Are you sure?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No articles found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $articles->links() }}
    </div>
</div>

<style>
    .table td, .table th {
        vertical-align: middle;
    }
    .btn-primary {
        background-color: #A75E30;
        border-color: #A75E30;
    }
    .btn-primary:hover {
        background-color: #B46A3B;
        border-color: #B46A3B;
    }
</style>
@endsection
