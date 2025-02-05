@extends('layout')

@section('title', 'My Projects')

@section('content')

<div class="container my-5">
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

    @if($projects->isEmpty())
        <div class="alert alert-info text-center" role="alert">
            You don't have any projects yet. Start creating one now!
        </div>
    @else
        <div class="row justify-content-center">
            @foreach($projects as $project)
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card shadow-lg border-0">
                        <div class="row g-0">
                            <!-- Imagen del proyecto -->
                            <div class="col-md-4">
                                <img src="{{ $project->image_url ?? 'https://via.placeholder.com/300x200' }}" 
                                     class="img-fluid rounded-start" 
                                     alt="{{ $project->title }}" 
                                     style="object-fit: cover; height: 100%; width: 100%;">
                            </div>
                            <!-- Información del proyecto -->
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $project->title }}</h5>
                                    
                                    <p><strong>Invested Amount:</strong> {{ number_format($project->current_investment, 2) }} €</p>
                                    <p><strong>Max Investment:</strong> {{ number_format($project->max_investment, 2) }} €</p>

                                    <!-- Barra de Progreso -->
                                    <div class="progress mb-3">
                                        @php
                                            $percentage = ($project->current_investment_amount / $project->max_investment) * 100;
                                            if($percentage > 100) $percentage = 100;
                                        @endphp
                                        <div class="progress-bar {{ $project->state == 'active' ? 'bg-success' : 'bg-danger' }}" 
                                             role="progressbar" 
                                             style="width: {{ $percentage }}%;" 
                                             aria-valuenow="{{ $percentage }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            {{ round($percentage, 2) }}%
                                        </div>
                                    </div>
                                     <!-- Estado del Proyecto -->
                                     <p class="mb-2">
                                        <strong>Status:</strong>
                                        <span class="badge {{ $project->state == 'active' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($project->state) }}
                                        </span>
                                    </p>
                                    @if(Auth::check() && Auth::user()->role == 'entrepreneur')
                                    <a href="{{ route('projects.investors', ['id' => $project->id]) }}" class="btn btn-info btn-sm">View investors</a>
                                    
                                    <form action="/projects/user/activate-or-deactivate" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $project->id }}">
                                        @if ($project->state == 'active')
                                            <button type="submit" name="state" value="inactive" class="btn btn-danger btn-sm mt-3 mb-3">Disable Project</button>
                                        @endif
                                    </form>
                                    @endif
                                    @if ($project->current_investment >= $project->min_investment && \Carbon\Carbon::parse($project->limit_date)->lessThan(now()) && $project->state == 'active')
                                    <form action="{{ route('projects.withdrawFunds', ['projectId' => $project->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm mb-3">
                                            Withdraw Funds
                                        </button>
                                    </form>
                                    @endif
                                
                                    <!-- Botón para abrir el modal: solo visible si el proyecto está activo -->
                                    @if ($project->state == 'active')
                                        <button 
                                            class="btn btn-warning btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editProjectModal-{{ $project->id }}">
                                            Edit Project
                                        </button>
                                    @endif
                            
                                <a href="{{ route('projects.show', $project->id) }}" class="btn btn-secondary btn-sm mt-3 mb-3 me-2">View Details
    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal para editar proyecto -->
    @include('editProjectModal', ['project' => $project])
            @endforeach
        </div>
    @endif
</div>

@endsection
