<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private $pdo;

    public function __construct()
    {
        include realpath(__DIR__ . '/../../config/config.php');
        $dsn = 'mysql:host=' . $dbSettings['host'] . ';dbname=' . $dbSettings['dbname'] . ';charset=utf8mb4';
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $dbSettings['user'], $dbSettings['password'], $options);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int) $e->getCode());
        }
    }

    public function getPdo()
    {
        return $this->pdo;
    }
}
