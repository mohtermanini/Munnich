<div class="modal fade" id="endTripModal" tabindex="-1" aria-labelledby="endTripModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="endTripModalLabel">End Trip</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="endTripForm">
                    <input type="hidden" name="csrfToken" value="<?= \Middlewares\CsrfMiddleware::generate("endTripForm"); ?>">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="endDate" class="form-label">Arrival Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="endDate" name="endDate" required>
                                <div class="input-error text-danger mt-1"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="endTime" class="form-label">Arrival Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" id="endTime" name="endTime" required>
                                <div class="input-error text-danger mt-1"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="endLocation" class="form-label">Final Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="endLocation" name="endLocation" placeholder="eg. Downtown Dubai, Marina Walk" required>
                        <div class="input-error text-danger mt-1"></div>
                    </div>
                    <div class="mb-3">
                        <label for="endKm" class="form-label">Final Odometer Reading (Km)<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="endKm" name="endKm" placeholder="e.g. 12345" required>
                        <div class="input-error text-danger mt-1"></div>
                    </div>
                    <div class="mb-3">
                        <label for="purpose" class="form-label">Purpose</label>
                        <textarea class="form-control" id="purpose" name="purpose" placeholder="eg. Business meeting" rows="3"></textarea>
                        <div class="input-error text-danger mt-1"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Duration</label>
                        <p id="duration" class="text-muted"> </p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-dark" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-sm btn-dark saveEndTrip">Save</button>
            </div>
        </div>
    </div>
</div>