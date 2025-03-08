<?php declare(strict_types=1);

namespace SwagHappyBirthdayEmail\ScheduledTask;

use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTask;

class SendHappyBirthdayEmailTask extends ScheduledTask
{
    public static function getTaskName(): string
    {
        return 'swag.send_happy_birthday_email_task';
    }

    public static function getDefaultInterval(): int
    {
        return 5; // 5 seconds
    }
}
