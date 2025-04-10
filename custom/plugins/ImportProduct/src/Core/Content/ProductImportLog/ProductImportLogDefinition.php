<?php declare(strict_types=1);

namespace ImportProduct\Core\Content\ProductImportLog;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\DateTimeField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\JsonField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;

class ProductImportLogDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'product_import_log';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return ProductImportLogEntity::class;
    }

    public function getCollectionClass(): string
    {
        return ProductImportLogCollection::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            (new StringField('file_name', 'fileName'))->addFlags(new Required()),
            (new StringField('status', 'status'))->addFlags(new Required()),
            new StringField('error_message', 'errorMessage'),
            (new IntField('total_records', 'totalRecords'))->addFlags(new Required()),
            (new IntField('success_records', 'successRecords'))->addFlags(new Required()),
            (new IntField('failed_records', 'failedRecords'))->addFlags(new Required()),
            new JsonField('import_details', 'importDetails'),
            new DateTimeField('created_at', 'createdAt'),
            new DateTimeField('updated_at', 'updatedAt')
        ]);
    }
}
