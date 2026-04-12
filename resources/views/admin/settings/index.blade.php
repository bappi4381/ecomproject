@extends('admin.layouts')
@section('title', 'General Settings')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <h4 class="fw-bold text-uppercase mb-4" style="color:#6f4e37;">
                <i class="bi bi-gear-fill me-2"></i> General Settings
            </h4>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    <!-- Site Basic Info -->
                    <div class="col-md-6">
                        <h5 class="fw-bold mb-3">Site Information</h5>
                        <div class="mb-3">
                            <label class="form-label">Site Name</label>
                            <input type="text" name="site_name" class="form-control" value="{{ $settings['site_name'] ?? 'BookSaw' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Site Description</label>
                            <textarea name="site_description" class="form-control" rows="3">{{ $settings['site_description'] ?? '' }}</textarea>
                        </div>
                         <div class="mb-3">
                            <label class="form-label">Support Email</label>
                            <input type="email" name="contact_email" class="form-control" value="{{ $settings['contact_email'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact Phone</label>
                            <input type="text" name="contact_phone" class="form-control" value="{{ $settings['contact_phone'] ?? '' }}">
                        </div>
                    </div>

                    <!-- Social Links -->
                    <div class="col-md-6">
                        <h5 class="fw-bold mb-3">Social Media Links</h5>
                        <div class="mb-3">
                            <label class="form-label">Facebook URL</label>
                            <input type="url" name="facebook_url" class="form-control" value="{{ $settings['facebook_url'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Twitter/X URL</label>
                            <input type="url" name="twitter_url" class="form-control" value="{{ $settings['twitter_url'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Instagram URL</label>
                            <input type="url" name="instagram_url" class="form-control" value="{{ $settings['instagram_url'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">YouTube URL</label>
                            <input type="url" name="youtube_url" class="form-control" value="{{ $settings['youtube_url'] ?? '' }}">
                        </div>
                    </div>

                    <div class="col-12 mt-4 text-end">
                        <button type="submit" class="btn btn-primary px-5 shadow-sm">
                            Save All Settings
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .btn-primary {
        background-color: #6f4e37;
        border-color: #6f4e37;
    }
    .btn-primary:hover {
        background-color: #5a3f2d;
        border-color: #5a3f2d;
    }
    .form-control:focus {
        border-color: #6f4e37;
        box-shadow: 0 0 0 0.25rem rgba(111, 78, 55, 0.25);
    }
</style>
@endsection
