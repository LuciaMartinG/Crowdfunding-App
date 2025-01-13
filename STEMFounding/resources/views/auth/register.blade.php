@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center">
<div class="card shadow p-4">
        <div class="text-center mb-4">
            <div class="d-flex justify-content-center align-items-center rounded-circle bg-primary">
                <span class="text-white fs-1">+</span>
            </div>
            <h2 class="mt-3" style="color: $text-color;">Register</h2>
        </div>
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="mb-3">
                <label for="name" class="form-label">{{ __('Name') }}</label>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                       name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                       name="email" value="{{ old('email') }}" required autocomplete="email">
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">{{ __('Password') }}</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                       name="password" required autocomplete="new-password">
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                <input id="password-confirm" type="password" class="form-control" 
                       name="password_confirmation" required autocomplete="new-password">
            </div>

            <!-- Role -->
            <div class="mb-4">
                <label for="role" class="form-label">{{ __('Register as an...') }}</label>
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-primary w-45">{{ __('Investor') }}</button>
                    <button type="button" class="btn btn-outline-primary w-45">{{ __('Founder') }}</button>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-block">{{ __('Register') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection