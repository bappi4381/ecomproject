@extends('user.layout')
@section('title', 'User Dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <h4 class="mb-4 fw-bold text-primary">
                        <i class="bi bi-speedometer2 me-2"></i> User Panel
                    </h4>
                    <p>Welcome to your dashboard! Use the sidebar to navigate through your account options.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection