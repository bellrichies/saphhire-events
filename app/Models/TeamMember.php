<?php

namespace App\Models;

use App\Core\Model;

class TeamMember extends Model
{
    protected string $table = 'team_members';
    private static bool $schemaEnsured = false;

    public function __construct()
    {
        parent::__construct();
        $this->ensureSchemaAndSeed();
    }

    public function all()
    {
        $this->ensureSchemaAndSeed();

        try {
            $rows = $this->connection->query(
                "SELECT * FROM {$this->table} ORDER BY display_order ASC, created_at ASC"
            )->fetchAll();
            return $this->localizeRecords($rows, ['name', 'role', 'bio']);
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function getActive(): array
    {
        $this->ensureSchemaAndSeed();

        try {
            $rows = $this->connection->query(
                "SELECT * FROM {$this->table}
                 WHERE is_active = 1
                 ORDER BY display_order ASC, created_at ASC"
            )->fetchAll();
            return $this->localizeRecords($rows, ['name', 'role', 'bio']);
        } catch (\Throwable $e) {
            return [];
        }
    }

    private function ensureSchemaAndSeed(): void
    {
        if (self::$schemaEnsured) {
            return;
        }

        try {
            $this->connection->exec(
                "CREATE TABLE IF NOT EXISTS `team_members` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `name` VARCHAR(150) NOT NULL,
                    `role` VARCHAR(150) NOT NULL,
                    `bio` TEXT NOT NULL,
                    `image` VARCHAR(255) NOT NULL,
                    `display_order` INT DEFAULT 0,
                    `is_active` TINYINT(1) DEFAULT 1,
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    INDEX `idx_active_order` (`is_active`, `display_order`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
            );

            $countStmt = $this->connection->query("SELECT COUNT(*) AS count FROM {$this->table}");
            $count = (int)($countStmt->fetch()['count'] ?? 0);
            if ($count === 0) {
                $seed = [
                    [
                        'name' => 'Kristina',
                        'role' => 'Founder & Director',
                        'bio' => 'Leads strategy, client experience, and execution quality across all major projects.',
                        'image' => '/assets/images/founder-ceo.avif',
                        'display_order' => 1,
                        'is_active' => 1,
                    ],
                    [
                        'name' => 'Marina',
                        'role' => 'Creative Director',
                        'bio' => 'Shapes visual concepts and styling systems for cohesive, high-impact event aesthetics.',
                        'image' => '/assets/images/founder-ceo-2.avif',
                        'display_order' => 2,
                        'is_active' => 1,
                    ],
                    [
                        'name' => 'Sophia',
                        'role' => 'Event Coordinator',
                        'bio' => 'Owns timelines, vendor alignment, and on-site orchestration to ensure seamless delivery.',
                        'image' => '/assets/images/founder-ceo-3.avif',
                        'display_order' => 3,
                        'is_active' => 1,
                    ],
                    [
                        'name' => 'Elena',
                        'role' => 'Client Success Lead',
                        'bio' => 'Ensures each client feels informed, supported, and confident throughout the planning journey.',
                        'image' => '/assets/images/team-2.avif',
                        'display_order' => 4,
                        'is_active' => 1,
                    ],
                ];

                $stmt = $this->connection->prepare(
                    "INSERT INTO {$this->table} (name, role, bio, image, display_order, is_active)
                     VALUES (:name, :role, :bio, :image, :display_order, :is_active)"
                );

                foreach ($seed as $row) {
                    $stmt->execute($row);
                }
            }
        } catch (\Throwable $e) {
            // Keep runtime stable even if DB user cannot create tables.
        }

        self::$schemaEnsured = true;
    }
}
