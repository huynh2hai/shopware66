<?php declare(strict_types=1);

namespace FreeProductOnRegistration\Subscriber;

use FreeProductOnRegistration\Service\EmailService;
use Shopware\Core\Checkout\Customer\Event\CustomerRegisterEvent;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SystemConfig\Event\SystemConfigMultipleChangedEvent;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Core\System\Tag\TagCollection;
use Shopware\Core\System\Tag\TagEntity;
use Shopware\Storefront\Pagelet\Footer\FooterPageletLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MySubscriber implements EventSubscriberInterface
{
    public function __construct(
        private EntityRepository $tagRepository,
        private EntityRepository $customerRepository,
        private EntityRepository $productCategoryRepository,
        private EmailService $emailService,
        private SystemConfigService $systemConfigService
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CustomerRegisterEvent::class => 'onCustomerRegister',
            SystemConfigMultipleChangedEvent::class => 'onSystemConfigMultipleChanged',
            FooterPageletLoadedEvent::class => 'onProductPage',
        ];
    }

    public function onSystemConfigMultipleChanged(SystemConfigMultipleChangedEvent $event): void
    {
        $context = Context::createDefaultContext();
        $config = $event->getConfig();
        $productsIds = $config['FreeProductOnRegistration.config.freeProductIds'];

        $this->removeProductsFromCategory($context);

        if (!empty($productsIds)) {
            $this->addProductsToCategory($productsIds, $context);
        }
    }

    public function onCustomerRegister(CustomerRegisterEvent $event)
    {
        $context = $event->getContext();
        $salesChannel = $event->getSalesChannelContext()->getSalesChannel();
        $customer = $event->getCustomer();

        $tag = $this->getTag('new_customer', $context);
        $customer->setTags(new TagCollection([$tag]));

        $this->customerRepository->update([[
            'id' => $customer->getUniqueIdentifier(),
            'tags' => [
                ['id' => $tag->getUniqueIdentifier()]
            ]
        ]], $context);

        $this->emailService->sendRegistrationGiftEmail($customer, $salesChannel, $context);
    }

    private function getTag(string $tagName, Context $context): TagEntity
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', $tagName));

        return $this->tagRepository->search($criteria, $context)->first();
    }

    private function removeProductsFromCategory(Context $context): void
    {
        $criteria = new Criteria();
        $criteria->addFilter(
            new EqualsFilter('categoryId', Uuid::fromStringToHex('free_product_new_customer_category_id'))
        );

        $productCategoryIds = $this->productCategoryRepository->searchIds($criteria, $context)->getIds();

        if (!empty($productCategoryIds)) {
            $this->productCategoryRepository->delete($productCategoryIds, $context);
        }
    }

    public function addProductsToCategory(array $productIds, Context $context): void
    {
        $categoryId = Uuid::fromStringToHex('free_product_new_customer_category_id');

        $productCategoryData = array_map(function ($productId) use ($categoryId) {
            return [
                'productId' => $productId,
                'categoryId' => $categoryId,
            ];
        }, $productIds);

        $this->productCategoryRepository->create($productCategoryData, $context);
    }

    public function onProductPage(FooterPageletLoadedEvent $event): void
    {
        $productIds = $this->systemConfigService->get('FreeProductOnRegistration.config');

        $event->getPagelet()->assign([
            'free_product_ids' => $productIds['freeProductIds'] ?? []
        ]);
    }
}
