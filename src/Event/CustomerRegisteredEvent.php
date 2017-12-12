<?php

namespace App\Event;

use App\ValueObject\CustomerId;
use App\ValueObject\Email;
use Symfony\Component\EventDispatcher\Event;

final class CustomerRegisteredEvent extends Event implements CustomerEventInterface
{
    const NAME = 'customer_registered';

    protected $customerId;

    protected $name;

    protected $surname;

    protected $email;

    public function __construct(CustomerId $customerId, string $name, Email $email)
    {
        $this->customerId = $customerId;
        $this->email = $email;
        $this->name = $name;
    }

    public function name() : string
    {
        return $this->name;
    }

    public function email() : Email
    {
        return $this->email;
    }

    public function customerId() : CustomerId
    {
        return $this->customerId;
    }

    public function __toString()
    {
        return sprintf(
            'Customer registered with id `%d`, name `%s`, email `%s`',
            $this->customerId->id(),
            $this->name,
            $this->email
        );
    }
}