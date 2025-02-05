@extends('layout')

@section('title', 'Pending Project List')

@section('content')

<div class="container">
@if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
    <h1 class="mb-4">Pending Projects</h1>

    <!-- Verificar si hay proyectos pendientes -->
    @if($pendingProjectList->isEmpty())
        <p>No hay proyectos pendientes en este momento.</p>
    @else
        <div class="row">
            @foreach($pendingProjectList as $project)
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card">
                        <!-- Imagen del proyecto -->
                        <img src="https://imgs.search.brave.com/xm7BO7sx2hZl7ga4QggJ0yG-a8YemFOlL2_LflG3uuk/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly92ZW50/dXJlc2NhbXAuY29t/L3dwLWNvbnRlbnQv/dXBsb2Fkcy8yMDI0/LzA3L2Nyb3dkZnVu/ZGluZy1jb25jZXB0/LWRyYXdpbmctd2l0/aC1yZWQtcGVuLWlu/LW5vdGVwLTIwMjMt/MTEtMjctMDUtMjkt/NDYtdXRjLXNjYWxl/ZC5qcGc" class="card-img-top img-fluid" alt="{{ $project->title }}">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ $project->title }}</h5>
                            <p class="card-text">{{ $project->description }}</p>
                            <form action="/projects/{{ $project->id }}/updateState" method="POST">
                                @csrf
                                <a href="/project/detail/{{ $project->id }}" class="btn btn-info mb-2">View Details</a>
                                <!-- Botón para activar el proyecto -->
                                <button type="submit" name="state" value="active" class="btn btn-success mb-2">Activate</button>
                                
                                <!-- Botón para rechazar el proyecto -->
                                <button type="submit" name="state" value="rejected" class="btn btn-danger mb-2">Reject</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación de Proyectos Pendientes -->
        <div class="d-flex justify-content-between mt-4">
            @if( ! $pendingProjectList->onFirstPage() )
                <a href="{{ $pendingProjectList->previousPageUrl() }}" class="btn btn-primary">Previous page</a>
            @endif

            @if( ! $pendingProjectList->onLastPage() )
                <a href="{{ $pendingProjectList->nextPageUrl() }}" class="btn btn-primary">Next page</a>
            @endif
        </div>
    @endif
</div>

@endsection
