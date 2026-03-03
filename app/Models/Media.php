<?php

namespace App\Models;

use App\Core\Model;

class Media extends Model
{
    protected string $table = 'media_library';
    private static bool $schemaEnsured = false;

    public function __construct()
    {
        parent::__construct();
        $this->ensureSchema();
    }

    public function countFiltered(?string $type = null, ?string $search = null): int
    {
        $where = [];
        $params = [];

        if ($type !== null && $type !== '') {
            $where[] = 'media_type = :media_type';
            $params[':media_type'] = $type;
        }

        if ($search !== null && $search !== '') {
            $where[] = '(original_name LIKE :search OR file_name LIKE :search)';
            $params[':search'] = '%' . $search . '%';
        }

        $sql = "SELECT COUNT(*) AS count FROM {$this->table}";
        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();

        return (int)($row['count'] ?? 0);
    }

    public function listPaginated(int $page = 1, int $perPage = 24, ?string $type = null, ?string $search = null): array
    {
        $offset = max(0, ($page - 1) * $perPage);
        $where = [];
        $params = [];

        if ($type !== null && $type !== '') {
            $where[] = 'media_type = :media_type';
            $params[':media_type'] = $type;
        }

        if ($search !== null && $search !== '') {
            $where[] = '(original_name LIKE :search OR file_name LIKE :search)';
            $params[':search'] = '%' . $search . '%';
        }

        $sql = "SELECT * FROM {$this->table}";
        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' ORDER BY id DESC LIMIT :limit OFFSET :offset';

        $stmt = $this->connection->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function ensureSchema(): void
    {
        if (self::$schemaEnsured) {
            return;
        }

        try {
            $this->connection->exec(
                "CREATE TABLE IF NOT EXISTS `media_library` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `file_name` VARCHAR(255) NOT NULL,
                    `original_name` VARCHAR(255) NOT NULL,
                    `disk_path` VARCHAR(255) NOT NULL,
                    `public_url` VARCHAR(255) NOT NULL,
                    `mime_type` VARCHAR(100) NOT NULL,
                    `media_type` ENUM('image','video','file') NOT NULL,
                    `extension` VARCHAR(20) NOT NULL,
                    `size_bytes` BIGINT UNSIGNED NOT NULL,
                    `uploaded_by` INT NULL,
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    INDEX `idx_media_type` (`media_type`),
                    INDEX `idx_created_at` (`created_at`),
                    INDEX `idx_uploaded_by` (`uploaded_by`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
            );
        } catch (\Throwable $e) {
            // Keep runtime stable even if DB user cannot create tables.
        }

        self::$schemaEnsured = true;
    }
}

