<?php

namespace SwagHappyBirthdayEmail\Extension\Content\Customer;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class BirthdayEmailEntity extends Entity
{
    use EntityIdTrait;

    protected ?bool $subscribe = null;

    public function getSubscribe(): ?bool
    {
        return $this->subscribe;
    }

    public function setSubscribe(?bool $subscribe): void
    {
        $this->subscribe = $subscribe;
    }
}
