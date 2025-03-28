<?php declare(strict_types=1);

namespace PriceHistory\Subscriber;

use PriceHistory\Core\Content\ProductPriceHistory\ProductPriceHistoryEntity;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Content\Product\SalesChannel\SalesChannelProductEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Storefront\Page\Product\ProductPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Shopware\Core\Content\Product\ProductEvents;

class MySubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly EntityRepository $productPriceHistoryRepository
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ProductEvents::PRODUCT_WRITTEN_EVENT => 'onProductPriceWritten',
            ProductPageLoadedEvent::class => 'onProductPage',
        ];
    }

    public function onProductPriceWritten(EntityWrittenEvent $event)
    {
        $context = $event->getContext();

        foreach ($event->getWriteResults() as $writeResult) {
            $payload = $writeResult->getPayload();

            // Only track if price was changed
            if (isset($payload['price'])) {
                $product = $this->getProductFromPayload($payload, $writeResult->getPrimaryKey());

                $this->trackPriceChange($product, $context);
            }
        }
    }

    private function trackPriceChange(ProductEntity $product, Context $context)
    {
        $this->productPriceHistoryRepository->create([
            [
                'productId' => $product->getUniqueIdentifier(),
                'price' => $product->getPrice()->first()->getGross()
            ]
        ], $context);
    }

    private function getProductFromPayload(array $payload, string $id): ProductEntity
    {
        $product = new ProductEntity();
        $product->setId($id);
        $product->setVersionId($payload['versionId'] ?? '0xFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF');
        $product->setPrice($payload['price']);

        return $product;
    }

    public function onProductPage(ProductPageLoadedEvent $event)
    {
        /** @var SalesChannelProductEntity $product */
        $product = $event->getPage()->getProduct();
        $context = $event->getContext();

        $productId = $product->getParentId() ?: $product->getUniqueIdentifier();

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('productId', $productId));
        $criteria->addSorting(new FieldSorting('createdAt', FieldSorting::DESCENDING));

        $results = $this->productPriceHistoryRepository->search($criteria, $context)->getElements();


        $prices = array_values(array_map(fn(ProductPriceHistoryEntity $productPriceHistory) => [
            'price' => $productPriceHistory->getPrice(),
            'date' => $productPriceHistory->getCreatedAt()->format('Y-m-d')
        ], $results));

        $event->getPage()->assign(['prices' => $prices]);
    }
}
