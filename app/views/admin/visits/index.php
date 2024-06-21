<?php
use App\Controllers\ProjectController;
use App\Helpers\View;
use App\Controllers\VisitController;

$title = "Visites";

$visitController = new VisitController();
$stats = [
    'total' => $visitController->countTotalVisits(),
    'today' => $visitController->countUniqueVisitsAtDate(date('Y-m-d'))
];

$urlFilter = [
    '/',
    '/admin',
    '/contact',
    '/home',
    '/projects',
    '/login',
    '/register',
    '/404',
    '/403',
    ];

$projects = ProjectController::getAllProjectsId();

foreach ($projects as $project) {
    $urlFilter[] = '/projects/show/' . $project['id'];
}

$statsByUrl = $visitController->getUrlStats($urlFilter);


?>
<?php ob_start(); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@^1.0.0"></script>

<div class="shadow row mt-4 bg-body-tertiary">
    <div class="col px-4 py-2">
        <h1><?= $title ?></h1>
    </div>
</div>
<div class="row mb-4">
    <div class="mt-4">
        <div class="card">
            <div class="card-body">
                <h3>Statistiques des visites</h3>
                <p>Total des visites : <?php echo $stats['total']; ?></p>
                <p>Visites aujourd'hui : <?php echo $stats['today']; ?></p>
            </div>
        </div>
    </div>
    <div class="col-sm-7 mt-4">
        <div class="card">
            <div class="card-body">
                <h3>Graphique des visites quotidiennes</h3>
                <canvas id="visitsChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-sm-5 mt-4">
        <div class="card">
            <div class="card-body">
                <h3>Visites par url</h3>
                <table class="table table-striped table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Url</th>
                            <th>Visites</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($statsByUrl as $statForUrl): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($statForUrl['url']); ?></td>
                                <td><?php echo htmlspecialchars($statForUrl['count']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col px-4 py-2">
        <a href="/admin" class="btn btn-primary">Retour</a>
    </div>
</div>
<script>
    const ctx = document.getElementById('visitsChart').getContext('2d');
    const visitsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php
            // Récupérer les données des 30 derniers jours pour le graphique
            $labels = [];
            $data = [];
            for ($i = 29; $i >= 0; $i--) {
                $date = date('Y-m-d', strtotime("-$i days"));
                $labels[] = $date;
                $data[] = $visitController->countUniqueVisitsAtDate($date);
            }
            echo json_encode($labels);
            ?>,
            datasets: [{
                label: 'Visites quotidiennes',
                data: <?php echo json_encode($data); ?>,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: true
            }]
        },
        options: {
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'day',
                        tooltipFormat: 'll'
                    },
                    title: {
                        display: true,
                        text: 'Date'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Nombre de visites'
                    }
                }
            }
        }
    });
</script>

<?php $content = ob_get_clean(); ?>

<?php View::render('layouts/layout.php', ['content' => $content, 'title' => $title]); ?>
