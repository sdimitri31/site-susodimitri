<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static $pdo = null;

    private function __construct()
    {
        // Private constructor to prevent multiple instances
    }

    public static function getConnection()
    {
        if (self::$pdo === null) {
            include realpath(__DIR__ . '/../../config/config.php');
            $dsn = 'mysql:host=' . $dbSettings['host'] . ';dbname=' . $dbSettings['dbname'] . ';charset=utf8mb4';
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            try {
                self::$pdo = new PDO($dsn, $dbSettings['user'], $dbSettings['password'], $options);
            } catch (PDOException $e) {
                error_log('PDOException - Database::getConnection() ' . $e->getMessage(), 0);
                throw new PDOException($e->getMessage(), (int) $e->getCode());
            }
        }

        return self::$pdo;
    }
}