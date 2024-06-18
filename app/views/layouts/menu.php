<?php 

use App\Models\Configuration;
$configurationModel = new Configuration();
$isMaintenanceMode = $configurationModel->getValue('maintenance_mode') == '1'; 
?>

<nav class="navbar navbar-expand-sm px-4 py-1" <?php if($isMaintenanceMode) echo 'style="background-color: red !important;"'; ?> id="navbar">
    <div class="container">
        <a class="navbar-brand p-0" href="/home">
            <img src="/assets/logo.png" alt="Logo" width="64" class="d-inline-block align-text-top">
        </a>
        <a class="navbar-brand px-2" href="/home">Suso Dimitri</a>
        <button class="navbar-toggler btn btn-navbar" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">☰</span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link mx-2" href="/projects">Projets</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-2" href="/contact">Contact</a>
                </li>
                <?php if(isset ($loggedUser)): ?>
                    <li class="nav-item">
                        <a class="nav-link mx-2" href="/admin">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mx-2" href="/logout">Déconnexion</a>
                    </li>
                <?php endif; ?>
            </ul>            
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <button class="btn btn-navbar" id="themeToggle">
                        <i id="themeIcon" class="fas"></i>
                    </button>
                </li>
            </ul>
        </div>
    </div>
</nav>