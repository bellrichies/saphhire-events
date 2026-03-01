<?php

namespace App\Models;

use App\Core\Model;

class Package extends Model
{
    protected string $table = 'packages';
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
                "SELECT * FROM {$this->table} ORDER BY display_order ASC, created_at DESC"
            )->fetchAll();
            return $this->localizeRecords($rows, ['title', 'description', 'features', 'price_label']);
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function find(int $id)
    {
        $this->ensureSchema();

        try {
            $row = parent::find($id);
            if (!is_array($row)) {
                return $row;
            }
            return $this->localizeRecord($row, ['title', 'description', 'features', 'price_label']);
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function getAllWithCategory()
    {
        $this->ensureSchema();
        $categoryNameSelect = $this->localizedColumnExpression(
            'package_categories',
            'pc',
            'name',
            'category_name'
        );

        try {
            $rows = $this->connection->query(
                "SELECT p.*, {$categoryNameSelect}, pc.slug AS category_slug
                 FROM {$this->table} p
                 JOIN package_categories pc ON p.category_id = pc.id
                 ORDER BY pc.display_order ASC, p.display_order ASC, p.created_at DESC"
            )->fetchAll();
            return $this->localizeRecords($rows, ['title', 'description', 'features', 'price_label']);
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function getByCategoryId(int $categoryId)
    {
        $this->ensureSchema();

        try {
            $stmt = $this->connection->prepare(
                "SELECT * FROM {$this->table}
                 WHERE category_id = :category_id
                 ORDER BY display_order ASC, created_at DESC"
            );
            $stmt->execute([':category_id' => $categoryId]);
            return $this->localizeRecords($stmt->fetchAll(), ['title', 'description', 'features', 'price_label']);
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function getByCategorySlug(string $slug)
    {
        $this->ensureSchema();
        $categoryNameSelect = $this->localizedColumnExpression(
            'package_categories',
            'pc',
            'name',
            'category_name'
        );

        try {
            $stmt = $this->connection->prepare(
                "SELECT p.*, {$categoryNameSelect}, pc.slug AS category_slug
                 FROM {$this->table} p
                 JOIN package_categories pc ON p.category_id = pc.id
                 WHERE pc.slug = :slug
                 ORDER BY p.display_order ASC, p.created_at DESC"
            );
            $stmt->execute([':slug' => $slug]);
            return $this->localizeRecords($stmt->fetchAll(), ['title', 'description', 'features', 'price_label']);
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function findWithCategory(int $id)
    {
        $this->ensureSchema();
        $categoryNameSelect = $this->localizedColumnExpression(
            'package_categories',
            'pc',
            'name',
            'category_name'
        );

        try {
            $stmt = $this->connection->prepare(
                "SELECT p.*, {$categoryNameSelect}, pc.slug AS category_slug
                 FROM {$this->table} p
                 JOIN package_categories pc ON p.category_id = pc.id
                 WHERE p.id = :id"
            );
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch();
            if (!is_array($row)) {
                return $row;
            }
            return $this->localizeRecord($row, ['title', 'description', 'features', 'price_label']);
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function getFeatured(int $limit = 3)
    {
        $this->ensureSchema();
        $categoryNameSelect = $this->localizedColumnExpression(
            'package_categories',
            'pc',
            'name',
            'category_name'
        );

        try {
            $stmt = $this->connection->prepare(
                "SELECT p.*, {$categoryNameSelect}, pc.slug AS category_slug
                 FROM {$this->table} p
                 JOIN package_categories pc ON p.category_id = pc.id
                 WHERE p.is_featured = 1
                 ORDER BY p.display_order ASC, p.created_at DESC
                 LIMIT :limit"
            );
            $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
            $stmt->execute();
            return $this->localizeRecords($stmt->fetchAll(), ['title', 'description', 'features', 'price_label']);
        } catch (\Throwable $e) {
            return [];
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
                    `display_order` INT DEFAULT 0,
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    INDEX `idx_slug` (`slug`),
                    INDEX `idx_order` (`display_order`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
            );

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
