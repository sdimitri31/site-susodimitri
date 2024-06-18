<?php
use App\Helpers\View;
use App\Controllers\VisitController;

$title = "Visites";

$visitController = $visitController ?? new VisitController();
$stats = [
    'total' => $visitController->countTotalVisits(),
    'today' => $visitController->countVisitsAtDate(date('Y-m-d'))
];

?>
<?php ob_start(); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@^1.0.0"></script>

<div class="shadow row mt-4 bg-body-tertiary">
    <div class="col px-4 py-2">
        <h1>Visites</h1>
    </div>
</div>
<div class="row">
    <div class="col mt-4 px-4 py-2">

        <h2>Statistiques des visites</h2>
        <p>Total des visites : <?php echo $stats['total']; ?></p>
        <p>Visites aujourd'hui : <?php echo $stats['today']; ?></p>

        <h2>Graphique des visites quotidiennes</h2>
        <canvas id="visitsChart"></canvas>

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
                        $data[] = $visitController->countVisitsAtDate($date);
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
    </div>
</div>
<div class="row">
    <div class="col px-4 py-2">
        <a href="/admin" class="btn btn-primary">Retour</a>
    </div>
</div>

<?php $content = ob_get_clean(); ?>

<?php View::render('layouts/layout.php', ['content' => $content, 'title' => $title]); ?>
