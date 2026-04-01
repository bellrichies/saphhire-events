<?php

namespace App\Models;

use App\Core\Model;

class SiteSetting extends Model
{
    protected string $table = 'site_settings';
    private static bool $schemaEnsured = false;

    public function __construct()
    {
        parent::__construct();
        $this->ensureSchema();
    }

    public function allAsKeyValue(): array
    {
        $stmt = $this->connection->query("SELECT setting_key, setting_value FROM {$this->table}");
        $rows = $stmt ? $stmt->fetchAll() : [];

        $settings = [];
        foreach ($rows as $row) {
            $key = (string)($row['setting_key'] ?? '');
            if ($key === '') {
                continue;
            }

            $settings[$key] = (string)($row['setting_value'] ?? '');
        }

        return $settings;
    }

    public function set(string $key, ?string $value): bool
    {
        $normalizedKey = trim($key);
        if ($normalizedKey === '') {
            return false;
        }

        $stmt = $this->connection->prepare(
            "INSERT INTO {$this->table} (setting_key, setting_value)
             VALUES (:setting_key, :setting_value)
             ON DUPLICATE KEY UPDATE
                setting_value = VALUES(setting_value),
                updated_at = CURRENT_TIMESTAMP"
        );

        return $stmt->execute([
            ':setting_key' => $normalizedKey,
            ':setting_value' => (string)($value ?? ''),
        ]);
    }

    public function setMany(array $values): bool
    {
        if ($values === []) {
            return true;
        }

        try {
            $this->connection->beginTransaction();
            foreach ($values as $key => $value) {
                if (!$this->set((string)$key, $value === null ? null : (string)$value)) {
                    $this->connection->rollBack();
                    return false;
                }
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

    public function defaults(): array
    {
        return [
            'site_name' => (string)appConfig('site.name', 'Sapphire Events & Decorations'),
            'site_tagline' => (string)appConfig('site.tagline', 'Adding glitz & glam to your events'),
            'site_description' => (string)appConfig('site.description', ''),
            'site_email' => (string)appConfig('site.email', ''),
            'site_phone' => (string)appConfig('site.phone', ''),
            'site_address' => (string)appConfig('site.address', ''),
            'site_registration_code' => (string)appConfig('site.registration_code', ''),
            'site_logo' => 'assets/images/logo.png',
            'site_favicon' => 'assets/images/favicon.png',
            'site_og_image' => 'assets/images/ceo-image.png',
            'theme_primary_color' => (string)appConfig('colors.primary', '#0F3D3E'),
            'theme_accent_color' => (string)appConfig('colors.accent', '#C8A951'),
            'theme_light_color' => (string)appConfig('colors.light', '#F8F5F2'),
            'theme_dark_color' => (string)appConfig('colors.dark', '#1C1C1C'),
            'theme_body_font' => 'lora',
            'theme_heading_font' => 'syncopate',
            'theme_display_font' => 'dancing-script',
            'theme_ui_font' => 'montserrat',
            'theme_body_size' => '1rem',
            'theme_h1_size' => '3.5rem',
            'theme_h2_size' => '2.5rem',
            'inner_hero_render_mode' => 'gradient_only',
            'inner_hero_background_image' => '',
            'inner_hero_overlay_opacity' => '65',
            'inner_hero_overlay_start' => '#0F3D3E',
            'inner_hero_overlay_end' => '#1C1C1C',
            'social_instagram' => (string)appConfig('social.instagram', ''),
            'social_facebook' => (string)appConfig('social.facebook', ''),
            'social_tiktok' => (string)appConfig('social.tiktok', ''),
            'social_whatsapp' => (string)appConfig('social.whatsapp', ''),
        ];
    }

    private function ensureSchema(): void
    {
        if (self::$schemaEnsured) {
            return;
        }

        try {
            $this->connection->exec(
                "CREATE TABLE IF NOT EXISTS `site_settings` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `setting_key` VARCHAR(100) NOT NULL,
                    `setting_value` TEXT NULL,
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    UNIQUE KEY `uniq_setting_key` (`setting_key`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
            );

            $existing = $this->allAsKeyValue();
            $missingDefaults = [];
            foreach ($this->defaults() as $key => $value) {
                if (!array_key_exists($key, $existing)) {
                    $missingDefaults[$key] = $value;
                }
            }

            if ($missingDefaults !== []) {
                $this->setMany($missingDefaults);
            }
        } catch (\Throwable $e) {
            // Keep runtime stable even if DB permissions block schema updates.
        }

        self::$schemaEnsured = true;
    }
}
