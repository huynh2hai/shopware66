<?php declare(strict_types=1);

namespace ImportProduct\ScheduledTask;

use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTask;

class ProductImportTask extends ScheduledTask
{
    public static function getTaskName(): string
    {
        return 'import_product.import_task';
    }

    public static function getDefaultInterval(): int
    {
        return 1; // 5 minutes
    }
}
