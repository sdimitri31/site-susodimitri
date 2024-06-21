<?php

namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

class Visit
{
    public function __construct()
    {
    }

    /**
     * Insert visit in dababase
     * @param string $ip 
     * @param string $url 
     * @param string $method 
     * @return bool
     */
    public function logVisit(string $ip, string $url, string $method)
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("INSERT INTO visits (ip, requested_url, requested_method, visited_at) VALUES (?, ?, ?, NOW())");
            return $stmt->execute([$ip, $url, $method]);
        } catch (PDOException $e) {
            error_log('PDOException - ' . $e->getMessage(), 0);
            return false;
        }
    }

    /**
     * Count number of unique IP per Day
     * @return int
     */
    public function countTotalVisits()
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->query("
                SELECT COUNT(*) AS total_visits
                FROM (
                    SELECT ip, DATE(visited_at) AS visit_date
                    FROM visits
                    GROUP BY ip, visit_date
                ) AS daily_visits
            ");
            return intval($stmt->fetch(PDO::FETCH_ASSOC)['total_visits']);
        } catch (PDOException $e) {
            error_log('PDOException - ' . $e->getMessage(), 0);
            return 0;
        }
    }

    /**
     * Count number of visits at selected date
     * @param string $date 
     * @return int
     */
    public function countVisitsAtDate($date)
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT COUNT(*) AS visit_count FROM visits WHERE DATE(visited_at) = ?");
            $stmt->execute([$date]);
            return intval($stmt->fetch(PDO::FETCH_ASSOC)['visits_count']);
        } catch (PDOException $e) {
            error_log('PDOException - ' . $e->getMessage(), 0);
            return 0;
        }
    }

    /**
     * Count number of unique IP at selected date
     * @param string $date 
     * @return int
     */
    public function countUniqueVisitsAtDate($date)
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT COUNT(DISTINCT ip) AS visits_count FROM visits WHERE DATE(visited_at) = ?");
            $stmt->execute([$date]);
            return intval($stmt->fetch(PDO::FETCH_ASSOC)['visits_count']);
        } catch (PDOException $e) {
            error_log('PDOException - ' . $e->getMessage(), 0);
            return 0;
        }
    }

    /**
     * Count number of unique IP at selected url
     * @param string $url 
     * @return int
     */
    public function countUniqueVisitsAtUrl(string $url)
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT COUNT(DISTINCT ip) AS visits_count FROM visits WHERE requested_url = ?");
            $stmt->execute([$url]);

            return intval($stmt->fetch(PDO::FETCH_ASSOC)['visits_count']);
        } catch (PDOException $e) {
            error_log('PDOException - ' . $e->getMessage(), 0);
            return 0;
        }
    }

    /**
     * Get an array containing every visited urls
     * @return array
     */
    public function getVisitedUrls()
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT DISTINCT requested_url FROM visits ORDER BY requested_url ASC");
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $urls = array_column($results, 'requested_url');

            return $urls;
        } catch (PDOException $e) {
            error_log('PDOException - ' . $e->getMessage(), 0);
            return [];
        }
    }

}