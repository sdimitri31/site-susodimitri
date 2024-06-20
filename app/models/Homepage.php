<?php

namespace App\Models;

use App\Core\Database;
use Exception;
use PDO;
use PDOException;

class Homepage
{
    public static function getHomepage()
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT * FROM homepage ORDER BY id DESC LIMIT 1");
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('PDOException - Homepage::getHomepage() : ' . $e->getMessage(), 0);
            return null;
        }
    }

    public static function updateContent($data)
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("UPDATE homepage SET title = :title, content = :content WHERE id = (SELECT id FROM homepage ORDER BY id DESC LIMIT 1)");
            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':content', $data['content']);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log('PDOException - Homepage::updateContent() : ' . $e->getMessage(), 0);
            throw new Exception($e->getMessage(), (int) $e->getCode());
        }
    }
}
