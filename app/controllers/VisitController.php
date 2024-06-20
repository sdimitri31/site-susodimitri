<?php

namespace App\Controllers;

use App\Models\Visit;
use App\Models\Permission;
use App\Helpers\View;
use App\Helpers\Authorization;

class VisitController
{
    private $visit;

    public function __construct()
    {
        $this->visit = new Visit();
    }

    public function index()
    {
        Authorization::requirePermission(Permission::VIEW_DASHBOARD, '/login');
        View::render('admin/visits/index.php');
    }

    public function logVisit($ip, $url, $method)
    {
        $this->visit->logVisit($ip, $url, $method);
    }

    public function getVisitStats()
    {
        return $this->visit->getVisitStats();
    }

    public function countTotalVisits()
    {
        return $this->visit->countTotalVisits();
    }

    public function countVisitsAtDate($date)
    {
        return $this->visit->countVisitsAtDate($date);
    }
}

?>
