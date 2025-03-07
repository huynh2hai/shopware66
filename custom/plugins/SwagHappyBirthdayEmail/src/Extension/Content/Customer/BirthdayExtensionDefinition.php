<?php

namespace SwagHappyBirthdayEmail\Extension\Content\Customer;

use Shopware\Core\Checkout\Customer\CustomerDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class BirthdayExtensionDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'swag_customer_birthday_email';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return BirthdayEmailEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            new FkField('customer_id', 'customerId', CustomerDefinition::class),
            (new BoolField('subscribe', 'subscribe')),
            new OneToOneAssociationField('customer', 'customer_id', 'id', CustomerDefinition::class, false)
        ]);
    }
}
