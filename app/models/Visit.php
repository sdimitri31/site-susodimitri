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

    public function logVisit($ip)
    {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) AS count FROM visits WHERE ip = ? AND DATE(date_visit) = CURDATE()");
            $stmt->execute([$ip]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row['count'] > 0) {
                return false;
            }

            $stmt = $this->db->prepare("INSERT INTO visits (ip, date_visit) VALUES (?, NOW())");
            return $stmt->execute([$ip]);
        } catch (PDOException $e) {
            error_log('PDOException - ' . $e->getMessage(), 0);
            return false;
        }
    }

    public function getVisitStats()
    {
        try {
            $stmt = $this->db->prepare("SELECT DATE(date_visit) AS visit_date, COUNT(*) AS visit_count FROM visits GROUP BY visit_date ORDER BY visit_date DESC");
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
            $stmt = $this->db->query("SELECT COUNT(*) AS total_visits FROM visits");
            return $stmt->fetch(PDO::FETCH_ASSOC)['total_visits'];
        } catch (PDOException $e) {
            error_log('PDOException - ' . $e->getMessage(), 0);
            return 0;
        }
    }

    public function countVisitsAtDate($date)
    {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) AS visits_count FROM visits WHERE DATE(date_visit) = ?");
            $stmt->execute([$date]);
            return $stmt->fetch(PDO::FETCH_ASSOC)['visits_count'];
        } catch (PDOException $e) {
            error_log('PDOException - ' . $e->getMessage(), 0);
            return 0;
        }
    }

}