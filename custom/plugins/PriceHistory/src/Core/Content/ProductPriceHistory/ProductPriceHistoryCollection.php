<?php declare(strict_types=1);

namespace PriceHistory\Core\Content\ProductPriceHistory;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void add(ProductPriceHistoryEntity $entity)
 * @method void set(string $key, ProductPriceHistoryEntity $entity)
 * @method ProductPriceHistoryEntity[] getIterator()
 * @method ProductPriceHistoryEntity[] getElements()
 * @method ProductPriceHistoryEntity|null get(string $key)
 * @method ProductPriceHistoryEntity|null first()
 * @method ProductPriceHistoryEntity|null last()
 */
class ProductPriceHistoryCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return ProductPriceHistoryEntity::class;
    }
}
