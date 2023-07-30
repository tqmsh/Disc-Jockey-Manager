<div class="modal-header">
    <h4 class="modal-title text-black fw-light">
        Vote Change?
    </h4>
    <button type="button" class="btn-close" title="Close" data-bs-dismiss="modal" aria-label="Close">
    </button>
</div>

<div class="modal-body">
    <div class="p-4" data-confirm-target="message">You have already voted for this position. Change vote to candidate &nbsp <strong>{{ $name }}</strong> ?</div>
</div>

{{-- TODO STYLE THIS BUTTON AND MAKE IT DELETE OLD VOTE AND ADD NEW ONE --}}
<button type="button" class="btn btn-link" data-bs-dismiss="modal" onclick="voting">
    Confirm Change
</button>
{{-- Test --}}