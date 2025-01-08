<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <!-- Option 2: Link Brand to Dashboard -->
        <a class="navbar-brand" href="<?php echo $baseUrl; ?>index.php?page=dashboard">Driver Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <!-- Dashboard Link -->
                <li class="nav-item">
                    <a class="nav-link <?php echo ($_GET['page'] ?? '') === 'dashboard' ? 'active' : ''; ?>" href="<?php echo $baseUrl; ?>index.php?page=dashboard">Dashboard</a>
                </li>
                <!-- Trips Link -->
                <li class="nav-item">
                    <a class="nav-link <?php echo ($_GET['page'] ?? '') === 'trips' ? 'active' : ''; ?>" href="<?php echo $baseUrl; ?>index.php?page=trips">Trips</a>
                </li>
            </ul>
            <form method="POST" action="<?php echo $baseUrl; ?>index.php?page=logout" class="d-flex">
                <button type="submit" class="btn btn-link text-white text-decoration-none">Logout</button>
            </form>
        </div>
    </div>
</nav>