@extends('layout')

@section('title', 'Project List')

@section('content')

<div class="container">
    <div class="row">



        <div class="col-12 col-md-4">
            <img src="{{ $project->image_url }}" alt="">
        </div>
        <div class="col-12 col-md-8">
            <h2>{{ $project->title }}</h2>
            <h3> Max investment: {{ $project->max_investment }}</h3>
            <h3>Current investment: {{ $project->current_investment }}</h3>
            <p>{{ $project->description }}<p>
                
            @if (Auth::user()->role == 'admin')
            <a href="/project/delete/{{ $project->id }}" class="btn btn-danger btn-sm mb-3 w-auto" onclick="return confirm('¿Are you sure?');">Delete Project</a>
        @endif
        </div>
    </div>

    <!-- Seccion para enviar comentarios -->
    {{-- @auth
    <div class="row mt-4">
        <div class="col-12">
            <!-- Formulario para enviar un comentario -->
            <form action="/updates/create" method="POST">
                <!-- Token CSRF para proteger el formulario contra ataques CSRF -->
                @csrf
                
                <!-- Campo oculto para enviar el ID de la película -->
                <input type="hidden" name="projectId" value="{{ $project->id }}">
                
                <!-- Campo de texto para el comentario -->
                <div class="mb-3">
                    <label for="updateText" class="form-label">Add update</label>
                    <textarea class="form-control" id="updateText" name="updateText" rows="3" required></textarea>
                </div>
                
                <!-- Botón para enviar el formulario -->
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
    @endauth

    <!-- Mostrar comentarios existentes -->
    <div class="row mt-4">
        @foreach($movie->comments as $comment)

        <div class="card mb-4">
            <div class="card-body">
                <p>{{$comment->text}}</p>

                <div class="d-flex justify-content-between">
                    <div class="d-flex flex-row align-items-center">
                        <img src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/img%20(31).webp" alt="avatar" width="25" height="25" />
                        <!-- Enlace al detalle del usuario -->
                        <a href="/users/{{ $comment->user->id }}" class="small mb-0 ms-2">{{$comment->user->name}}</a>
                    </div>
                    
                    <!-- Enlace para eliminar comentario solo para administradores -->
                    @if (Auth::user()->role == 'admin')
                        <a href="/comments/delete/{{ $comment->id }}" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este comentario?');">Eliminar</a>
                    @endif
                </div>
            </div>
        </div>

        @endforeach
    </div>--}}
</div>

@endsection 
