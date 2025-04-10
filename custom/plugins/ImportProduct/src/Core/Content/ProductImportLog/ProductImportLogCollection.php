<?php declare(strict_types=1);

namespace ImportProduct\Core\Content\ProductImportLog;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void add(ProductImportLogEntity $entity)
 * @method void set(string $key, ProductImportLogEntity $entity)
 * @method ProductImportLogEntity[] getIterator()
 * @method ProductImportLogEntity[] getElements()
 * @method ProductImportLogEntity|null get(string $key)
 * @method ProductImportLogEntity|null first()
 * @method ProductImportLogEntity|null last()
 */
class ProductImportLogCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return ProductImportLogEntity::class;
    }
}
