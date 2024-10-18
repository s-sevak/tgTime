<?php

namespace App\Database;

use PDO;
use PDOException;

class Database implements DatabaseInterface
{
    private PDO $pdo;

    public function __construct(string $host, string $dbname, string $username, string $password)
    {
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die(json_encode(["error" => "Ошибка подключения: " . $e->getMessage()]));
        }
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
