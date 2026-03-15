<?php

namespace App\Models;

use App\Core\Model;

class Gallery extends Model
{
    protected string $table = 'gallery_items';

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
             ORDER BY gi.created_at DESC
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
             ORDER BY gi.created_at DESC"
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
             ORDER BY gi.created_at DESC
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
             ORDER BY gi.created_at DESC
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
             ORDER BY RAND()
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
             ORDER BY RAND()
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
             ORDER BY gi.created_at DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return $this->localizeRecords($stmt->fetchAll(), ['title', 'description', 'category_name']);
    }
}
