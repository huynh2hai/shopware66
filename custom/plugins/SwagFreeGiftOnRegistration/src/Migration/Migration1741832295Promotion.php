<?php declare(strict_types=1);

namespace SwagFreeGiftOnRegistration\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;
use SwagFreeGiftOnRegistration\Service\EmailService;

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
            INSERT IGNORE INTO `promotion_translation` (
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
            INSERT IGNORE INTO `promotion_discount` (
                `id`,
                `promotion_id`,
                `scope`,
                `type`,
                `value`,
                `max_value`,
                `consider_advanced_rules`,
                `created_at`
            ) VALUES (
                UNHEX(?),
                UNHEX(?),
                "cart",
                "percentage",
                80,
                50,
                0,
                NOW()
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

        if (!empty($enGbLangId)) {
            $connection->executeStatement("
            INSERT IGNORE INTO `mail_template_translation`
                (mail_template_id, language_id, sender_name, subject, description, content_html, content_plain, created_at)
            VALUES
                (:mailTemplateId, :languageId, :senderName, :subject, :description, :contentHtml, :contentPlain, :createdAt)
            ",[
                'mailTemplateId' => Uuid::fromHexToBytes($mailTemplateId),
                'languageId' => Uuid::fromHexToBytes($enGbLangId),
                'senderName' => '{{ shopName }}',
                'subject' => 'Registration Gift',
                'description' => 'Registration Gift',
                'contentHtml' => $this->getContentHtmlEn(),
                'contentPlain' => $this->getContentPlainEn(),
                'createdAt' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
            ]);
        }

    }

    private function getContentHtmlEn(): string
    {
        return <<<MAIL
        <div style="font-family: arial; font-size: 16px;">
            <h2>Welcome to {{ shopName }}!</h2>
            
            <p>Thank you for registering. As a welcome gift, here's your personal voucher:</p>
            
            <div style="margin: 20px 0; padding: 20px; background-color: #f8f8f8; text-align: center;">
                <span style="font-size: 24px; font-weight: bold; color: #000;">{{ voucherCode }}</span>
            </div>
            
            <p>This voucher gives you discount on your first purchase.</p>
            
            <p>Simply enter this code during checkout to redeem your gift.</p>
            
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

Thank you for registering. As a welcome gift, here's your personal voucher:
{{ voucherCode }}
This voucher gives you discount on your first purchase.
Simply enter this code during checkout to redeem your gift.
We hope you enjoy shopping with us!

Best regards,
Your {{ shopName }} Team
MAIL;
    }
}
