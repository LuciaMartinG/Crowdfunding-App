@extends('layout')

@section('title', 'My Investments')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">My Investments</h1>

    @if($projects->isEmpty())
        <p class="text-muted">You haven't invested in any projects yet.</p>
    @else
        <div class="row">
            @foreach($projects as $project)
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card">
                        <!-- Imagen del proyecto -->
                        <img src="https://imgs.search.brave.com/5BFgigCdSRNQjIo1mp9FL0jaX6J6Rt4HdCPn3cfsvjQ/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9mcmFt/ZXJ1c2VyY29udGVu/dC5jb20vaW1hZ2Vz/L3JpNWp4ellReFBD/SHVyWmdYZU9scmVj/RWdkVS5wbmc" 
                             class="card-img-top img-fluid" alt="{{ $project->title }}">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ $project->title }}</h5>
                            <p class="card-text">{{ $project->description }}</p>
                            <a href="/project/detail/{{ $project->id }}" class="btn btn-secondary text-white">View Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación de Proyectos -->
        <div class="d-flex justify-content-between mt-4">
            @if( ! $projects->onFirstPage() )
                <a href="{{ $projects->previousPageUrl() }}" class="btn btn-primary">Previous page</a>
            @endif

            @if( ! $projects->onLastPage() )
                <a href="{{ $projects->nextPageUrl() }}" class="btn btn-primary">Next page</a>
            @endif
        </div>
    @endif
</div>

@endsection
