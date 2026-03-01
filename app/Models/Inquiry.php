<?php

namespace App\Models;

use App\Core\Model;

class Inquiry extends Model
{
    protected string $table = 'inquiries';
    private static bool $schemaEnsured = false;

    public function __construct()
    {
        parent::__construct();
        $this->ensureSchema();
    }

    public function createInquiry(array $data): bool
    {
        $payload = [
            'name' => $data['name'] ?? '',
            'email' => $data['email'] ?? '',
            'phone' => $data['phone'] ?? '',
            'event_type' => $data['event_type'] ?? '',
            'message' => $data['message'] ?? '',
        ];

        if (!empty($data['event_date'])) {
            $payload['event_date'] = $data['event_date'];
        }

        return $this->create($payload);
    }

    public function getLatest(int $limit = 10)
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getSenderEmails(): array
    {
        $stmt = $this->connection->prepare(
            "SELECT DISTINCT email
             FROM {$this->table}
             WHERE email IS NOT NULL AND email <> ''
             ORDER BY email ASC"
        );
        $stmt->execute();

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        $emails = [];
        foreach ($rows as $row) {
            $email = trim((string)($row['email'] ?? ''));
            if ($email !== '') {
                $emails[] = $email;
            }
        }

        return $emails;
    }

    private function ensureSchema(): void
    {
        if (self::$schemaEnsured) {
            return;
        }

        try {
            $this->connection->exec(
                "CREATE TABLE IF NOT EXISTS `inquiries` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `name` VARCHAR(150) NOT NULL,
                    `email` VARCHAR(150) NOT NULL,
                    `phone` VARCHAR(50),
                    `event_type` VARCHAR(150),
                    `event_date` DATE NULL,
                    `message` TEXT NOT NULL,
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    INDEX `idx_email` (`email`),
                    INDEX `idx_created` (`created_at`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
            );

            $columnCheck = $this->connection->query("SHOW COLUMNS FROM `{$this->table}` LIKE 'event_date'");
            if (!$columnCheck->fetch()) {
                $this->connection->exec(
                    "ALTER TABLE `{$this->table}` ADD COLUMN `event_date` DATE NULL AFTER `event_type`"
                );
            }
        } catch (\Throwable $e) {
            // Keep runtime stable if DB user cannot alter schema.
        }

        self::$schemaEnsured = true;
    }
}
