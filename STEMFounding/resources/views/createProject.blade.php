@extends('layout')

@section('title', 'Create New Project')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">New Project</h1>

    <div class="card shadow">
        <div class="card-body">
            <form action="/project/create" method="POST">
                @csrf

                <!-- Title -->
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control bg-white" id="title" name="title" required>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control bg-white" id="description" name="description" rows="4" required></textarea>
                </div>

                <!-- Image URL -->
                <div class="mb-3">
                    <label for="image_url" class="form-label">Image URL</label>
                    <input type="url" class="form-control bg-white" id="image_url" name="image_url" required>
                </div>

                <!-- Video URL -->
                <div class="mb-3">
                    <label for="video_url" class="form-label">Video URL</label>
                    <input type="url" class="form-control bg-white" id="video_url" name="video_url" required>
                </div>

                <!-- Minimum Investment -->
                <div class="mb-3">
                    <label for="min_investment" class="form-label">Minimum Investment</label>
                    <input type="number" class="form-control bg-white" id="min_investment" name="min_investment" required min="1">
                </div>

                <!-- Maximum Investment -->
                <div class="mb-3">
                    <label for="max_investment" class="form-label">Maximum Investment</label>
                    <input type="number" class="form-control bg-white" id="max_investment" name="max_investment">
                </div>

                <!-- Limit Date -->
                <div class="mb-3">
                    <label for="limit_date" class="form-label">Limit Date</label>
                    <input type="date" class="form-control bg-white" id="limit_date" name="limit_date" required min="{{ date('Y-m-d') }}">
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-secondary text-white">Create Project</button>
                <a href="/" class="btn btn-danger">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
