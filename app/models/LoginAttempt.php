<?php

namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

class LoginAttempt
{
    public function __construct()
    {
    }

    public function logAttempt($username, $ipAddress, $isSuccess)
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("INSERT INTO login_attempts (username, ip_address, is_success, attempt_time)
                                        VALUES (?, ?, ?, NOW())");
            return $stmt->execute([$username, $ipAddress, $isSuccess]);
        } catch (PDOException $e) {
            error_log('PDOException - ' . $e->getMessage(), 0);
            return false;
        }
    }

    public function isUserBlocked($username, $ipAddress, $maxAttempts = 5, $attemptWindowMinutes = 30)
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT COUNT(*) AS attempts FROM login_attempts
                                        WHERE username = ? AND ip_address = ? AND is_success = 0
                                        AND attempt_time > DATE_SUB(NOW(), INTERVAL ? MINUTE)");
            $stmt->execute([$username, $ipAddress, $attemptWindowMinutes]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row['attempts'] >= $maxAttempts) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log('PDOException - ' . $e->getMessage(), 0);
            return false;
        }
    }

    public function clearLoginAttempts($username)
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("DELETE FROM login_attempts WHERE username = ?");
            return $stmt->execute([$username]);
        } catch (PDOException $e) {
            error_log('PDOException - ' . $e->getMessage(), 0);
            return false;
        }
    }
}
