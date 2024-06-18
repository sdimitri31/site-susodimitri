<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Homepage
{
    public static function getContent()
    {
        $db = (new Database())->getPdo();
        $stmt = $db->query("SELECT * FROM homepage ORDER BY id DESC LIMIT 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function updateContent($data)
    {
        $db = (new Database())->getPdo();
        $stmt = $db->prepare("UPDATE homepage SET title = :title, content = :content WHERE id = (SELECT id FROM homepage ORDER BY id DESC LIMIT 1)");
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->execute();
    }
}
