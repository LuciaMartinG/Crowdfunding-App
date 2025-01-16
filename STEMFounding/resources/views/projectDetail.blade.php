@extends('layout')

@section('title', 'Project List')

@section('content')

<div class="container my-5">
    <div class="row justify-content-center">
        <!-- Tarjeta para mostrar el proyecto -->
        <div class="col-12 col-md-8">
            <div class="card shadow-lg border-0">
                <div class="row g-0">
                    <!-- Imagen del proyecto -->
                    <div class="col-md-4">
                        <img src="{{ $project->image_url }}" alt="{{ $project->title }}" class="img-fluid rounded-start" />
                    </div>
                    <!-- Datos del proyecto -->
                    <div class="col-md-8">
                        <div class="card-body">
                            <h2 class="card-title">{{ $project->title }}</h2>
                            <p class="card-text">{{ $project->description }}</p>
                            <h5 class="card-subtitle mb-2 text-muted">Deadline: {{ $project->limit_date }}</h5>
                            <h5 class="card-subtitle mb-2 text-muted">Money raised: {{ $project->current_investment }} / {{ $project->max_investment }}</h5>
                            
                            <!-- Barra de Progreso -->
                            <div class="progress mb-3">
                                <!-- Calcula el porcentaje de inversión recaudada -->
                                @php
                                    $percentage = ($project->current_investment / $project->max_investment) * 100;
                                    if($percentage > 100) $percentage = 100;  // Asegúrate de que no se pase de 100%
                                @endphp

                                <!-- Barra de Progreso que se ajusta según el porcentaje calculado -->
                                <div class="progress-bar bg-secondary" role="progressbar" style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                    {{ round($percentage, 2) }}% 
                                </div>
                            </div>
                            @if (Auth::user()->role == 'admin')
                                <a href="/project/delete/{{ $project->id }}" class="btn btn-danger btn-sm mb-3 w-auto" onclick="return confirm('¿Are you sure?');">Delete Project</a>
                            @endif

                            @if (Auth::user()->role == 'admin')
                                <form action="/projects/activate-or-deactivate" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $project->id }}">
                                    @if ($project->state == 'active')
                                        <button type="submit" name="state" value="inactive" class="btn btn-danger btn-sm mb-3">Disable Project</button>
                                    @elseif ($project->state == 'inactive')
                                        <button type="submit" name="state" value="active" class="btn btn-success btn-sm mb-3">Enable Project</button>
                                    @endif
                                </form>
                                <p>Current state: <span id="currentState">{{ $project->state }}</span></p>
                            @endif

                            <!-- Botón "Found" visible solo para el usuario con rol 'investor' -->
                            @if (Auth::user()->role == 'investor')
                                <a href="/project/fund/{{ $project->id }}" class="btn btn-success btn-sm mt-3">Found Project</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
