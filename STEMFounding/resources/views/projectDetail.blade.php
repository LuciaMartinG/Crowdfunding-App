@extends('layout')

@section('title', 'Project List')

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
                            <h4 class="user">User: {{ $project->user->name }}</h4>
                            <p class="card-text">{{ $project->description }}</p>
                            <h5 class="card-subtitle mb-2">Deadline: {{ $project->limit_date }}</h5>
                            <h5 class="card-subtitle mb-2">State: {{ $project->state }}</h5>
                            <h5 class="card-subtitle mb-2 ">Money raised: {{ $project->current_investment }} / {{ $project->max_investment }}</h5>
                            
                            <!-- Barra de Progreso -->
                            <div class="progress mb-3">
                                <!-- Calcula el porcentaje de inversión recaudada -->
                                @php
                                    $percentage = ($project->current_investment / $project->max_investment) * 100;
                                    if($percentage > 100) $percentage = 100;  
                                @endphp

                                <!-- Barra de Progreso que se ajusta según el porcentaje calculado -->
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-secondary" role="progressbar" style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                    {{ round($percentage, 2) }}% 
                                </div>
                            </div>
                        
                        <!-- Mostrar las actualizaciones del proyecto -->
                        <!-- Mostrar las actualizaciones del proyecto -->
                        <h4 class="mb-3">Project Updates</h4>
                        @forelse ($project->updates as $update)
                            <div class="update mb-3 p-3 border border-primary rounded shadow-sm bg-light">
                                <h5 class="update-title">{{ $update->title }}</h5>
                                <p class="update-description">{{ $update->description }}</p>
                                <p><strong>Updated by:</strong> {{ $update->user->name }} | <strong>On:</strong> {{ $update->updated_at->format('d-m-Y H:i') }}</p>
                                
                                @if (Auth::check() && (Auth::id() === $update->user_id || Auth::id() === $project->user_id))
                                    <a href="{{ route('projects.comments.delete', $update->id) }}" class="btn btn-danger btn-sm mb-3 w-auto" onclick="return confirm('¿Are you sure?');">Delete Update</a>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#editUpdateModal" class="btn btn-warning btn-sm mb-3 w-auto">Edit Update</a>
                                @endif
                                
                                @include('editUpdatesModal')
                            </div>
                        @empty
                            <p>No updates available for this project.</p>
                        @endforelse


                        @if(Auth::check() && Auth::user()->role == 'admin')
                            <a href="/project/delete/{{ $project->id }}" class="btn btn-danger btn-sm mb-3 w-auto" onclick="return confirm('¿Are you sure?');">Delete Project</a>
                        @endif

                        @if(Auth::check() && Auth::user()->role == 'admin')
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

                        <!-- Verificar si el usuario ha invertido en el proyecto -->
                        @if (Auth::check() && Auth::user()->role == 'investor' && $project->investments->where('user_id', Auth::id())->count() > 0)
                            <a href="{{ url('/project/investments/' . $project->id) }}" class="btn btn-info btn-sm mt-3">View My Investments</a>
                        @endif

                        <!-- Botón "Found" visible solo para el usuario con rol 'investor' -->
                        @if (Auth::check() && Auth::user()->role == 'investor' && $project->state == 'active')
                            <button 
                                        class="btn btn-warning btn-sm mt-3 animated-button" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editFoundsModal-{{ $project->id }}">
                                        Add Founds
                                    </button>
                                    
                                    @include('addFoundsModal', ['project' => $project])
                        @endif

                        <!-- Botón "Edit Project" y Modal visible solo si el proyecto pertenece al usuario autenticado -->
                        @if ($project->user_id == Auth::id() && $project->state == 'active')
                        <button 
                                        class="btn btn-warning btn-sm mt-3" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editProjectModal-{{ $project->id }}">
                                        Edit Project
                                    </button>
                
                        <button type="button" class="btn btn-secondary btn-sm mt-3" data-bs-toggle="modal" data-bs-target="#addCommentModal">
                            Add Update
                        </button>
                         

                            <!-- Modal para editar proyecto -->
                            @include('editProjectModal', ['project' => $project])
                            @include('addUpdatesModal', ['project' => $project])
                            
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
