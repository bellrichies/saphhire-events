<?php

namespace App\Core;

use PDO;

class Model
{
    protected string $table;
    protected string $primaryKey = 'id';
    protected Database $db;
    protected PDO $connection;
    private static array $tableColumnsCache = [];

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->connection = $this->db->getConnection();
    }

    public function all()
    {
        $stmt = $this->connection->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findBy(string $column, string $value)
    {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table} WHERE {$column} = :value");
        $stmt->execute([':value' => $value]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function where(string $column, string $operator, mixed $value)
    {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table} WHERE {$column} {$operator} :value");
        $stmt->execute([':value' => $value]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        $normalizedData = $this->filterDataByExistingColumns(
            $this->normalizeDataKeys($data)
        );
        if (empty($normalizedData)) {
            return false;
        }

        $columns = implode(', ', array_map(static fn(string $key): string => "`{$key}`", array_keys($normalizedData)));
        $placeholders = ':' . implode(', :', array_keys($normalizedData));

        $stmt = $this->connection->prepare(
            "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})"
        );

        return $stmt->execute($this->toStatementParameters($normalizedData));
    }

    public function update(int $id, array $data): bool
    {
        $normalizedData = $this->filterDataByExistingColumns(
            $this->normalizeDataKeys($data)
        );
        if (empty($normalizedData)) {
            return false;
        }

        $setParts = [];
        foreach ($normalizedData as $key => $value) {
            $setParts[] = "`{$key}` = :{$key}";
        }
        $setClause = implode(', ', $setParts);

        $stmt = $this->connection->prepare(
            "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = :id"
        );

        $params = $this->toStatementParameters($normalizedData);
        $params[':id'] = $id;
        return $stmt->execute($params);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->connection->prepare(
            "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id"
        );
        return $stmt->execute([':id' => $id]);
    }

    public function count(): int
    {
        $stmt = $this->connection->query("SELECT COUNT(*) as count FROM {$this->table}");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }

    public function paginate(int $page = 1, int $perPage = 15)
    {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->connection->query(
            "SELECT * FROM {$this->table} LIMIT {$perPage} OFFSET {$offset}"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function normalizeDataKeys(array $data): array
    {
        $normalized = [];
        foreach ($data as $key => $value) {
            $normalizedKey = ltrim((string)$key, ':');
            if ($normalizedKey === '') {
                continue;
            }
            $normalized[$normalizedKey] = $value;
        }

        return $normalized;
    }

    private function toStatementParameters(array $data): array
    {
        $params = [];
        foreach ($data as $key => $value) {
            $params[':' . $key] = $value;
        }

        return $params;
    }

    protected function getCurrentLocale(): string
    {
        $locale = 'en';
        if (function_exists('getCurrentLanguage')) {
            $locale = (string) getCurrentLanguage();
        } elseif (isset($_SESSION['language'])) {
            $locale = (string) $_SESSION['language'];
        }

        $locale = strtolower(trim($locale));
        return $locale !== '' ? $locale : 'en';
    }

    protected function localizeRecord(array $row, array $fields, ?string $locale = null): array
    {
        $locale = strtolower(trim((string) ($locale ?? $this->getCurrentLocale())));
        if ($locale === '' || $locale === 'en') {
            return $row;
        }

        foreach ($fields as $field) {
            $localizedField = $field . '_' . $locale;
            if (!array_key_exists($localizedField, $row)) {
                continue;
            }

            $localizedValue = $row[$localizedField];
            if ($localizedValue === null) {
                continue;
            }

            if (is_string($localizedValue) && trim($localizedValue) === '') {
                continue;
            }

            $row[$field] = $localizedValue;
        }

        return $row;
    }

    protected function localizeRecords(array $rows, array $fields, ?string $locale = null): array
    {
        $localized = [];
        foreach ($rows as $row) {
            if (!is_array($row)) {
                $localized[] = $row;
                continue;
            }
            $localized[] = $this->localizeRecord($row, $fields, $locale);
        }

        return $localized;
    }

    protected function localizedColumnExpression(
        string $tableName,
        string $tableAlias,
        string $baseColumn,
        string $outputAlias,
        ?string $locale = null
    ): string {
        $locale = strtolower(trim((string) ($locale ?? $this->getCurrentLocale())));
        if ($locale === '' || $locale === 'en') {
            return "{$tableAlias}.{$baseColumn} AS {$outputAlias}";
        }

        $localizedColumn = $baseColumn . '_' . $locale;
        if (!$this->tableHasColumn($tableName, $localizedColumn)) {
            return "{$tableAlias}.{$baseColumn} AS {$outputAlias}";
        }

        return "COALESCE(NULLIF({$tableAlias}.{$localizedColumn}, ''), {$tableAlias}.{$baseColumn}) AS {$outputAlias}";
    }

    protected function tableHasColumn(string $tableName, string $column): bool
    {
        $columns = $this->getTableColumns($tableName);
        return in_array($column, $columns, true);
    }

    private function filterDataByExistingColumns(array $data): array
    {
        if (empty($data)) {
            return [];
        }

        $columns = $this->getTableColumns($this->table);
        if (empty($columns)) {
            return $data;
        }

        return array_filter(
            $data,
            static fn($key): bool => in_array($key, $columns, true),
            ARRAY_FILTER_USE_KEY
        );
    }

    private function getTableColumns(string $tableName): array
    {
        if (isset(self::$tableColumnsCache[$tableName])) {
            return self::$tableColumnsCache[$tableName];
        }

        try {
            $stmt = $this->connection->query("SHOW COLUMNS FROM `{$tableName}`");
            $columns = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
            $columnNames = array_values(array_filter(array_map(
                static fn(array $col): string => (string) ($col['Field'] ?? ''),
                $columns
            )));
            self::$tableColumnsCache[$tableName] = $columnNames;
            return $columnNames;
        } catch (\Throwable $e) {
            self::$tableColumnsCache[$tableName] = [];
            return [];
        }
    }
}
