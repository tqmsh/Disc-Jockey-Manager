{{-- TODO probably better to implement this as proper modal instead of alert using orchid --}}

<div class="modal-content" style="background-color:#FFF3CD">
    <div class="modal-header">
        <h4 class="modal-title text-black fw-light">
            <strong>
                Vote Change?
            </strong>
        </h4>
    </div>


    <div class="modal-body">
        <h6 data-confirm-target="message">You have already voted for this position. Change vote to candidate&nbsp <strong>{{ $name }}</strong> ?</h6>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-warning">Change Vote</button>
    </div>
</div>