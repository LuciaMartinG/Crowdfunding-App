@extends('layout')

@section('title', 'Edit Profile')

@section('content')
<div class="container">
    <h1>Edit Profile</h1>

    <form action="/user/update" method="POST" class="form-group">
        @csrf
        <input type="hidden" name="id" value="{{ $user->id }}">

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" class="form-control bg-white" value="{{ old('name', $user->name) }}">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control bg-white" value="{{ old('email', $user->email) }}">
        </div>

        <div class="mb-3">
            <label for="photo" class="form-label">Profile Photo</label>
            <input type="text" name="photo" class="form-control bg-white" value="{{ old('photo', $user->photo) }}">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">New Password (optional)</label>
            <input type="password" name="password" class="form-control bg-white">
        </div>

      
        <button type="submit" class="btn btn-secondary text-white">Save Changes</button>
        
    </form>
</div>
@endsection
