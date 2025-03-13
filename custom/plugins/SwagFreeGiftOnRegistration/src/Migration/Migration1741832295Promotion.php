<?php declare(strict_types=1);

namespace SwagFreeGiftOnRegistration\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

/**
 * @internal
 */
class Migration1741832295Promotion extends MigrationStep
{
    public CONST PROMOTION_NAME = 'swag_free_gift_on_registration_promotion';

    public function getCreationTimestamp(): int
    {
        return 1741832295;
    }

    public function update(Connection $connection): void
    {
        $promotionId = Uuid::fromStringToHex(self::PROMOTION_NAME);
        $discountId = Uuid::randomHex();
        $saleChannelId = '019517c2b5a27313ad1f783e3fd93d9a';

        // Create promotion
        $connection->executeStatement('
            INSERT INTO `promotion` (
                `id`,
                `active`,
                `use_codes`,
                `use_individual_codes`,
                `max_redemptions_per_customer`,
                `exclusive`,
                `priority`,
                `created_at`
            ) VALUES (
                UNHEX(?),
                1,
                1,
                1,
                1,
                0,
                1,
                NOW()
            )
        ', [$promotionId]);

        // Create promotion translations
        $connection->executeStatement('
            INSERT INTO `promotion_translation` (
                `promotion_id`,
                `language_id`,
                `name`,
                `created_at`
            ) VALUES (
                UNHEX(?),
                UNHEX(?),
                "Registration Gift",
                NOW()
            )
        ', [$promotionId, Defaults::LANGUAGE_SYSTEM]);

        // Create promotion discount
        $connection->executeStatement('
            INSERT INTO `promotion_discount` (
                `id`,
                `promotion_id`,
                `scope`,
                `type`,
                `value`,
                `consider_advanced_rules`,
                `created_at`
            ) VALUES (
                UNHEX(?),
                UNHEX(?),
                "cart",
                "percentage",
                80,
                0,
                NOW()
            )
        ', [$discountId, $promotionId]);

        $promotionSalesChannelId = Uuid::randomHex();

        $connection->executeStatement('
            INSERT INTO `promotion_sales_channel` (
                `id`,
                `promotion_id`,
                `sales_channel_id`,
                `priority`,
                `created_at`
            ) VALUES (
                UNHEX(?),
                UNHEX(?),
                UNHEX(?),
                1,
                NOW()
            )
        ', [$promotionSalesChannelId, $promotionId, $saleChannelId]);
    }
}
