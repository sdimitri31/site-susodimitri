<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Configuration
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getPdo();
    }

    public function getAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM config");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($setting_name, $setting_value)
    {
        $stmt = $this->db->prepare("UPDATE config SET setting_value = ? WHERE setting_name = ?");
        return $stmt->execute([$setting_value, $setting_name]);
    }

    public function get($setting_name)
    {
        $stmt = $this->db->prepare("SELECT * FROM config WHERE setting_name = ?");
        $stmt->execute([$setting_name]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getValue($setting_name)
    {
        $stmt = $this->db->prepare("SELECT * FROM config WHERE setting_name = ?");
        $stmt->execute([$setting_name]);
        $config = $stmt->fetch(PDO::FETCH_ASSOC);
        return $config['setting_value'];
    }

    public function create($setting_name, $setting_value, $setting_type)
    {
        $stmt = $this->db->prepare("INSERT INTO config (setting_name, setting_value, setting_type) VALUES (?, ?, ?)");
        return $stmt->execute([$setting_name, $setting_value, $setting_type]);
    }

    public function destroy($id)
    {
        $stmt = $this->db->prepare("DELETE FROM config WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
