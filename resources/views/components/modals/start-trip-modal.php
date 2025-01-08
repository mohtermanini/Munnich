<div class="modal fade" id="startTripModal" tabindex="-1" aria-labelledby="startTripModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="startTripModalLabel">Start a New Trip</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="startTripForm">
                    <input type="hidden" name="csrfToken" value="<?= \Middlewares\CsrfMiddleware::generate("startTripForm"); ?>">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="licensePlate" class="form-label">License Plate <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="licensePlate" name="licensePlate" placeholder="Enter license plate" required>
                                <div class="input-error text-danger mt-1"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">

                                <label for="startDate" class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="startDate" name="startDate" required>
                                <div class="input-error text-danger mt-1"></div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">

                                <label for="startTime" class="form-label">Start Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" id="startTime" name="startTime" required>
                                <div class="input-error text-danger mt-1"></div>
                            </div>

                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">

                                <label for="startLocation" class="form-label">Initial Location <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="startLocation" name="startLocation" placeholder="Enter initial location" required>
                                <div class="input-error text-danger mt-1"></div>
                            </div>

                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="startKm" class="form-label">Initial Odometer Reading (Km)<span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="startKm" name="startKm" placeholder="Enter initial odometer reading" required>
                                <div class="input-error text-danger mt-1"></div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-dark" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-sm btn-dark saveStartTrip">Start Trip</button>
            </div>
        </div>
    </div>
</div>