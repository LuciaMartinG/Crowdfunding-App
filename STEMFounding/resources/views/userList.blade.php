@extends('layout')

@section('title', 'User List')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<div class="container">
@if (Auth::user()->role == 'admin')

    <!-- Formulario para Filtrar por Estado de Usuario -->
    <div class="mb-4">
        <form action="{{ route('users.list') }}" method="GET">
            <div class="row align-items-center">
                <div class="col-auto">
                    <label for="banned" class="form-label">Filter by Ban Status:</label>
                </div>
                <div class="col-auto">
                    <select name="banned" id="banned" class="form-select" onchange="this.form.submit()" style="background-color: white;">
                        <option value="">All Users</option>
                        <option value="0" @if(request('banned') === '0') selected @endif>Not Banned</option>
                        <option value="1" @if(request('banned') === '1') selected @endif>Banned</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <div class="row">
        @forelse($userList as $user)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <img src="{{ $user->photo ?? 'https://via.placeholder.com/150' }}" class="card-img-top img-fluid" alt="{{ $user->name }}">
                    <div class="card-body">
                        <h5 class="card-title fs-5 fw-bold">{{ $user->name }}</h5>
                        <p class="card-text">Email: {{ $user->email }}</p>
                        <p class="card-text">Role: {{ ucfirst($user->role) }}</p>
                        <p class="card-text">Status: {{ $user->banned ? 'Banned' : 'Not Banned' }}</p>
                        <p class="card-text">Register at: {{ $user->created_at }}</p>
                        <a href="user/detail/{{ $user->id }}" class="btn btn-secondary">View Details</a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">No users found for the selected filter.</p>
        @endforelse
    </div>
    
    <!-- Paginación -->
    @if( ! $userList->onFirstPage() )
        <a href="{{$userList->previousPageUrl()}}" class="btn btn-secondary">Previous page</a>
    @endif

    @if( ! $userList->onLastPage() )
        <a href="{{$userList->nextPageUrl()}}" class="btn btn-secondary">Next page</a>
    @endif

</div>
@endif
@endsection
