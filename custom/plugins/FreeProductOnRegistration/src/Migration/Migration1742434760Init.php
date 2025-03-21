<?php declare(strict_types=1);

namespace FreeProductOnRegistration\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;
use FreeProductOnRegistration\Service\EmailService;

/**
 * @internal
 */
class Migration1742434760Init extends MigrationStep
{
    public CONST NEW_CUSTOMER_TAG_ID = 'free_product_new_customer_tag_id';
    public CONST NEW_CUSTOMER_PROMOTION_ID = 'free_product_new_customer_promotion_id';
    public CONST NEW_CUSTOMER_DISCOUNT_ID = 'free_product_new_customer_discount_id';
    public CONST NEW_CUSTOMER_RULE_ID = 'free_product_new_customer_rule_id';
    public CONST NEW_CUSTOMER_CONDITION_ID = 'free_product_new_customer_condition_id';
    public CONST NEW_CUSTOMER_CATEGORY_ID = 'free_product_new_customer_category_id';

    public function getCreationTimestamp(): int
    {
        return 1742434760;
    }

    public function update(Connection $connection): void
    {
        $this->createNewCustomerTag($connection);
        $this->createRuleCustomerWithTagNewCustomer($connection);
        $this->createCategory($connection);
        $this->createPromotion($connection);
        $this->createProductInCategoryCondition($connection);
        $this->createEmailTemplate($connection);
    }

    private function createCategory(Connection $connection): void
    {
        $categoryId = Uuid::fromStringToHex(self::NEW_CUSTOMER_CATEGORY_ID);
        $versionId = Uuid::fromHexToBytes(Defaults::LIVE_VERSION);
        $parentId = $this->getRootCategoryId($connection);

        $connection->executeStatement('
            INSERT IGNORE INTO `category` (
                `id`,
                `version_id`,
                `type`,
                `created_at`,
                `parent_id`,
                `active`,
                `visible`,
                `child_count`,
                `path`
            ) VALUES (
                UNHEX(?),
                ?,
                "page",
                NOW(),
                ?,
                1,
                0,
                0,
                ""
            )
        ', [$categoryId, $versionId, $parentId]);


        $connection->executeStatement('
            INSERT IGNORE INTO `category_translation` (
                `category_id`,
                `category_version_id`,
                `language_id`,
                `name`,
                `created_at`
            ) VALUES (
                UNHEX(?),
                ?,
                UNHEX(?),
                "FREE PRODUCT",
                NOW()
            )
        ', [$categoryId, $versionId, Defaults::LANGUAGE_SYSTEM]);
    }

    private function createPromotion(Connection $connection): void
    {
        $promotionId = Uuid::fromStringToHex(self::NEW_CUSTOMER_PROMOTION_ID);
        $discountId = Uuid::fromStringToHex(self::NEW_CUSTOMER_DISCOUNT_ID);
        $saleChannelId = '019517c2b5a27313ad1f783e3fd93d9a';
        $ruleId = Uuid::fromStringToHex(self::NEW_CUSTOMER_RULE_ID);

        // Create promotion
        $connection->executeStatement('
            INSERT IGNORE INTO `promotion` (
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
                0,
                1,
                1,
                0,
                1,
                NOW()
            )
        ', [$promotionId]);

        // Create promotion translations
        $connection->executeStatement('
            INSERT IGNORE INTO `promotion_translation` (
                `promotion_id`,
                `language_id`,
                `name`,
                `created_at`
            ) VALUES (
                UNHEX(?),
                UNHEX(?),
                "FREE PRODUCT",
                NOW()
            )
        ', [$promotionId, Defaults::LANGUAGE_SYSTEM]);

        // Create promotion discount
        $connection->executeStatement('
            INSERT IGNORE INTO `promotion_discount` (
                `id`,
                `promotion_id`,
                `scope`,
                `type`,
                `value`,
                `consider_advanced_rules`,
                `sorter_key`,
                `applier_key`,
                `created_at`,
                `usage_key`
            ) VALUES (
                UNHEX(?),
                UNHEX(?),
                "cart",
                "percentage",
                100,
                1,
                "PRICE_DESC",
                1,
                NOW(),
                "ALL"
            )
        ', [$discountId, $promotionId]);

        $promotionSalesChannelId = Uuid::randomHex();

        $connection->executeStatement('
            INSERT IGNORE INTO `promotion_sales_channel` (
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

        $connection->executeStatement('
            INSERT IGNORE INTO `promotion_persona_rule` (
                `promotion_id`,
                `rule_id`
            ) VALUES (
                UNHEX(?),
                UNHEX(?)
            )
        ', [$promotionId, $ruleId]);
    }

    private function createRuleCustomerWithTagNewCustomer(Connection $connection)
    {
        $ruleId = Uuid::fromStringToHex(self::NEW_CUSTOMER_RULE_ID);
        $conditionId = Uuid::fromStringToHex(self::NEW_CUSTOMER_CONDITION_ID);
        $tagId = Uuid::fromStringToHex(self::NEW_CUSTOMER_TAG_ID);

        $connection->executeStatement('
            INSERT IGNORE INTO `rule` (
                `id`,
                `name`,
                `priority`,
                `created_at`
            ) VALUES (
                UNHEX(?),
                "RULE CUSTOMER WITH TAG NEW_CUSTOMER",
                1,
                NOW()
            )
        ', [$ruleId]);

        $connection->executeStatement('
            INSERT IGNORE INTO `rule_condition` (
                `id`,
                `rule_id`,
                `type`,
                `value`,
                `created_at`
            ) VALUES (
                UNHEX(?),
                UNHEX(?),
                "customerTag",
                ?,
                NOW()
            )
        ', [$conditionId, $ruleId, json_encode(['operator' => '=', 'identifiers' => [$tagId]])]);
    }

    private function createProductInCategoryCondition(Connection $connection)
    {
        $ruleId = Uuid::fromStringToHex('new_customer_bla_bla');
        $conditionId = Uuid::fromStringToHex('new_customer_bla_bla_2');
        $categoryId = Uuid::fromStringToHex(self::NEW_CUSTOMER_CATEGORY_ID);

        $discountId = Uuid::fromStringToHex(self::NEW_CUSTOMER_DISCOUNT_ID);

        $connection->executeStatement('
            INSERT IGNORE INTO `rule` (
                `id`,
                `name`,
                `priority`,
                `created_at`
            ) VALUES (
                UNHEX(?),
                "PRODUCT IN CATEGORY",
                1,
                NOW()
            )
        ', [$ruleId]);

        $connection->executeStatement('
            INSERT IGNORE INTO `rule_condition` (
                `id`,
                `rule_id`,
                `type`,
                `value`,
                `created_at`
            ) VALUES (
                UNHEX(?),
                UNHEX(?),
                "cartLineItemInCategory",
                ?,
                NOW()
            )
        ', [$conditionId, $ruleId, json_encode(['operator' => '=', 'categoryIds' => [$categoryId]])]);


        $connection->executeStatement('
            INSERT IGNORE INTO `promotion_discount_rule` (
                `discount_id`,
                `rule_id`
            ) VALUES (
                UNHEX(?),
                UNHEX(?)
            )
        ', [$discountId, $ruleId]);
    }

    private function createNewCustomerTag(Connection $connection): void
    {
        $tagId = Uuid::fromStringToHex(self::NEW_CUSTOMER_TAG_ID);

        $connection->executeStatement('
            INSERT IGNORE INTO `tag` (
                `id`,
                `name`,
                `created_at`
            ) VALUES (
                UNHEX(?),
                "new_customer",
                NOW()
            )
        ', [$tagId]);
    }

    private function getRootCategoryId(Connection $connection): string
    {
        $rootCategoryId = $connection->fetchOne(
            'SELECT id FROM category WHERE parent_id IS NULL AND active = 1 LIMIT 1'
        );

        if (!$rootCategoryId) {
            throw new \RuntimeException('Root category not found.');
        }

        return $rootCategoryId;
    }

    private function createEmailTemplate(Connection $connection)
    {
        $mailTemplateTypeId = $this->getMailTemplateTypeId($connection);

        $this->createMailTemplate($connection, $mailTemplateTypeId);
    }

    private function getMailTemplateTypeId(Connection $connection): string
    {
        $sql = <<<SQL
            SELECT id
            FROM mail_template_type
            WHERE technical_name = "customer_register"
        SQL;

        return Uuid::fromBytesToHex($connection->fetchOne($sql));
    }

    private function createMailTemplate(Connection $connection, string $mailTemplateTypeId): void
    {
        $mailTemplateId = Uuid::fromStringToHex(EmailService::EMAIL_TEMPLATE_NAME);
        $enGbLangId = $this->getLanguageIdByLocale($connection, 'en-GB');

        $connection->executeStatement("
        INSERT IGNORE INTO `mail_template`
            (id, mail_template_type_id, system_default, created_at)
        VALUES
            (:id, :mailTemplateTypeId, :systemDefault, :createdAt)
        ",[
            'id' => Uuid::fromHexToBytes($mailTemplateId),
            'mailTemplateTypeId' => Uuid::fromHexToBytes($mailTemplateTypeId),
            'systemDefault' => 0,
            'createdAt' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ]);

        $connection->executeStatement("
        INSERT IGNORE INTO `mail_template_translation`
            (mail_template_id, language_id, sender_name, subject, description, content_html, content_plain, created_at)
        VALUES
            (:mailTemplateId, :languageId, :senderName, :subject, :description, :contentHtml, :contentPlain, :createdAt)
        ",[
            'mailTemplateId' => Uuid::fromHexToBytes($mailTemplateId),
            'languageId' => Uuid::fromHexToBytes($enGbLangId),
            'senderName' => '{{ shopName }}',
            'subject' => 'FREE WELCOME PRODUCT',
            'description' => 'FREE WELCOME PRODUCT',
            'contentHtml' => $this->getContentHtmlEn(),
            'contentPlain' => $this->getContentPlainEn(),
            'createdAt' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ]);
    }

    private function getContentHtmlEn(): string
    {
        return <<<MAIL
        <div style="font-family: arial; font-size: 16px;">
            <h2>Welcome to {{ shopName }}!</h2>
            
            <p>Thank you for registering. As a welcome gift, here is your free product:</p>
            <p>Please check and claim your product here: http://127.0.0.1:8000/FREE-PRODUCT/ </p>
            
            
            <p>We hope you enjoy shopping with us!</p>
            
            <p>Best regards,<br>
            Your {{ shopName }} Team</p>
        </div>
        MAIL;
    }

    private function getContentPlainEn(): string
    {
        return <<<MAIL
Welcome to {{ shopName }}!

Thank you for registering. As a welcome gift, here is your free product:
Please check and claim your product here: http://127.0.0.1:8000/FREE-PRODUCT/
We hope you enjoy shopping with us!

Best regards,
Your {{ shopName }} Team
MAIL;
    }

    private function getLanguageIdByLocale(Connection $connection, string $locale): ?string
    {
        $sql = <<<SQL
        SELECT `language`.`id`
        FROM `language`
        INNER JOIN `locale` ON `locale`.`id` = `language`.`locale_id`
        WHERE `locale`.`code` = :code
        SQL;

        $languageId = $connection->executeQuery($sql, ['code' => $locale])->fetchOne();

        if (empty($languageId)) {
            return null;
        }

        return Uuid::fromBytesToHex($languageId);
    }
}
