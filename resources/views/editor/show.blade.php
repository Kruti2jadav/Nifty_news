@extends('components.app')

@section('content')
<div class="card shadow-sm p-4 col-md-6 mx-auto">
    <h4 class="fw-bold mb-4">My Profile</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="mb-3">
            <label>Password <small>(leave blank if not changing)</small></label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="mb-3">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>

        <div class="mb-3">
            <label>Avatar</label><br>
            @if($user->avatar)
                <img src="{{ asset($user->avatar) }}" alt="avatar" style="height:80px; margin-bottom:10px;"><br>
            @endif
            <input type="file" name="avatar" class="form-control">
        </div>

        <div class="mb-3">
            <label>Languages <small>(comma-separated)</small></label>
            <input type="text" name="language" class="form-control" value="{{ old('language', $user->language) }}">
        </div>

        <div class="mb-3">
            <label>Role</label>
            <input type="text" class="form-control" value="{{ $user->role }}" disabled>
        </div>

        <div class="mb-3">
            <label>Created At</label>
            <input type="text" class="form-control" value="{{ $user->created_at }}" disabled>
        </div>

        <button type="submit" class="btn btn-success">Update Profile</button>
    </form>
</div>
@endsection