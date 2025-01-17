@extends('layout')

@section('title', 'User List')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<div class="container">
@if (Auth::user()->role == 'admin')
    <div class="row">
        @foreach($userList as $user)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <img src="{{ $user->photo ?? 'https://via.placeholder.com/150' }}" class="card-img-top img-fluid" alt="{{ $user->name }}">
                    <div class="card-body">
                        <h5 class="card-title fs-5 fw-bold">{{ $user->name }}</h5>
                        <p class="card-text">Email: {{ $user->email }}</p>
                        <p class="card-text">Role: {{ ucfirst($user->role) }}</p>
                        <a href="user/detail/{{ $user->id }}" class="btn btn-secondary">View Details</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    

    @if( ! $userList->onFirstPage() )
        <a href="{{$userList->previousPageUrl()}}" class="btn btn-secondary">Previous page</a>
    @endif

    @if( ! $userList->onLastPage() )
        <a href="{{$userList->nextPageUrl()}}" class="btn btn-secondary">Next page</a>
    @endif

</div>
@endif
@endsection