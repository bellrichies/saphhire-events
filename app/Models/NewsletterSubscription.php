<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class NewsletterSubscription extends Model
{
    protected string $table = 'newsletter_subscriptions';
    private static bool $schemaEnsured = false;

    public function __construct()
    {
        parent::__construct();
        $this->ensureSchema();
    }

    public function createOrReactivate(string $email, array $context = []): array
    {
        $normalizedEmail = $this->normalizeEmail($email);
        if ($normalizedEmail === '') {
            return ['success' => false, 'reason' => 'invalid_email'];
        }

        $existing = $this->findByNormalizedEmail($normalizedEmail);
        $now = date('Y-m-d H:i:s');
        $source = $this->sanitizeSource((string)($context['source'] ?? 'footer'));
        $locale = $this->sanitizeLocale((string)($context['locale'] ?? ''));
        $ipAddress = $this->sanitizeIp((string)($context['ip_address'] ?? ''));
        $userAgent = $this->sanitizeUserAgent((string)($context['user_agent'] ?? ''));

        if ($existing) {
            if (($existing['status'] ?? '') === 'active') {
                return [
                    'success' => true,
                    'is_new' => false,
                    'reactivated' => false,
                    'already_subscribed' => true,
                    'id' => (int)$existing['id'],
                ];
            }

            $stmt = $this->connection->prepare(
                "UPDATE {$this->table}
                 SET status = :status,
                     source = :source,
                     locale = :locale,
                     ip_address = :ip_address,
                     user_agent = :user_agent,
                     reactivated_at = :reactivated_at,
                     updated_at = :updated_at
                 WHERE id = :id"
            );

            $ok = $stmt->execute([
                ':status' => 'active',
                ':source' => $source,
                ':locale' => $locale,
                ':ip_address' => $ipAddress,
                ':user_agent' => $userAgent,
                ':reactivated_at' => $now,
                ':updated_at' => $now,
                ':id' => (int)$existing['id'],
            ]);

            return [
                'success' => $ok,
                'is_new' => false,
                'reactivated' => $ok,
                'already_subscribed' => false,
                'id' => (int)$existing['id'],
            ];
        }

        try {
            $stmt = $this->connection->prepare(
                "INSERT INTO {$this->table}
                 (email, email_normalized, status, source, locale, ip_address, user_agent, subscribed_at, created_at, updated_at)
                 VALUES
                 (:email, :email_normalized, :status, :source, :locale, :ip_address, :user_agent, :subscribed_at, :created_at, :updated_at)"
            );

            $ok = $stmt->execute([
                ':email' => trim($email),
                ':email_normalized' => $normalizedEmail,
                ':status' => 'active',
                ':source' => $source,
                ':locale' => $locale,
                ':ip_address' => $ipAddress,
                ':user_agent' => $userAgent,
                ':subscribed_at' => $now,
                ':created_at' => $now,
                ':updated_at' => $now,
            ]);

            return [
                'success' => $ok,
                'is_new' => $ok,
                'reactivated' => false,
                'already_subscribed' => false,
                'id' => (int)$this->connection->lastInsertId(),
            ];
        } catch (\Throwable $e) {
            if (($e instanceof \PDOException) && ($e->getCode() === '23000')) {
                return [
                    'success' => true,
                    'is_new' => false,
                    'reactivated' => false,
                    'already_subscribed' => true,
                ];
            }

            throw $e;
        }
    }

    public function findByNormalizedEmail(string $normalizedEmail): ?array
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM {$this->table} WHERE email_normalized = :email_normalized LIMIT 1"
        );
        $stmt->execute([':email_normalized' => $normalizedEmail]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function countRecentByIp(string $ipAddress, int $seconds): int
    {
        if ($ipAddress === '' || $seconds <= 0) {
            return 0;
        }

        $windowStart = date('Y-m-d H:i:s', time() - $seconds);

        $stmt = $this->connection->prepare(
            "SELECT COUNT(*) AS count_value
             FROM {$this->table}
             WHERE ip_address = :ip_address AND created_at >= :window_start"
        );
        $stmt->execute([
            ':ip_address' => $ipAddress,
            ':window_start' => $windowStart,
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['count_value'] ?? 0);
    }

    public function getLatest(int $limit = 200, string $search = '', string $status = 'all'): array
    {
        $limit = max(1, min(1000, $limit));
        $search = trim($search);
        $status = strtolower(trim($status));

        $where = [];
        $params = [];

        if ($status !== '' && in_array($status, ['active', 'unsubscribed', 'bounced'], true)) {
            $where[] = 'status = :status';
            $params[':status'] = $status;
        }

        if ($search !== '') {
            $where[] = '(email LIKE :search OR source LIKE :search OR ip_address LIKE :search)';
            $params[':search'] = '%' . $search . '%';
        }

        $sql = "SELECT * FROM {$this->table}";
        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' ORDER BY created_at DESC LIMIT :limit';

        $stmt = $this->connection->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getAll(string $search = '', string $status = 'all'): array
    {
        $search = trim($search);
        $status = strtolower(trim($status));

        $where = [];
        $params = [];

        if ($status !== '' && in_array($status, ['active', 'unsubscribed', 'bounced'], true)) {
            $where[] = 'status = :status';
            $params[':status'] = $status;
        }

        if ($search !== '') {
            $where[] = '(email LIKE :search OR source LIKE :search OR ip_address LIKE :search)';
            $params[':search'] = '%' . $search . '%';
        }

        $sql = "SELECT * FROM {$this->table}";
        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' ORDER BY created_at DESC';

        $stmt = $this->connection->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function updateStatus(int $id, string $status): bool
    {
        $status = strtolower(trim($status));
        if (!in_array($status, ['active', 'unsubscribed', 'bounced'], true)) {
            return false;
        }

        $stmt = $this->connection->prepare(
            "UPDATE {$this->table} SET status = :status, updated_at = :updated_at WHERE id = :id"
        );

        return $stmt->execute([
            ':status' => $status,
            ':updated_at' => date('Y-m-d H:i:s'),
            ':id' => $id,
        ]);
    }

    public function normalizeEmail(string $email): string
    {
        return strtolower(trim($email));
    }

    private function sanitizeSource(string $source): string
    {
        $source = trim($source);
        if ($source === '') {
            return 'footer';
        }
        return substr($source, 0, 120);
    }

    private function sanitizeLocale(string $locale): string
    {
        $locale = strtolower(trim($locale));
        if ($locale === '') {
            return 'en';
        }
        return substr($locale, 0, 10);
    }

    private function sanitizeIp(string $ipAddress): string
    {
        $ipAddress = trim($ipAddress);
        if ($ipAddress === '') {
            return '';
        }

        if (!filter_var($ipAddress, FILTER_VALIDATE_IP)) {
            return '';
        }

        return substr($ipAddress, 0, 45);
    }

    private function sanitizeUserAgent(string $userAgent): string
    {
        $userAgent = trim($userAgent);
        if ($userAgent === '') {
            return '';
        }
        return substr($userAgent, 0, 512);
    }

    private function ensureSchema(): void
    {
        if (self::$schemaEnsured) {
            return;
        }

        try {
            $this->connection->exec(
                "CREATE TABLE IF NOT EXISTS `newsletter_subscriptions` (
                    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    `email` VARCHAR(254) NOT NULL,
                    `email_normalized` VARCHAR(254) NOT NULL,
                    `status` ENUM('active','unsubscribed','bounced') NOT NULL DEFAULT 'active',
                    `source` VARCHAR(120) NOT NULL DEFAULT 'footer',
                    `locale` VARCHAR(10) NOT NULL DEFAULT 'en',
                    `ip_address` VARCHAR(45) NULL,
                    `user_agent` VARCHAR(512) NULL,
                    `subscribed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `reactivated_at` TIMESTAMP NULL DEFAULT NULL,
                    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    UNIQUE KEY `uniq_email_normalized` (`email_normalized`),
                    KEY `idx_newsletter_status` (`status`),
                    KEY `idx_newsletter_created` (`created_at`),
                    KEY `idx_newsletter_ip` (`ip_address`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
            );
        } catch (\Throwable $e) {
            // Keep runtime stable if DB user cannot alter schema.
        }

        self::$schemaEnsured = true;
    }
}
