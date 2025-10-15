<?php

declare(strict_types=1);

namespace App\Services;

use PDO;
use PDOException;

final class Database
{
    private ?PDO $connection = null;

    public function __construct(
        private readonly ?string $host = null,
        private readonly ?int $port = null,
        private readonly ?string $database = null,
        private readonly ?string $username = null,
        private readonly ?string $password = null,
    ) {}

    public function getConnection(): PDO
    {
        if ($this->connection instanceof PDO) {
            return $this->connection;
        }

        $host = $this->host ?? ($_ENV['DB_HOST'] ?? '127.0.0.1');
        $port = $this->port ?? (int)($_ENV['DB_PORT'] ?? 3306);
        $db = $this->database ?? ($_ENV['DB_DATABASE'] ?? 'asian_store');
        $user = $this->username ?? ($_ENV['DB_USERNAME'] ?? 'root');
        $pass = $this->password ?? ($_ENV['DB_PASSWORD'] ?? '');

        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $host, $port, $db);

        try {
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            throw new PDOException('Database connection failed: ' . $e->getMessage(), (int)$e->getCode());
        }

        $this->connection = $pdo;
        return $this->connection;
    }
}


