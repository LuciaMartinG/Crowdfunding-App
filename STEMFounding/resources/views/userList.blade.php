@extends('layout')

@section('title', 'User List')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<div class="container">
    <div class="row">
        @foreach($userList as $user)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <img src="https://definicion.de/wp-content/uploads/2019/07/perfil-de-usuario.png" class="card-img-top img-fluid" alt="{{ $user->name }}">
                    <div class="card-body">
                        <h5 class="card-title fs-5 fw-bold">{{ $user->name }}</h5>
                        <p class="card-text">Email: {{ $user->email }}</p>
                        <p class="card-text">Role: {{ ucfirst($user->role) }}</p>
                        <form action="/user/update" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $user->id }}">
                            <div class="mb-2">
                                <label for="role-{{ $user->id }}" class="form-label">Change Role</label>
                                <select id="role-{{ $user->id }}" name="role" class="form-select" required>
                                    <option value="entrepeneur" {{ $user->role === 'entrepeneur' ? 'selected' : '' }}>Entrepeneur</option>
                                    <option value="investor" {{ $user->role === 'investor' ? 'selected' : '' }}>Investor</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-secondary">Update Role</button>
                        </form>
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

@endsection