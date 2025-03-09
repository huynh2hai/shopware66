<?php

declare(strict_types=1);

namespace SwagHappyBirthdayEmail\Storefront\Controller;

use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use SwagHappyBirthdayEmail\Extension\Content\Customer\BirthdayEmailEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(defaults: ['_routeScope' => ['storefront']])]
class BirthdayEmailController extends StorefrontController
{
    public function __construct(
        private EntityRepository $customerRepository,
        private EntityRepository $swagCustomerBirthdayEmailRepository
    )
    {
    }

    #[Route(path: '/widgets/account/birthday', name: 'frontend.account.birthday', defaults: ['XmlHttpRequest' => true, '_loginRequired' => true], methods: ['POST'])]
    public function subscribeCustomer(
        Request $request,
        RequestDataBag $data,
        SalesChannelContext $context,
        CustomerEntity $customer
    ): Response
    {
        $customer = $context->getCustomer();

        if (!$customer) {
            return $this->redirectToRoute('frontend.account.home.page');
        }

        try {
            /** @var BirthdayEmailEntity $birthdayEmail */
            $birthdayEmail = $customer->getExtension('birthdayEmail');
            if ($birthdayEmail) {
                $this->swagCustomerBirthdayEmailRepository->update([
                    [
                        'id' => $birthdayEmail->getUniqueIdentifier(),
                        'subscribe' => (bool) $data->get('subscribe', false)
                    ]
                ], $context->getContext());
            } else {
                $this->customerRepository->upsert([[
                    'id' => $customer->getUniqueIdentifier(),
                    'birthdayEmail' => [
                        'subscribe' => true
                    ]
                ]], $context->getContext());
            }

            $message = 'You have successfully updated from the birthday email.';

        } catch (\Throwable $exception) {
            $message =$exception->getMessage();
        }

        $isSubscribe = $customer->getExtension('birthdayEmail') ? $customer->getExtension('birthdayEmail')->getSubscribe() : false;

        $this->addFlash('success', 'You have successfully updated from the birthday email.');

        return $this->redirectToRoute('frontend.account.home.page');


        return $this->renderStorefront('@SwagHappyBirthdayEmail/storefront/page/account/birthday.html.twig', [
            'isSubscribe' => !$isSubscribe,
            'context' => $context,
            'message' => $message,
        ]);
    }
}
