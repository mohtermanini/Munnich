<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $appName; ?> | Login</title>

    <link rel="icon" href="<?php echo $baseUrl; ?>assets/images/car-icon.svg" type="image/svg+xml">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/css/styles.css">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/css/login.css">
</head>

<body>
    <main class="gradient-form d-flex flex-column" style="background-color: #eee;">
        <div class="card rounded-3 text-black flex-grow-1 d-flex flex-column">
            <div class="row g-0 flex-sm-row flex-column flex-grow-1">
                <div class="col-lg-6 flex-grow-1">
                    <div class="card-body p-md-5 mx-md-4 h-100 d-flex flex-column justify-content-center">

                        <div class="text-center">
                            <img src="<?php echo $baseUrl; ?>assets/images/car-icon.svg"
                                style="width: 75px;" alt="logo">
                            <h4 class="mt-3 mb-5 pb-1"><?= $appName; ?></h4>
                        </div>

                        <form method="POST" action="index.php?page=authenticate">
                            <p style="font-weight: 600;">Please login to your account</p>

                            <?php if (!empty($errorMessage)): ?>
                                <div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div>
                            <?php endif; ?>

                            <div data-mdb-input-init class="form-outline mb-4">
                                <label class="form-label" for="username">Username</label>
                                <input type="text" name="username" id="username" class="form-control" required>
                            </div>

                            <div data-mdb-input-init class="form-outline mb-4">
                                <label class="form-label" for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>

                            <div class="d-flex">
                                <button type="submit" class="btn btn-dark flex-grow-1" type="button">Login</button>
                            </div>

                        </form>

                    </div>
                </div>
                <div class="col-lg-6 d-flex align-items-center bg-dark">
                    <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                        <h4 class="mb-4">Efficient Driver Management at Your Fingertips</h4>
                        <p class="small mb-0">Our platform is designed to optimize and simplify driver trip management.
                            From logging trips and tracking vehicle usage to ensuring accurate odometer readings, we provide drivers and fleet managers with a comprehensive tool to enhance productivity. With an intuitive interface, real-time data, and robust reporting capabilities, we empower businesses to manage their fleet operations more efficiently and effectively.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>