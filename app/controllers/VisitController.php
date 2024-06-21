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

    /**
     * Insert visit in dababase
     * @param string $ip 
     * @param string $url 
     * @param string $method 
     * @return bool
     */
    public static function logVisit(string $ip, string $url, string $method)
    {
        $logvisit = new Visit();
        return $logvisit->logVisit($ip, $url, $method);
    }

    /**
     * Count number of unique IP per Day
     * @return int
     */
    public function countTotalVisits()
    {
        return $this->visit->countTotalVisits();
    }

    /**
     * Count number of visits at selected date
     * @param string $date 
     * @return int
     */
    public function countVisitsAtDate($date)
    {
        return $this->visit->countVisitsAtDate($date);
    }

    /**
     * Count number of unique IP at selected date
     * @param string $date 
     * @return int
     */
    public function countUniqueVisitsAtDate($date)
    {
        return $this->visit->countUniqueVisitsAtDate($date);
    }

    /**
     * Count number of unique IP at selected url
     * @param string $url 
     * @return int
     */
    public function countUniqueVisitsAtUrl(string $url)
    {
        return $this->visit->countUniqueVisitsAtUrl($url);
    }

    /**
     * Get an array containing every visited urls
     * @return array
     */
    public function getVisitedUrls()
    {
        return $this->visit->getVisitedUrls();
    }

    public function getUrlStats(array $urlFilter){
        $urls = $this->getVisitedUrls();
        $statsByUrl = [];
        foreach ($urls as $url) {
            if (in_array($url, $urlFilter)) {
                $statsByUrl[] = [
                    'url' => $url,
                    'count' => $this->countUniqueVisitsAtUrl($url)
                ];
            }
        }
        $keyValues = array_column($statsByUrl, 'count');
        array_multisort($keyValues, SORT_DESC, $statsByUrl);
        return $statsByUrl;
    }
}
