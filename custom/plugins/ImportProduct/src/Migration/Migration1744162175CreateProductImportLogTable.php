<?php declare(strict_types=1);

namespace ImportProduct\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1744162175CreateProductImportLogTable extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1744162175;
    }

    public function update(Connection $connection): void
    {
        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `product_import_log` (
    `id` BINARY(16) NOT NULL,
    `file_name` VARCHAR(255) NOT NULL,
    `status` VARCHAR(50) NOT NULL,
    `error_message` TEXT NULL,
    `total_records` INT NOT NULL,
    `success_records` INT NOT NULL,
    `failed_records` INT NOT NULL,
    `import_details` JSON NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;

        $connection->executeStatement($sql);
    }

    public function updateDestructive(Connection $connection): void
    {
    }
}
