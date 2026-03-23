<?php

namespace App\Models;

use App\Core\Model;

class Gallery extends Model
{
    protected string $table = 'gallery_items';
    private static bool $schemaEnsured = false;

    public function __construct()
    {
        parent::__construct();
        $this->ensureSchema();
    }

    public function getAllWithCategoryPaginated(int $page = 1, int $perPage = 10): array
    {
        $offset = max(0, ($page - 1) * $perPage);
        $categoryNameSelect = $this->localizedColumnExpression(
            'gallery_categories',
            'gc',
            'name',
            'category_name'
        );
        $stmt = $this->connection->prepare(
            "SELECT gi.*, {$categoryNameSelect} FROM {$this->table} gi
             JOIN gallery_categories gc ON gi.category_id = gc.id
             ORDER BY gi.display_order ASC, gi.created_at DESC
             LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $this->localizeRecords($stmt->fetchAll(), ['title', 'description', 'category_name']);
    }

    public function getAllWithCategory()
    {
        $categoryNameSelect = $this->localizedColumnExpression(
            'gallery_categories',
            'gc',
            'name',
            'category_name'
        );

        $rows = $this->connection->query(
            "SELECT gi.*, {$categoryNameSelect} FROM {$this->table} gi 
             JOIN gallery_categories gc ON gi.category_id = gc.id 
             ORDER BY gi.display_order ASC, gi.created_at DESC"
        )->fetchAll();

        return $this->localizeRecords($rows, ['title', 'description', 'category_name']);
    }

    public function getFeatured(int $limit = 6)
    {
        $categoryNameSelect = $this->localizedColumnExpression(
            'gallery_categories',
            'gc',
            'name',
            'category_name'
        );
        $stmt = $this->connection->prepare(
            "SELECT gi.*, {$categoryNameSelect} FROM {$this->table} gi
             JOIN gallery_categories gc ON gi.category_id = gc.id
             WHERE gi.is_featured = 1
             ORDER BY gi.display_order ASC, gi.created_at DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $this->localizeRecords($stmt->fetchAll(), ['title', 'description', 'category_name']);
    }

    public function getByCategory(int $categoryId, int $page = 1, int $perPage = 12)
    {
        $offset = ($page - 1) * $perPage;
        $categoryNameSelect = $this->localizedColumnExpression(
            'gallery_categories',
            'gc',
            'name',
            'category_name'
        );
        $stmt = $this->connection->prepare(
            "SELECT gi.*, {$categoryNameSelect} FROM {$this->table} gi
             JOIN gallery_categories gc ON gi.category_id = gc.id
             WHERE gi.category_id = :categoryId
             ORDER BY gi.display_order ASC, gi.created_at DESC
             LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':categoryId', $categoryId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $this->localizeRecords($stmt->fetchAll(), ['title', 'description', 'category_name']);
    }

    public function countByCategory(int $categoryId): int
    {
        $stmt = $this->connection->prepare(
            "SELECT COUNT(*) as count FROM {$this->table} WHERE category_id = :categoryId"
        );
        $stmt->execute([':categoryId' => $categoryId]);
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    }

    public function toggleFeatured(int $id): bool
    {
        $item = $this->find($id);
        if (!$item) return false;
        
        return $this->update($id, ['is_featured' => !$item['is_featured']]);
    }

    public function getRandomWithImages(int $limit = 12)
    {
        $categoryNameSelect = $this->localizedColumnExpression(
            'gallery_categories',
            'gc',
            'name',
            'category_name'
        );
        $stmt = $this->connection->prepare(
            "SELECT gi.*, {$categoryNameSelect} FROM {$this->table} gi
             JOIN gallery_categories gc ON gi.category_id = gc.id
             WHERE gi.image IS NOT NULL AND gi.image != ''
             ORDER BY gi.display_order ASC, gi.created_at DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $this->localizeRecords($stmt->fetchAll(), ['title', 'description', 'category_name']);
    }

    public function getRandomImagesOnly(int $limit = 12): array
    {
        $categoryNameSelect = $this->localizedColumnExpression(
            'gallery_categories',
            'gc',
            'name',
            'category_name'
        );
        $stmt = $this->connection->prepare(
            "SELECT gi.*, {$categoryNameSelect} FROM {$this->table} gi
             JOIN gallery_categories gc ON gi.category_id = gc.id
             WHERE gi.image IS NOT NULL
               AND gi.image != ''
               AND LOWER(gi.image) NOT LIKE '%.mp4'
               AND LOWER(gi.image) NOT LIKE '%.webm'
               AND LOWER(gi.image) NOT LIKE '%.ogv'
               AND LOWER(gi.image) NOT LIKE '%.mov'
               AND LOWER(gi.image) NOT LIKE '%/video/%'
             ORDER BY gi.display_order ASC, gi.created_at DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $this->localizeRecords($stmt->fetchAll(), ['title', 'description', 'category_name']);
    }

    public function getLatestImagesOnly(int $limit = 12): array
    {
        $categoryNameSelect = $this->localizedColumnExpression(
            'gallery_categories',
            'gc',
            'name',
            'category_name'
        );
        $stmt = $this->connection->prepare(
            "SELECT gi.*, {$categoryNameSelect} FROM {$this->table} gi
             JOIN gallery_categories gc ON gi.category_id = gc.id
             WHERE gi.image IS NOT NULL
               AND gi.image != ''
               AND LOWER(gi.image) NOT LIKE '%.mp4'
               AND LOWER(gi.image) NOT LIKE '%.webm'
               AND LOWER(gi.image) NOT LIKE '%.ogv'
               AND LOWER(gi.image) NOT LIKE '%.mov'
               AND LOWER(gi.image) NOT LIKE '%/video/%'
             ORDER BY gi.display_order ASC, gi.created_at DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return $this->localizeRecords($stmt->fetchAll(), ['title', 'description', 'category_name']);
    }

    public function nextDisplayOrder(): int
    {
        $this->ensureSchema();

        try {
            $result = $this->connection->query("SELECT COALESCE(MAX(display_order), 0) AS max_order FROM {$this->table}")->fetch();
            return ((int)($result['max_order'] ?? 0)) + 1;
        } catch (\Throwable $e) {
            return 1;
        }
    }

    public function reorder(array $orderedIds): bool
    {
        $this->ensureSchema();
        $orderedIds = array_values(array_unique(array_map('intval', $orderedIds)));
        $orderedIds = array_values(array_filter($orderedIds, static fn (int $id): bool => $id > 0));

        if ($orderedIds === []) {
            return false;
        }

        try {
            $existingIds = array_map('intval', array_column($this->all(), 'id'));
            sort($existingIds);
            $submittedIds = $orderedIds;
            sort($submittedIds);

            if ($existingIds !== $submittedIds) {
                return false;
            }

            $this->connection->beginTransaction();
            $stmt = $this->connection->prepare("UPDATE {$this->table} SET display_order = :display_order WHERE id = :id");

            foreach ($orderedIds as $index => $id) {
                $stmt->execute([
                    ':display_order' => $index + 1,
                    ':id' => $id,
                ]);
            }

            $this->connection->commit();
            return true;
        } catch (\Throwable $e) {
            if ($this->connection->inTransaction()) {
                $this->connection->rollBack();
            }
            return false;
        }
    }

    private function ensureSchema(): void
    {
        if (self::$schemaEnsured) {
            return;
        }

        try {
            $this->connection->exec(
                "CREATE TABLE IF NOT EXISTS `gallery_items` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `category_id` INT NOT NULL,
                    `title` VARCHAR(255) NOT NULL,
                    `description` TEXT,
                    `image` VARCHAR(255) NOT NULL,
                    `is_featured` BOOLEAN DEFAULT FALSE,
                    `display_order` INT DEFAULT 0,
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    `deleted_at` TIMESTAMP NULL,
                    FOREIGN KEY (`category_id`) REFERENCES `gallery_categories` (`id`) ON DELETE CASCADE,
                    INDEX `idx_category` (`category_id`),
                    INDEX `idx_featured` (`is_featured`),
                    INDEX `idx_display_order` (`display_order`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
            );

            if (!$this->tableHasColumn($this->table, 'display_order')) {
                $this->connection->exec(
                    "ALTER TABLE `gallery_items` ADD COLUMN `display_order` INT DEFAULT 0 AFTER `is_featured`"
                );
                $this->connection->exec(
                    "ALTER TABLE `gallery_items` ADD INDEX `idx_display_order` (`display_order`)"
                );
            }

            $missingOrderRows = $this->connection->query(
                "SELECT id FROM {$this->table} WHERE display_order = 0 ORDER BY created_at ASC, id ASC"
            )->fetchAll();

            if (!empty($missingOrderRows)) {
                $nextOrder = 1;
                $maxOrderRow = $this->connection->query(
                    "SELECT COALESCE(MAX(display_order), 0) AS max_order FROM {$this->table}"
                )->fetch();
                $maxOrder = (int)($maxOrderRow['max_order'] ?? 0);
                if ($maxOrder > 0) {
                    $nextOrder = $maxOrder + 1;
                }

                $stmt = $this->connection->prepare("UPDATE {$this->table} SET display_order = :display_order WHERE id = :id");
                foreach ($missingOrderRows as $row) {
                    $stmt->execute([
                        ':display_order' => $nextOrder++,
                        ':id' => (int)($row['id'] ?? 0),
                    ]);
                }
            }
        } catch (\Throwable $e) {
            // Keep runtime stable even if schema changes are unavailable.
        }

        self::$schemaEnsured = true;
    }
}
