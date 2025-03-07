<?php

namespace SwagHappyBirthdayEmail\Extension\Content\Customer;

use Shopware\Core\Checkout\Customer\CustomerDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class BirthdayEmailExtension extends EntityExtension
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            new OneToOneAssociationField('birthdayEmail', 'id', 'customer_id', BirthdayExtensionDefinition::class, true)
        );
    }

    public function getDefinitionClass(): string
    {
        return CustomerDefinition::class;
    }

}
