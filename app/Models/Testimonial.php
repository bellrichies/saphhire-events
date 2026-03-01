<?php

namespace App\Models;

use App\Core\Model;

class Testimonial extends Model
{
    protected string $table = 'testimonials';

    public function getLatest(int $limit = 3)
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $this->localizeRecords($stmt->fetchAll(), ['name', 'content']);
    }
}
