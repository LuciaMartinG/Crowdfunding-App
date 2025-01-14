@extends('layout')

@section('title', 'User List')

@section('content')

<div class="container">
    <div class="row">
        @foreach($userList as $user)
        
            <!-- <img src="{{ $user->image_url }}" alt=""> -->
            <h2>{{ $user->name }}</h2>
            <h3>{{ $user->email }}</h3>
            <h3>{{ $user->role }}</h3>
            <form action="/user/update" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $user->id }}">
                <select name="role" required>
                    <option value="entrepeneur" {{ $user->role === 'entrepeneur' ? 'selected' : '' }}>Entrepeneur</option>
                    <option value="investor" {{ $user->role === 'investor' ? 'selected' : '' }}>Investor</option>
                </select>
                <button type="submit">Update Role</button>
            </form>
        
        @endforeach
    </div>

    @if( ! $userList->onFirstPage() )
        <a href="{{$userList->previousPageUrl()}}" class="btn btn-primary">Previous page</a>
    @endif

    @if( ! $userList->onLastPage() )
        <a href="{{$userList->nextPageUrl()}}" class="btn btn-primary">Next page</a>
    @endif

</div>

@endsection