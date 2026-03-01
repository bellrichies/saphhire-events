<?php

namespace App\Models;

use App\Core\Model;

class Category extends Model
{
    protected string $table = 'gallery_categories';

    public function all()
    {
        $rows = parent::all();
        if (!is_array($rows)) {
            return $rows;
        }

        return $this->localizeRecords($rows, ['name', 'description']);
    }

    public function findBySlug(string $slug)
    {
        $row = $this->findBy('slug', $slug);
        if (!is_array($row)) {
            return $row;
        }

        return $this->localizeRecord($row, ['name', 'description']);
    }

    public function getWithItemCount()
    {
        $rows = $this->connection->query(
            "SELECT gc.*, COUNT(gi.id) as item_count FROM {$this->table} gc
             LEFT JOIN gallery_items gi ON gc.id = gi.category_id
             GROUP BY gc.id
             ORDER BY gc.name ASC"
        )->fetchAll();
        return $this->localizeRecords($rows, ['name', 'description']);
    }
}
