<?php

namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

class Configuration
{
    public function __construct()
    {
    }

    public function getAll()
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM config");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('PDOException - Configuration::getAll() : ' . $e->getMessage(), 0);
            return null;
        }
    }

    public function update($setting_name, $setting_value)
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("UPDATE config SET setting_value = ? WHERE setting_name = ?");
            $stmt->execute([$setting_value, $setting_name]);
        } catch (PDOException $e) {
            error_log('PDOException - Configuration::update() : ' . $e->getMessage(), 0);
            throw new PDOException($e->getMessage(), (int) $e->getCode());
        }
    }

    public function get($setting_name)
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM config WHERE setting_name = ?");
            $stmt->execute([$setting_name]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('PDOException - Configuration::get() : ' . $e->getMessage(), 0);
            return null;
        }
    }

    public function getValue($setting_name)
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM config WHERE setting_name = ?");
            $stmt->execute([$setting_name]);
            $config = $stmt->fetch(PDO::FETCH_ASSOC);
            return $config['setting_value'];
        } catch (PDOException $e) {
            error_log('PDOException - Configuration::getValue() : ' . $e->getMessage(), 0);
            return null;
        }
    }

    public function create($setting_name, $setting_value, $setting_type)
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("INSERT INTO config (setting_name, setting_value, setting_type) VALUES (?, ?, ?)");
            $stmt->execute([$setting_name, $setting_value, $setting_type]);
        } catch (PDOException $e) {
            error_log('PDOException - Configuration::create() : ' . $e->getMessage(), 0);
            throw new PDOException($e->getMessage(), (int) $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("DELETE FROM config WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log('PDOException - Configuration::destroy() : ' . $e->getMessage(), 0);
            throw new PDOException($e->getMessage(), (int) $e->getCode());
        }
    }
}
