<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $appName; ?> | Trips</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


    <link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/css/styles.css">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/css/trips.css">
</head>

<body>
    <?php
    include $viewsPath . '/components/navigation/navbar.php';
    ?>

    <main>
        <div class="container-fluid px-4 mt-4">

            <nav class="d-flex align-items-center justify-content-between mb-2" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">All Trips</li>
                </ol>
                <button class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#startTripModal">Start new trip</button>
            </nav>


            <div class="card p-3 mb-4">
                <div class="row row-gap-3 mb-3">
                    <div class="col-lg-2 col-md-3">
                        <label for="fromDate" class="form-label">From Date</label>
                        <input type="date" id="fromDate" class="form-control">
                    </div>
                    <div class="col-lg-2 col-md-3">
                        <label for="toDate" class="form-label">To Date</label>
                        <input type="date" id="toDate" class="form-control">
                    </div>
                    <div class="col-lg-2 col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" class="form-select">
                            <option value="">All</option>
                            <option value="Completed">Completed</option>
                            <option value="In Progress">In Progress</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-3">
                        <label for="vehicle" class="form-label">Vehicle</label>
                        <select id="vehicle" class="form-select">
                            <option value="">All</option>
                            <!-- Dynamically populate vehicle options -->
                        </select>
                    </div>

                    <div class="col-md-2 align-self-end">
                        <button id="applyFilters" class="btn btn-sm btn-dark" style="height: 37.33px;">Apply Filters</button>
                    </div>
                </div>


            </div>

            <div class="card p-3">
                <div class="table-container mt-4 overflow-auto" style="height: 515px;">
                    <table class="table table-striped table-bordered table-responsive overflow-auto" style="table-layout: fixed; width: 100%;">
                        <thead class="table-dark sticky-header">
                            <tr>
                                <th style="width: 4%;"></th>
                                <th style="width: 3%;"></th>
                                <th style="width: 20%;" colspan="4" class="header-group">Departure</th>
                                <th style="width: 20%;" colspan="4" class="header-group">Arrival</th>
                                <th style="width: 10%;"></th>
                                <th style="width: 10%;"></th>
                                <th style="width: 10%;"></th>
                                <th style="width: 13%;"></th>
                                <th style="width: 10%;"></th>
                            </tr>
                            <tr>
                                <th style="width: 4%;"></th>
                                <th style="width: 3%;">#</th>
                                <th style="width: 10%;">Vehicle</th>
                                <th style="width: 5%;">Date</th>
                                <th style="width: 5%;">Time</th>
                                <th style="width: 5%;">Start KM</th>
                                <th style="width: 5%;">Location</th>
                                <th style="width: 5%;">Date</th>
                                <th style="width: 5%;">Time</th>
                                <th style="width: 5%;">End KM</th>
                                <th style="width: 5%;">Location</th>
                                <th style="width: 10%;">Total KM</th>
                                <th style="width: 10%;">Duration</th>
                                <th style="width: 13%;">Purpose</th>
                                <th style="width: 10%;">Status</th>
                            </tr>
                        </thead>
                        <tbody id="tripsTableBody">
                            <!-- Dynamic rows will be populated here -->
                        </tbody>
                    </table>


                </div>

                <hr class="hr" />
                <div class="pagination-container d-flex justify-content-between align-items-center">
                    <div>
                        <div id="trips-pagination-links" class="d-inline"></div>
                    </div>
                    <p id="recordInfo" class="text-muted"></p>
                </div>
            </div>
    </main>

    <?php include $viewsPath . '/components/navigation/footer.php'; ?>

    <?php include $viewsPath . '/components/modals/start-trip-modal.php'; ?>
    <?php include $viewsPath . '/components/modals/end-trip-modal.php'; ?>
    <?php include $viewsPath . '/components/modals/delete-trip-modal.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="module" src="assets/js/trips/init-trips.js"></script>
    <script type="module" src="assets/js/utils/date.js"></script>
    <script type="module" src="assets/js/scripts.js"></script>
</body>

</html>