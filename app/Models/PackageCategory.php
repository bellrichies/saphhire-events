<?php

namespace App\Models;

use App\Core\Model;

class PackageCategory extends Model
{
    protected string $table = 'package_categories';
    private static bool $schemaEnsured = false;

    public function __construct()
    {
        parent::__construct();
        $this->ensureSchema();
    }

    public function all()
    {
        $this->ensureSchema();

        try {
            $rows = $this->connection->query(
                "SELECT * FROM {$this->table} ORDER BY display_order ASC, name ASC"
            )->fetchAll();
            return $this->localizeRecords($rows, ['name', 'description']);
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function findBySlug(string $slug)
    {
        $this->ensureSchema();

        try {
            $row = $this->findBy('slug', $slug);
            if (!is_array($row)) {
                return $row;
            }
            return $this->localizeRecord($row, ['name', 'description']);
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function getWithPackageCount(?int $limit = null)
    {
        $this->ensureSchema();

        try {
            $sql =
                "SELECT pc.*, COUNT(p.id) AS package_count,
                        MIN(CASE WHEN p.price_amount IS NULL THEN NULL ELSE p.price_amount END) AS min_price,
                        MAX(CASE WHEN p.price_amount IS NULL THEN NULL ELSE p.price_amount END) AS max_price
                 FROM {$this->table} pc
                 LEFT JOIN packages p ON pc.id = p.category_id
                 GROUP BY pc.id
                 ORDER BY pc.display_order ASC, pc.name ASC";

            if ($limit !== null) {
                $sql .= " LIMIT :limit";
                $stmt = $this->connection->prepare($sql);
                $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
                $stmt->execute();
                $rows = $stmt->fetchAll();
            } else {
                $rows = $this->connection->query($sql)->fetchAll();
            }

            return $this->localizeRecords($rows, ['name', 'description']);
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function findRaw(int $id)
    {
        $this->ensureSchema();

        try {
            return parent::find($id);
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function findBySlugRaw(string $slug)
    {
        $this->ensureSchema();

        try {
            return parent::findBy('slug', $slug);
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function ensureSchema(): void
    {
        if (self::$schemaEnsured) {
            return;
        }

        try {
            $this->connection->exec(
                "CREATE TABLE IF NOT EXISTS `package_categories` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `name` VARCHAR(120) NOT NULL,
                    `slug` VARCHAR(160) UNIQUE NOT NULL,
                    `description` TEXT,
                    `image` VARCHAR(255) NULL,
                    `display_order` INT DEFAULT 0,
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    INDEX `idx_slug` (`slug`),
                    INDEX `idx_order` (`display_order`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
            );

            if (!$this->tableHasColumn($this->table, 'image')) {
                $this->connection->exec(
                    "ALTER TABLE `package_categories` ADD COLUMN `image` VARCHAR(255) NULL AFTER `description`"
                );
            }

            $this->connection->exec(
                "CREATE TABLE IF NOT EXISTS `packages` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `category_id` INT NOT NULL,
                    `title` VARCHAR(255) NOT NULL,
                    `description` TEXT NOT NULL,
                    `features` TEXT,
                    `price_label` VARCHAR(255) NOT NULL,
                    `currency` VARCHAR(10) DEFAULT 'EUR',
                    `price_amount` DECIMAL(10,2) NULL,
                    `image` VARCHAR(255) NULL,
                    `is_featured` BOOLEAN DEFAULT FALSE,
                    `display_order` INT DEFAULT 0,
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    FOREIGN KEY (`category_id`) REFERENCES `package_categories` (`id`) ON DELETE CASCADE,
                    INDEX `idx_category` (`category_id`),
                    INDEX `idx_featured` (`is_featured`),
                    INDEX `idx_order` (`display_order`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
            );
        } catch (\Throwable $e) {
            // Keep runtime stable even if DB user cannot create tables.
        }

        self::$schemaEnsured = true;
    }
}
