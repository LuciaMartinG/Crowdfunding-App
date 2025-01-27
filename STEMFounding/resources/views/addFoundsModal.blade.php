<!-- Modal para añadir fondos -->
<div class="modal fade" id="editFoundsModal-{{ $project->id }}" tabindex="-1" aria-labelledby="addFundModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFundModalLabel">Add Fund to Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/invest" method="POST">
                    @csrf
                    <input type="hidden" name="project_id" value="{{ $project->id }}">

                    <div class="mb-3">
                        <label for="investment_amount" class="form-label">Amount to Add (€)</label>
                        <input 
                            type="number" 
                            class="form-control" 
                            id="investment_amount" 
                            name="investment_amount" 
                            min="10" 
                            step="0.01" 
                            value="10" 
                            required
                        >
                        <small class="form-text text-muted">
                            The minimum investment amount is €10.
                        </small>
                    </div>

                    <button type="submit" class="btn btn-secondary text-white">Add Fund</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
