<?php

namespace FreeProductOnRegistration\Service;

use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Content\Mail\Service\MailService;
use Shopware\Core\Content\MailTemplate\MailTemplateEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SalesChannel\SalesChannelEntity;

class EmailService
{
    public const EMAIL_TEMPLATE_NAME = 'free_product_on_registration_email_template';

    public function __construct(
        private EntityRepository $mailTemplateRepository,
        private MailService $mailService,
    ){
    }

    public function sendRegistrationGiftEmail(
        CustomerEntity $receiver,
        SalesChannelEntity $salesChannel,
        Context $context
    ) {
        $emailTemplate = $this->getEmailTemplate($context);

        $this->sendVoucherEmail($receiver, $context, $emailTemplate, $salesChannel);
    }


    private function sendVoucherEmail(CustomerEntity $customer, Context $context, MailTemplateEntity $mailTemplate, SalesChannelEntity $salesChannel): void
    {
        $data = [
            'recipients' => [$customer->getEmail() => $customer->getEmail()],
            'senderName' => $mailTemplate->getSenderName(),
            'subject' => $mailTemplate->getSubject(),
            'contentHtml' => $mailTemplate->getContentHtml(),
            'contentPlain' => $mailTemplate->getContentPlain(),
            'salesChannelId' => $salesChannel->getUniqueIdentifier(),
        ];

        $this->mailService->send($data, $context, [
            'shopName' => $salesChannel->getName(),
        ]);
    }

    private function getEmailTemplate(Context $context): ?MailTemplateEntity
    {

        $criteria = new Criteria();
        $criteria->addFilter(
            new EqualsFilter('id', Uuid::fromStringToHex(self::EMAIL_TEMPLATE_NAME))
        );

        return $this->mailTemplateRepository->search($criteria, $context)->first();
    }
}
