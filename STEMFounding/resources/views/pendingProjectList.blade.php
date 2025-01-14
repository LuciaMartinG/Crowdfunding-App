@extends('layout')

@section('title', 'Pending Project List')

@section('content')

<div class="container">
    <h1>Pending Projects</h1>

    <!-- Verificar si hay proyectos pendientes -->
    @if($pendingProjectList->isEmpty())
        <p>No hay proyectos pendientes en este momento.</p>
    @else
        <div class="row">
            @foreach($pendingProjectList as $project)
                <a href="project/detail/{{ $project->id }}" class="card col-12 col-md-6 col-lg-3 mb-3">
                    <h2>{{ $project->title }}</h2>
                    <h3>{{ $project->description }}</h3>
                    <form action="/projects/{{ $project->id }}/updateState" method="POST">
                        @csrf
                        <!-- Botón para activar el proyecto -->
                        <button type="submit" name="state" value="active" class="btn btn-success mb-2">Activate</button>
                        
                        <!-- Botón para rechazar el proyecto -->
                        <button type="submit" name="state" value="rejected" class="btn btn-danger mb-2">Reject</button>
                    </form>
                </a>
            @endforeach
        </div>

        <!-- Paginación de Proyectos Pendientes -->
        @if( ! $pendingProjectList->onFirstPage() )
            <a href="{{ $pendingProjectList->previousPageUrl() }}" class="btn btn-primary">Previous page</a>
        @endif

        @if( ! $pendingProjectList->onLastPage() )
            <a href="{{ $pendingProjectList->nextPageUrl() }}" class="btn btn-primary">Next page</a>
        @endif
    @endif
</div>

@endsection