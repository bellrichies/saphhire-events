<?php

namespace App\Models;

use App\Core\Model;

class Service extends Model
{
    protected string $table = 'services';

    public function getAllWithImage()
    {
        $rows = $this->connection->query(
            "SELECT * FROM {$this->table} ORDER BY created_at DESC"
        )->fetchAll();

        return $this->localizeRecords($rows, ['title', 'description']);
    }

    public function find(int $id)
    {
        $row = parent::find($id);
        if (!is_array($row)) {
            return $row;
        }

        return $this->localizeRecord($row, ['title', 'description']);
    }
}
