<?php

namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

class Visit
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getPdo();
    }

    public function logVisit($ip, $url, $method)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO visits (ip, requested_url, requested_method, visited_at) VALUES (?, ?, ?, NOW())");
            return $stmt->execute([$ip, $url, $method]);
        } catch (PDOException $e) {
            error_log('PDOException - ' . $e->getMessage(), 0);
            return false;
        }
    }

    public function getVisitStats()
    {
        try {
            $stmt = $this->db->prepare("SELECT DATE(visited_at) AS visit_date, COUNT(*) AS visit_count FROM visits GROUP BY visit_date ORDER BY visit_date DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('PDOException - ' . $e->getMessage(), 0);
            return [];
        }
    }

    public function countTotalVisits()
    {
        try {
            $stmt = $this->db->query("
                SELECT COUNT(*) AS total_visits
                FROM (
                    SELECT ip, DATE(visited_at) AS visit_date
                    FROM visits
                    GROUP BY ip, visit_date
                ) AS daily_visits
            ");
            return $stmt->fetch(PDO::FETCH_ASSOC)['total_visits'];
        } catch (PDOException $e) {
            error_log('PDOException - ' . $e->getMessage(), 0);
            return 0;
        }
    }

    public function countVisitsAtDate($date)
    {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(DISTINCT ip) AS visits_count FROM visits WHERE DATE(visited_at) = ?");
            $stmt->execute([$date]);
            return $stmt->fetch(PDO::FETCH_ASSOC)['visits_count'];
        } catch (PDOException $e) {
            error_log('PDOException - ' . $e->getMessage(), 0);
            return 0;
        }
    }

}