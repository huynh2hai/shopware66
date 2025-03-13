<?php

namespace SwagFreeGiftOnRegistration\Subscriber;

use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Checkout\Customer\Event\CustomerRegisterEvent;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\Uuid\Uuid;
use SwagFreeGiftOnRegistration\Service\EmailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CustomerRegistrationSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private EntityRepository $promotionRepository,
        private EntityRepository $promotionIndividualCodeRepository,
        private EmailService $emailService
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        // Return the events to listen to as array like this:  <event to listen to> => <method to execute>
        return [
            CustomerRegisterEvent::class => ['onCustomerRegister', -99]
        ];
    }

    public function onCustomerRegister(CustomerRegisterEvent $event)
    {
        $context = $event->getContext();
        $customer = $event->getCustomer();
        $code = $this->createIndividualCode($context, $customer);

        $this->emailService->sendRegistrationGiftEmail($customer, $code, $context);
    }

    private function createIndividualCode(Context $context, CustomerEntity $customer): string
    {
        $code = 'REG-' . strtoupper(substr(md5(uniqid()), 0, 8));

        $this->promotionIndividualCodeRepository->create([
            [
                'id' => Uuid::randomHex(),
                'promotionId' => Uuid::fromStringToHex('swag_free_gift_on_registration_promotion'),
                'code' => $code
            ]
        ], $context);

        return $code;
    }

}
