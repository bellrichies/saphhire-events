<?php

class Database
{
    public static function migrate()
    {
        $db = \App\Core\Database::getInstance()->getConnection();

        $adminsTable = "CREATE TABLE IF NOT EXISTS `admins` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(100) NOT NULL,
            `email` VARCHAR(150) UNIQUE NOT NULL,
            `password` VARCHAR(255) NOT NULL,
            `role` ENUM('super_admin', 'editor') DEFAULT 'editor',
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX `idx_email` (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        $categoriesTable = "CREATE TABLE IF NOT EXISTS `gallery_categories` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(100) NOT NULL,
            `slug` VARCHAR(150) UNIQUE NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX `idx_slug` (`slug`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        $galleryTable = "CREATE TABLE IF NOT EXISTS `gallery_items` (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        $servicesTable = "CREATE TABLE IF NOT EXISTS `services` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `title` VARCHAR(255) NOT NULL,
            `description` TEXT NOT NULL,
            `image` VARCHAR(255),
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `deleted_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        $packageCategoriesTable = "CREATE TABLE IF NOT EXISTS `package_categories` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(120) NOT NULL,
            `slug` VARCHAR(160) UNIQUE NOT NULL,
            `description` TEXT,
            `image` VARCHAR(255) NULL,
            `display_order` INT DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX `idx_slug` (`slug`),
            INDEX `idx_order` (`display_order`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        $packagesTable = "CREATE TABLE IF NOT EXISTS `packages` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `category_id` INT NOT NULL,
            `title` VARCHAR(255) NOT NULL,
            `description` TEXT NOT NULL,
            `features` TEXT,
            `price_label` VARCHAR(255) NOT NULL,
            `currency` VARCHAR(10) DEFAULT 'EUR',
            `price_amount` DECIMAL(10,2) NULL,
            `image` VARCHAR(255) NULL,
            `is_featured` BOOLEAN DEFAULT FALSE,
            `display_order` INT DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (`category_id`) REFERENCES `package_categories` (`id`) ON DELETE CASCADE,
            INDEX `idx_category` (`category_id`),
            INDEX `idx_featured` (`is_featured`),
            INDEX `idx_order` (`display_order`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        $testimonialsTable = "CREATE TABLE IF NOT EXISTS `testimonials` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(150) NOT NULL,
            `content` TEXT NOT NULL,
            `image` VARCHAR(255),
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `deleted_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        $inquiriesTable = "CREATE TABLE IF NOT EXISTS `inquiries` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(150) NOT NULL,
            `email` VARCHAR(150) NOT NULL,
            `phone` VARCHAR(50),
            `event_type` VARCHAR(150),
            `event_date` DATE NULL,
            `message` TEXT NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX `idx_email` (`email`),
            INDEX `idx_created` (`created_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        $mediaTable = "CREATE TABLE IF NOT EXISTS `media_library` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `file_name` VARCHAR(255) NOT NULL,
            `original_name` VARCHAR(255) NOT NULL,
            `disk_path` VARCHAR(255) NOT NULL,
            `public_url` VARCHAR(255) NOT NULL,
            `mime_type` VARCHAR(100) NOT NULL,
            `media_type` ENUM('image','video','file') NOT NULL,
            `extension` VARCHAR(20) NOT NULL,
            `size_bytes` BIGINT UNSIGNED NOT NULL,
            `uploaded_by` INT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX `idx_media_type` (`media_type`),
            INDEX `idx_created_at` (`created_at`),
            INDEX `idx_uploaded_by` (`uploaded_by`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        try {
            $db->exec($adminsTable);
            $db->exec($categoriesTable);
            $db->exec($galleryTable);
            $db->exec($servicesTable);
            $db->exec($packageCategoriesTable);
            $db->exec($packagesTable);
            $db->exec($testimonialsTable);
            $db->exec($inquiriesTable);
            $db->exec($mediaTable);
            $galleryDisplayOrderColumn = $db->query("SHOW COLUMNS FROM `gallery_items` LIKE 'display_order'")->fetch();
            if (!$galleryDisplayOrderColumn) {
                $db->exec("ALTER TABLE `gallery_items` ADD COLUMN `display_order` INT DEFAULT 0 AFTER `is_featured`");
                $db->exec("ALTER TABLE `gallery_items` ADD INDEX `idx_display_order` (`display_order`)");
            }
            $categoryImageColumn = $db->query("SHOW COLUMNS FROM `package_categories` LIKE 'image'")->fetch();
            if (!$categoryImageColumn) {
                $db->exec("ALTER TABLE `package_categories` ADD COLUMN `image` VARCHAR(255) NULL AFTER `description`");
            }

            echo "✓ Database tables created successfully\n";
        } catch (\Exception $e) {
            echo "✗ Migration failed: " . $e->getMessage() . "\n";
        }
    }
}

if (php_sapi_name() === 'cli') {
    require_once __DIR__ . '/../public/index.php';
    Database::migrate();
}
