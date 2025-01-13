@extends('layout')

@section('title', 'Crear Nuevo Proyecto')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Nuevo Proyecto</h1>

    <div class="card">
        <div class="card-body">
            <form action="/project/create" method="POST">
                @csrf

                <!-- Título -->
                <div class="mb-3">
                    <label for="title" class="form-label">Título</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>

                                <!-- Descripcion -->
                    <div class="mb-3">
                    <label for="description" class="form-label">Descripcion</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                </div>

                            <!-- URL imagen -->
                <div class="mb-3">
                    <label for="image_url" class="form-label">Imagen URL</label>
                    <input type="url" class="form-control" id="image_url" name="image_url" required>
                </div>
                <!-- URL video -->
                    <div class="mb-3">
                    <label for="video_url" class="form-label">video URL</label>
                    <input type="url" class="form-control" id="video_url" name="video_url" required>
                </div>

                <!-- inversión minima -->
                <div class="mb-3">
                    <label for="min_investment" class="form-label">Minimum investment</label>
                    <input type="number" class="form-control" id="min_investment" name="min_investment" required min="1">
                </div>

                <!-- inversión maxima -->
                <div class="mb-3">
                    <label for="max_investment" class="form-label">Maximum investment</label>
                    <input type="number" class="form-control" id="max_investment" name="max_investment">
                </div>

                    <!-- Limit date -->
                    <div class="mb-3">
                    <label for="limit_date" class="form-label">Deadline</label>
                    <input type="date" class="form-control" id="limit_date" name="limit_date" required min="2025" max="2026-01-01">
                </div>


                <!-- Botón de envío -->
                <button type="submit" class="btn btn-primary">Crear Proyecto</button>
                <a href="/" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection