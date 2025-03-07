<?php declare(strict_types=1);

namespace SwagHappyBirthdayEmail\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

/**
 * @internal
 */
class Migration1741328431CustomerBirthdayExtension extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1741328431;
    }

    public function update(Connection $connection): void
    {
        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `swag_customer_birthday_email` (
    `id` BINARY(16) NOT NULL,
    `customer_id` BINARY(16) NULL,
    `subscribe` TINYINT(1) COLLATE utf8mb4_unicode_ci,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3),
    PRIMARY KEY (`id`),
    CONSTRAINT `unique.swag_customer_birthday_email.customer` UNIQUE (`customer_id`),
    CONSTRAINT `fk.swag_customer_birthday_email.customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;
        $connection->executeStatement($sql);
    }
}
