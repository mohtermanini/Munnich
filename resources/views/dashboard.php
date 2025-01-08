<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $appName; ?> | Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/css/styles.css">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <?php include $viewsPath . '/components/navigation/navbar.php'; ?>

    <main class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card p-3 fade-in">
                    <h5 class="card-title">Number of Completed Trips for Last 7 Days</h5>
                    <canvas id="tripsChart" width="400" height="200"></canvas>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card p-3 fade-in">
                    <h5 class="card-title">Trip Duration in Minutes (Max, Min, Avg)</h5>
                    <canvas id="durationChart" width="400" height="200"></canvas>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card p-3 fade-in">
                    <h5 class="card-title">Vehicles Usage (Total Kilometers Driven)</h5>
                    <canvas id="vehicleUsageChart" width="400" height="200"></canvas>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card p-3 fade-in">
                    <h5 class="card-title">Total Hours Traveled</h5>
                    <canvas id="totalHoursChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card p-3 fade-in">
                    <h5 class="card-title mb-4">Ongoing Trips</h5>
                    <div class="table-container overflow-auto" style="max-height: 400px;">
                        <table class="table table-striped table-bordered table-responsive overflow-auto">
                            <thead class="table-dark sticky-header">
                                <tr>
                                    <th>Vehicle</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Departure Location</th>
                                    <th>Start KM</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="ongoingTripsTableBody"></tbody>
                        </table>
                    </div>
                    <hr class="hr" />
                    <div class="pagination-container d-flex justify-content-between align-items-center">
                        <div>
                            <div id="trips-pagination-links" class="d-inline"></div>
                        </div>
                        <a href="index.php?page=trips" class="btn btn-sm btn-dark view-all-btn">View All Trips</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include $viewsPath . '/components/navigation/footer.php'; ?>
    <?php include $viewsPath . '/components/modals/end-trip-modal.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="assets/js/dashboard/init-dashboard.js"></script>
    <script type="module" src="assets/js/scripts.js"></script>
</body>

</html>