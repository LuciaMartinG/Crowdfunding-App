@extends('layout')

@section('title', 'Project List')

@section('content')

<div class="container">
    <h1 id="welcomeTitle" class ="text-center"> Welcome to STEMFounding </h1>
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

    @if (Auth::check() && Auth::user()->role == 'admin') <!-- Solo muestra esto si el usuario está autenticado y es admin -->
        <div class="mb-4">
            <a href="/projects/pending/" class="btn btn-danger btn-sm mb-3 w-auto">Pending Projects</a>
        </div>
    @endif

    <!-- Formulario para Filtrar por Estado de Proyecto -->
    <div class="mb-4">
        <form action="{{ route('projects.list') }}" method="GET">
            <div class="row align-items-center">
                <div class="col-auto">
                    <label for="state" class="form-label">Filter by State:</label>
                </div>
                <div class="col-auto">
                    <select name="state" id="state" class="form-select" onchange="this.form.submit()" style="background-color: white;">
                        <option value="">All Projects</option>
                        <option value="active" @if(request('state') == 'active') selected @endif>Active Projects</option>
                        <option value="inactive" @if(request('state') == 'inactive') selected @endif>Inactive Projects</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Si el estado seleccionado es "inactive", mostrar un carrusel -->
    @if(request('state') == 'inactive')
        <div id="inactiveProjectsCarousel" class="carousel slide" data-bs-ride="carousel" style="max-width: 800px; margin: 0 auto;">
            <div class="carousel-inner">
                @foreach($projectList as $index => $project)
                    @if($project->state == 'inactive')
                        <div class="carousel-item @if($index == 0) active @endif">
                            <div class="card">
                                <img src="https://imgs.search.brave.com/5BFgigCdSRNQjIo1mp9FL0jaX6J6Rt4HdCPn3cfsvjQ/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9mcmFt/ZXJ1c2VyY29udGVu/dC5jb20vaW1hZ2Vz/L3JpNWp4ellReFBD/SHVyWmdYZU9scmVj/RWdkVS5wbmc" 
                                     class="card-img-top img-fluid" alt="{{ $project->title }}" >
                                <div class="card-body">
                                    <h5 class="card-title fw-bold">{{ $project->title }}</h5>
                                    <p class="card-text">{{ $project->description }}</p>
                                    <a href="project/detail/{{ $project->id }}" class="btn btn-secondary text-white">View Details</a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            <button class="carousel-control-prev position-absolute top-50 start-0 translate-middle-y" type="button" data-bs-target="#inactiveProjectsCarousel" data-bs-slide="prev" style="z-index: 10;">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next position-absolute top-50 end-0 translate-middle-y" type="button" data-bs-target="#inactiveProjectsCarousel" data-bs-slide="next" style="z-index: 10;">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
        </div>
    @else
        <!-- Si no se selecciona 'inactive', mostrar los proyectos en modo estándar -->
        <div class="row">
            @foreach($projectList as $project)
                @if($project->state == 'active' || $project->state == 'inactive')
                    <div class="col-12 col-md-6 col-lg-4 mb-4">
                        <div class="card cardProjectList shadow d-flex flex-column h-100">
                            <!-- Imagen del proyecto -->
                            <img src="https://imgs.search.brave.com/5BFgigCdSRNQjIo1mp9FL0jaX6J6Rt4HdCPn3cfsvjQ/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9mcmFt/ZXJ1c2VyY29udGVu/dC5jb20vaW1hZ2Vz/L3JpNWp4ellReFBD/SHVyWmdYZU9scmVj/RWdkVS5wbmc" 
                                 class="card-img-top img-fluid" alt="{{ $project->title }}" >
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold">{{ $project->title }}</h5>
                                <p class="card-text">{{ $project->description }}</p>
                                <a href="project/detail/{{ $project->id }}" class="btn btn-secondary text-white mt-auto">View Details</a>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif

    <!-- Paginación de Proyectos -->
    <div class="d-flex justify-content-between mt-4">
        @if( ! $projectList->onFirstPage() )
            <a href="{{ $projectList->previousPageUrl() }}" class="btn btn-primary">Previous page</a>
        @endif

        @if( ! $projectList->onLastPage() )
            <a href="{{ $projectList->nextPageUrl() }}" class="btn btn-primary">Next page</a>
        @endif
    </div>

</div>

@endsection
