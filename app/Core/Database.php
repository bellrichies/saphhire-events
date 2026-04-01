<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?Database $instance = null;
    private PDO $connection;

    private function __construct()
    {
        try {
            $host = trim((string)($_ENV['DB_HOST'] ?? 'localhost'));
            $port = trim((string)($_ENV['DB_PORT'] ?? '3306'));
            $database = $_ENV['DB_NAME'] ?? 'sapphire_events';
            $user = $_ENV['DB_USER'] ?? 'root';
            $password = $_ENV['DB_PASSWORD'] ?? '';

            if (preg_match('/^(.+):(\d+)$/', $host, $matches) === 1) {
                $host = trim($matches[1]);
                if ($port === '') {
                    $port = $matches[2];
                }
            }

            if ($host === '') {
                $host = 'localhost';
            }

            if ($port === '' || preg_match('/^\d+$/', $port) !== 1) {
                $port = '3306';
            }

            $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";
            
            $this->connection = new PDO(
                $dsn,
                $user,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    public function prepare(string $query)
    {
        return $this->connection->prepare($query);
    }

    public function query(string $query)
    {
        return $this->connection->query($query);
    }

    public function __clone() {}
    public function __wakeup() {}
}
