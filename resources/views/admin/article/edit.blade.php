@extends('admin.layouts')
@section('title', 'Edit Article')

@section('content')
<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-uppercase mb-0" style="letter-spacing: 2px; color: #6f4e37;">
            <i class="bi bi-file-earmark-text me-2"></i> Edit Article
        </h4>
    </div>

    <p class="mb-4 text-muted"><strong>Article ID:</strong> {{ $article->id }}</p>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('articles.update', $article) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Basic Information --}}
        <div class="card mb-3 border-0 shadow-sm rounded-3">
            <div class="card-header fw-semibold">
                <i class="bi bi-info-circle me-2"></i> Basic Information
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $article->title) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $article->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Content</label>
                    <div id="editor" style="height: 400px;">{!! old('content', $article->content) !!}</div>
                    <textarea name="content" id="content" hidden></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Slug</label>
                        <input type="text" name="slug" class="form-control" value="{{ old('slug', $article->slug) }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="draft" {{ old('status', $article->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $article->status) == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold">Publish Date</label>
                        <input type="date" name="published_at" class="form-control" value="{{ old('published_at', $article->published_at ? $article->published_at->format('Y-m-d') : '') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Article Image --}}
        <div class="card mb-3 border-0 shadow-sm rounded-3">
            <div class="card-header fw-semibold">
                <i class="bi bi-image me-2"></i> Article Image
            </div>
            <div class="card-body">
                <div id="image-drop-area" class="border rounded-3 text-center py-4" style="border:2px dashed #A75E30; cursor:pointer;">
                    <p class="mb-0 text-muted">Drag & Drop Image Here or Click to Upload</p>
                    <input type="file" name="image" accept="image/*" style="display:none;" id="image-input">
                </div>
                <div id="image-preview" class="d-flex flex-wrap mt-3">
                    @if($article->image)
                        <img src="{{ asset('storage/'.$article->image) }}" width="120" class="rounded shadow-sm me-2 mb-2">
                    @endif
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-check-circle me-1"></i> Update Article
        </button>
    </form>
</div>

@endsection
