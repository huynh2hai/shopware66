<?php

namespace SwagHappyBirthdayEmail\Service;

use Shopware\Core\Checkout\Customer\CustomerCollection;
use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Content\MailTemplate\MailTemplateEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\UuidException;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use SwagHappyBirthdayEmail\Extension\Content\Customer\BirthdayEmailEntity;
use Shopware\Core\Content\Mail\Service\MailService;
use Shopware\Core\Framework\Uuid\Uuid;

class BirthdayEmailService
{
    public const TEMPLATE_NAME = 'send_happy_birth_day.default_email_template';

    public function __construct(
        private EntityRepository $customerRepository,
        private EntityRepository $mailTemplateRepository,
        private MailService $mailService,
        private SystemConfigService $systemConfigService
    ) {
    }

    public function sendBirthdayEmails(Context $context): void
    {
        $mailTemplate = $this->getEmailTemplate($context);

        if (!$mailTemplate) {
            throw new \RuntimeException('Mail template not found');
        }

        $this->configureEmailSender();

        $customers = $this->getCustomers($context);

        foreach ($customers as $customer) {
            $today = new \DateTime();

            if($customer->getBirthday()->format('d-m') !== $today->format('d-m')) {
                continue;
            }

            /** @var BirthdayEmailEntity $birthdayEmail */
            $birthdayEmail = $customer->getExtension('birthdayEmail');

            if (!$birthdayEmail || !$birthdayEmail->getSubscribe()) {
                continue;
            }

            $this->sendEmail($customer, $mailTemplate, $context);
        }
    }

    private function sendEmail(CustomerEntity $customer, MailTemplateEntity $mailTemplate, Context $context): void
    {
        $data = [
            'recipients' => [
                $customer->getEmail() => 'Hai Huynh'
            ],
            'senderName' => 'TheOne',
            'senderEmail' => 'hai.huynh@nfq.com',
            'subject' => "Happy Birthday Email",
            'contentHtml' => $mailTemplate->getContentHtml(),
            'contentPlain' => $mailTemplate->getContentPlain(),
            'salesChannelId' => '98432def39fc4624b33213a56b8c944d',
            'templateData' => [
                'customer' => $customer
            ],
        ];

        $this->mailService->send($data, $context, [
            'customer' => $customer
        ]);
    }

    private function getCustomers($context): CustomerCollection
    {
        $criteria = new Criteria();
        $criteria->addFilter(
            new EqualsFilter('active', true)
        );

        return $this->customerRepository->search($criteria, $context)->getEntities();
    }

    private function getEmailTemplate(Context $context): ?MailTemplateEntity
    {
        $criteria = new Criteria();
        $criteria->addFilter(
            new EqualsFilter('id', Uuid::fromStringToHex(self::TEMPLATE_NAME))
        );

        return $this->mailTemplateRepository->search($criteria, $context)->first();
    }


    private function configureEmailSender(): void
    {
        throw new \Exception();
        $smtpConfigure = $this->systemConfigService->get('SwagHappyBirthdayEmail.config');

        if (
            empty($smtpConfigure['smtpHost']) ||
            empty($smtpConfigure['smtpPort']) ||
            empty($smtpConfigure['smtpUsername']) ||
            empty($smtpConfigure['smtpPassword'])
        ) {
            throw new \RuntimeException('SMTP Server Settings for Send Happy Birthday Email are empty');
        }



//        $this->systemConfigService->set('core.basicInformation.email', $senderEmail);
        $this->systemConfigService->set('core.mailerSettings.transport', 'smtp');
        $this->systemConfigService->set('core.mailerSettings.host', $smtpConfigure['smtpHost']);
        $this->systemConfigService->set('core.mailerSettings.port', $smtpConfigure['smtpPort']);
        $this->systemConfigService->set('core.mailerSettings.username', $smtpConfigure['smtpUsername']);
        $this->systemConfigService->set('core.mailerSettings.password', $smtpConfigure['smtpPassword']);
        $this->systemConfigService->set('core.mailerSettings.encryption', 'ssl');
    }

}
