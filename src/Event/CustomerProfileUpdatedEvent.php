<?php

namespace App\Event;

use App\ValueObject\CustomerId;
use Symfony\Component\EventDispatcher\Event;

class CustomerProfileUpdatedEvent extends Event implements CustomerEventInterface
{
    const NAME = 'customer_profile_updated';

    /** @var CustomerId */
    private $customerId;

    /** @var string */
    private $field;

    private $value;

    public function __construct(CustomerId $customerId, string $field, $value)
    {
        $this->customerId = $customerId;
        $this->field = $field;
        $this->value = $value;
    }

    public function field() : string
    {
        return $this->field;
    }

    public function value()
    {
        return $this->value;
    }

    public function customerId() : CustomerId
    {
        return $this->customerId;
    }

    public function __toString()
    {
        return sprintf(
            'Customer profile updated ID `%d`, field `%s`, data `%s`',
            $this->customerId->id(),
            $this->field,
            $this->value
        );
    }
}