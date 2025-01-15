@extends('layout')

@section('title', 'User Details')

@section('content')

<div class="container">
    <!-- Información del usuario -->
    <div class="mb-4">
        <h1 class="mb-2">{{ $user->name }}</h1>
        <h3 class="text-muted">{{ $user->email }}</h3>
    </div>

      <!-- Botón para editar perfil -->
    @if(Auth::user() && (Auth::user()->role == 'entrepreneur' || Auth::user()->role == 'investor'))
        <!-- Botón para editar perfil solo visible para "emprendedor" e "inversor" -->
        <div class="mb-4">
            <a href="/user/update" class="btn btn-primary">Edit Profile</a> <!-- Redirige a la página de edición del perfil -->
        </div>
    @endif

    <!-- Proyectos del usuario -->
    <h2 class="mb-4">Projects by {{ $user->name }}</h2>

    @if($userProjects->isEmpty())
        <p>No projects found for this user.</p>
    @else
        <div class="row">
            @foreach($userProjects as $project)
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card">
                        <!-- Imagen del proyecto -->
                        <img src="{{ $project->image_url ?? 'https://via.placeholder.com/300x200' }}" 
                             class="card-img-top img-fluid" 
                             alt="{{ $project->title }}" 
                             style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ $project->title }}</h5>
                            <p class="card-text">{{ $project->description }}</p>
                            <a href="/project/detail/{{ $project->id }}" class="btn btn-secondary">View Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@endsection
