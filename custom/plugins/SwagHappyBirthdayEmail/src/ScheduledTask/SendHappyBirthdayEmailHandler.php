<?php declare(strict_types=1);

namespace SwagHappyBirthdayEmail\ScheduledTask;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTaskHandler;
use SwagHappyBirthdayEmail\Service\BirthdayEmailService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(handles: SendHappyBirthdayEmailTask::class)]
class SendHappyBirthdayEmailHandler extends ScheduledTaskHandler
{
    private ?BirthdayEmailService $birthdayEmailService = null;

    public function run(): void
    {
        $this->birthdayEmailService->sendBirthdayEmails(Context::createDefaultContext());
    }

    public function setBirthdayEmailService(?BirthdayEmailService $birthdayEmailService): void
    {
        $this->birthdayEmailService = $birthdayEmailService;
    }
}
