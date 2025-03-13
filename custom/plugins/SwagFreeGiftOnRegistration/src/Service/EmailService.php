<?php

namespace SwagFreeGiftOnRegistration\Service;

use Cassandra\Custom;
use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Content\Mail\Service\MailService;
use Shopware\Core\Content\MailTemplate\MailTemplateEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;

class EmailService
{
    public function __construct(
        private EntityRepository $mailTemplateRepository,
        private MailService $mailService,
    ){
    }

    public function sendRegistrationGiftEmail(CustomerEntity $receiver, string $voucher, Context $context)
    {
        $emailTemplate = $this->getEmailTemplate($context);

        $this->sendVoucherEmail($receiver, $voucher, $context, $emailTemplate);
    }


    private function sendVoucherEmail(CustomerEntity $customer, string $voucherCode, Context $context, MailTemplateEntity $mailTemplate): void
    {
//        $amount = $this->systemConfig->get('FreeGiftOnRegistration.config.voucherAmount') ?? 10;
//        $shopName = $this->systemConfig->get('core.basicInformation.shopName');

        $shopName = 'Test';

        $data = [
            'recipients' => [$customer->getEmail() => $customer->getEmail()],
            'senderName' => $mailTemplate->getSenderName(),
            'subject' => $mailTemplate->getSubject(),
            'contentHtml' => $mailTemplate->getContentHtml(),
            'contentPlain' => $mailTemplate->getContentPlain(),
            'salesChannelId' => '98432def39fc4624b33213a56b8c944d'
        ];

        $this->mailService->send($data, $context, [
            'voucherCode' => $voucherCode,
            'shopName' => 'shopName',
            'amount' => 10,
            'currencySymbol' => '$'
        ]);
    }

    private function getEmailTemplate(Context $context): ?MailTemplateEntity
    {

        $criteria = new Criteria();
        $criteria->addFilter(
            new EqualsFilter('id', Uuid::fromStringToHex('free_gift_on_registration_email_template'))
        );

        return $this->mailTemplateRepository->search($criteria, $context)->first();
    }
}
