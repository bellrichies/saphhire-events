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

    public function getLatestWithImage(int $limit = 6): array
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return $this->localizeRecords($stmt->fetchAll(), ['title', 'description']);
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
