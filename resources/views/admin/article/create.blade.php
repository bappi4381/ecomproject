@extends('admin.layouts')
@section('title', 'Add New Article')

@section('content')
<div class="container-fluid py-4">

    {{-- ✅ Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-uppercase mb-0" style="letter-spacing: 2px; color: #6f4e37;">
            <i class="bi bi-file-earmark-text me-2"></i> Add New Article
        </h4>
    </div>

    {{-- ✅ Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ✅ Article Form --}}
    <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- 🟤 Basic Information --}}
        <div class="card mb-3 border-0 shadow-sm rounded-3">
            <div class="card-header fw-semibold">
                <i class="bi bi-info-circle me-2"></i> Basic Information
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Title --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Title</label>
                        <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" required>
                    </div>

                    {{-- Category --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Content --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Content</label>
                    <div id="editor" style="height: 400px;"></div>
                    <textarea name="content" id="content" hidden></textarea>
                </div>

                <div class="row">
                    {{-- Publish Date --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Publish Date</label>
                        <input type="date" name="published_at" class="form-control" value="{{ old('published_at') }}">
                    </div>

                    {{-- Slug --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Slug</label>
                        <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug') }}" readonly>
                    </div>
                </div>
            </div>
        </div>

        {{-- 🟤 Article Image --}}
        <div class="card mb-3 border-0 shadow-sm rounded-3">
            <div class="card-header fw-semibold">
                <i class="bi bi-image me-2"></i> Article Image
            </div>
            <div class="card-body">
                <div id="image-drop-area" class="border rounded-3 text-center py-4" 
                     style="border:2px dashed #A75E30; cursor:pointer;">
                    <p class="mb-0 text-muted">Drag & Drop Image Here or Click to Upload</p>
                    <input type="file" name="image" accept="image/*" style="display:none;" id="image-input">
                </div>
                <div id="image-preview" class="d-flex flex-wrap mt-3"></div>
            </div>
        </div>

        {{-- Submit Button --}}
        <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-check-circle me-1"></i> Add Article
        </button>
    </form>
</div>

{{-- ✅ Include Quill --}}
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<script>
    // ✅ Initialize Quill Editor
    const quill = new Quill('#editor', { theme: 'snow' });

    // ✅ Save content before form submit
    document.querySelector('form').addEventListener('submit', function() {
        document.querySelector('#content').value = quill.root.innerHTML;
    });

    // ✅ Auto Slug Generation
    document.getElementById('title').addEventListener('input', function() {
        let slug = this.value
            .toLowerCase()
            .replace(/[^\w\s-]/g, '')   // Remove special characters
            .replace(/\s+/g, '-')       // Replace spaces with hyphens
            .replace(/--+/g, '-')       // Avoid multiple hyphens
            .trim('-');                 // Trim start/end hyphen
        document.getElementById('slug').value = slug;
    });

    // ✅ Drag & Drop Image Upload Preview
    const dropArea = document.getElementById('image-drop-area');
    const input = document.getElementById('image-input');
    const preview = document.getElementById('image-preview');

    dropArea.addEventListener('click', () => input.click());

    input.addEventListener('change', function () {
        previewFiles(this.files);
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, (e) => {
            e.preventDefault(); e.stopPropagation();
            dropArea.style.backgroundColor = '#e9f5ff';
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, (e) => {
            e.preventDefault(); e.stopPropagation();
            dropArea.style.backgroundColor = '';
        });
    });

    dropArea.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        input.files = files;
        previewFiles(files);
    });

    function previewFiles(files) {
        preview.innerHTML = '';
        Array.from(files).forEach(file => {
            if(!file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.width = 80;
                img.classList.add('rounded', 'me-2', 'mb-2', 'border');
                preview.appendChild(img);
            }
            reader.readAsDataURL(file);
        });
    }
</script>

{{-- ✅ Styling --}}
<style>
.btn-primary { background-color: #A75E30; border-color: #A75E30; transition: all 0.2s ease-in-out; }
.btn-primary:hover { background-color: #B46A3B; border-color: #B46A3B; transform: translateY(-1px); }
.card-header { background-color: #F3F2EC; color: #6f4e37; border-bottom: 1px solid #e6ddd3; }
#editor { background: #fff; border-radius: 8px; }
</style>

@endsection
