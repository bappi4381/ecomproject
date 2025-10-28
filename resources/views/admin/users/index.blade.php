@extends('admin.layouts')
@section('title', 'User List')

@section('content')
<div class="container py-4">
    <div class="row align-items-center mb-4">
        <!-- Left: User List Title -->
        <div class="col-3">
            <h4 class="fw-bold text-uppercase mb-0" style="letter-spacing: 1px; color:#6f4e37;">
                <i class="bi bi-people me-2"></i> User List
            </h4>
        </div>

        <!-- Middle: Search Input -->
        <div class="col-6">
            <form action="{{ route('users.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control rounded-start shadow-sm" 
                    placeholder="Search by name, email, phone..." 
                    value="{{ request('search') }}" autocomplete="off" style="height:45px;">
                <button type="submit" class="btn btn-primary rounded-end shadow-sm ms-1" style="height:45px;">
                    <i class="bi bi-search"></i>
                </button>
                @if(request('search'))
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary ms-1 shadow-sm" style="height:45px;">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Right: Add New User Button -->
        <div class="col-3 text-end">
            <a href="{{ route('users.create') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Add New User
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

    {{-- Users Table --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Country</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td width="10%" >{{ $user->user_id }}</td>
                        <td class="text-start">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?? '-' }}</td>
                        <td>{{ $user->city ?? '-' }}</td>
                        <td>{{ $user->state ?? '-' }}</td>
                        <td>{{ $user->country ?? '-' }}</td>
                        <td class="d-flex justify-content-center gap-1">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning mb-1">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-primary mb-1">
                                <i class="bi bi-eye"></i>
                            </a>
                            <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger mb-1">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $users->links() }}
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
