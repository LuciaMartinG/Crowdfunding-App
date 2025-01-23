<!-- Modal -->
<div class="modal fade" id="editUpdateModal" tabindex="-1" aria-labelledby="editUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUpdateModalLabel">Edit Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario de edición de actualización -->
                <form action="{{ route('projects.comments.edit', $update->id) }}" method="POST">
                    @csrf
                    @method('PUT') <!-- Cambiar a PUT para actualización -->

                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $update->title) }}">
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" class="form-control">{{ old('description', $update->description) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="image_url">Image URL</label>
                        <input type="url" name="image_url" class="form-control" value="{{ old('image_url', $update->image_url) }}">
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>