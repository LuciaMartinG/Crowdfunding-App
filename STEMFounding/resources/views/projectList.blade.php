@extends('layout')

@section('title', 'Project List')

@section('content')

<div class="container">

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

    <div class="row">
        @foreach($projectList as $project)
            @if($project->state == 'active' || $project->state == 'inactive')
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card">
                        <!-- Imagen del proyecto -->
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
