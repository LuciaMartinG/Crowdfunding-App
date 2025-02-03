@extends('layout')

@section('title', 'Crear Nuevo Proyecto')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Nuevo Proyecto</h1>

    <div class="card shadow">
        <div class="card-body">
            <form action="/project/create" method="POST">
                @csrf

                <!-- Título -->
                <div class="mb-3">
                    <label for="title" class="form-label">Título</label>
                    <input type="text" class="form-control bg-white" id="title" name="title" required>
                </div>

                <!-- Descripción -->
                <div class="mb-3">
                    <label for="description" class="form-label">Descripción</label>
                    <textarea class="form-control bg-white" id="description" name="description" rows="4" required></textarea>
                </div>

                <!-- URL imagen -->
                <div class="mb-3">
                    <label for="image_url" class="form-label">Imagen URL</label>
                    <input type="url" class="form-control bg-white" id="image_url" name="image_url" required>
                </div>

                <!-- URL video -->
                <div class="mb-3">
                    <label for="video_url" class="form-label">Video URL</label>
                    <input type="url" class="form-control bg-white" id="video_url" name="video_url" required>
                </div>

                <!-- Inversión mínima -->
                <div class="mb-3">
                    <label for="min_investment" class="form-label">Inversión Mínima</label>
                    <input type="number" class="form-control bg-white" id="min_investment" name="min_investment" required min="1">
                </div>

                <!-- Inversión máxima -->
                <div class="mb-3">
                    <label for="max_investment" class="form-label">Inversión Máxima</label>
                    <input type="number" class="form-control bg-white" id="max_investment" name="max_investment">
                </div>

                <!-- Fecha límite -->
                <div class="mb-3">
                    <label for="limit_date" class="form-label">Fecha Límite</label>
                    <input type="date" class="form-control bg-white" id="limit_date" name="limit_date" required min="{{ date('Y-m-d') }}">
                </div>

                <!-- Botón de envío -->
                <button type="submit" class="btn btn-secondary text-white">Crear Proyecto</button>
                <a href="/" class="btn btn-danger">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection
