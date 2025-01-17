
<div class="modal fade" id="editProjectModal-{{ $project->id }}" tabindex="-1" aria-labelledby="editProjectModalLabel-{{ $project->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProjectModalLabel-{{ $project->id }}">Edit Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/project/update" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $project->id }}">

                    <div class="form-group mb-3">
                        <label for="title-{{ $project->id }}">Title</label>
                        <input type="text" id="title-{{ $project->id }}" name="title" class="form-control" value="{{ $project->title }}" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="description-{{ $project->id }}">Description</label>
                        <textarea id="description-{{ $project->id }}" name="description" class="form-control" required>{{ $project->description }}</textarea>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="image_url-{{ $project->id }}">Image URL</label>
                        <input type="text" id="image_url-{{ $project->id }}" name="image_url" class="form-control" value="{{ $project->image_url }}">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="video_url-{{ $project->id }}">Video URL</label>
                        <input type="text" id="video_url-{{ $project->id }}" name="video_url" class="form-control" value="{{ $project->video_url }}">
                    </div>
                    
                    <button type="submit" class="btn btn-secondary text-white">Save Changes</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
