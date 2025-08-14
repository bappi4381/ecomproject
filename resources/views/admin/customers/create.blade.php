@extends('admin.layouts')
@section('title', 'Create User')

@section('content')
<div class="container mt-4">
    <h2>Create User</h2>

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}">
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control">
            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>

        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
        </div>

        <div class="mb-3">
            <label>Address</label>
            <textarea name="address" class="form-control">{{ old('address') }}</textarea>
        </div>

        <div class="mb-3">
            <label>City</label>
            <input type="text" name="city" class="form-control" value="{{ old('city') }}">
        </div>

        <div class="mb-3">
            <label>State</label>
            <input type="text" name="state" class="form-control" value="{{ old('state') }}">
        </div>

        <div class="mb-3">
            <label>Postal Code</label>
            <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code') }}">
        </div>

        <div class="mb-3">
            <label>Country</label>
            <input type="text" name="country" class="form-control" value="{{ old('country') }}">
        </div>

        <button type="submit" class="btn btn-success">Create User</button>
    </form>
</div>
@endsection
