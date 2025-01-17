<!-- Modal para añadir fondos -->
<div class="modal fade" id="editFoundsModal-{{ $project->id }}" tabindex="-1" aria-labelledby="addFundModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFundModalLabel">Add Fund to Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/project/update" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $project->id }}">

                    <div class="mb-3">
                        <label for="fund_amount" class="form-label">Amount to Add</label>
                        <input type="number" class="form-control" id="fund_amount" name="current_investment" min="1" step="0.01" required>
                    </div>

                    <button type="submit" class="btn btn-secondary text-white">Add Fund</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
