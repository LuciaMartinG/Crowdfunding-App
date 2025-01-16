@extends('layout')

@section('title', 'User Details')

@section('content')

<div class="container mt-5">
    <!-- Información del usuario en una tarjeta con sombra -->
    <div class="card shadow-lg mb-4">
        <div class="row g-0">
            <!-- Fotografía del usuario a la izquierda -->
            <div class="col-md-4">
                <img src="{{ $user->photo ?? 'https://via.placeholder.com/150' }}" 
                     class="img-fluid rounded-start" 
                     alt="{{ $user->name }}" 
                     style="object-fit: cover; height: 100%; width: 100%;">
            </div>
            <!-- Información del usuario a la derecha -->
            <div class="col-md-8">
                <div class="card-body">
                    <h1 class="card-title">{{ $user->name }}</h1>
                    <h3 class="card-subtitle text-muted">{{ $user->email }}</h3>
                    <p class="mt-3"><strong>Role:</strong> {{ ucfirst($user->role) }}</p>

                    <!-- Botón para editar perfil -->
                    @if(Auth::user() && (Auth::user()->role == 'entrepreneur' || Auth::user()->role == 'investor'))
                    <div class="mb-4">
                        <a href="/user/update/{{ $user->id }}" class="btn btn-primary text-white">Edit Profile</a>
                    </div>
                    @endif

                    <!-- Botón "Change Role" que abre el modal (solo visible para admin) -->
                    @if(Auth::user() && (Auth::user()->role == 'admin'))
                    <button class="btn btn-secondary text-white" data-bs-toggle="modal" data-bs-target="#changeRoleModal">Change Role</button>
                    <!-- Botón para banear al usuario -->
                    <form action="/user/ban" method="POST" class="mt-3">
                        @csrf
                        <input type="hidden" name="id" value="{{ $user->id }}">
                        <button type="submit" class="btn btn-danger">Ban User</button>
                    </form>
                    @endif

                    <!-- Recuadro con saldo actual -->
                    <div class="mt-4">
                        <p><strong>Current Balance:</strong> {{ number_format($user->balance, 2) }} €</p>
                    </div>

                    <!-- Botón "Modify Balance" que abre el modal -->
                    <button class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#modifyBalanceModal">Modify Balance</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para cambiar el rol -->
    @if(Auth::user() && (Auth::user()->role == 'admin'))
    <div class="modal fade" id="changeRoleModal" tabindex="-1" aria-labelledby="changeRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changeRoleModalLabel">Change User Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/user/updateRole" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $user->id }}">
                        <div class="mb-2">
                            <label for="role-{{ $user->id }}" class="form-label">Role</label>
                            <select id="role-{{ $user->id }}" name="role" class="form-select" required>
                                <option value="entrepreneur" {{ $user->role === 'entrepreneur' ? 'selected' : '' }}>Entrepreneur</option>
                                <option value="investor" {{ $user->role === 'investor' ? 'selected' : '' }}>Investor</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-secondary text-white">Update Role</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal para modificar el saldo -->
    @if(Auth::user() && (Auth::user()->role == 'admin'))
    <div class="modal fade" id="modifyBalanceModal" tabindex="-1" aria-labelledby="modifyBalanceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modifyBalanceModalLabel">Modify User Balance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/user/updateBalance" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $user->id }}">
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" step="1" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label for="transaction_type" class="form-label">Transaction Type</label>
                            <select class="form-select" name="transaction_type" id="transaction_type" required>
                                <option value="deposit">Deposit</option>
                                <option value="withdrawal">Withdrawal</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Balance</button>
                    </form>
                </div>
            </div>
        </div>
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
                            <a href="/project/detail/{{ $project->id }}" class="btn btn-secondary text-white">View Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@endsection
