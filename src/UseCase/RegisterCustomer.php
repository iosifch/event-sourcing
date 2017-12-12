<?php

namespace App\UseCase;

use App\Entity\Customer;
use App\Event\CustomerRegisteredEvent;
use App\ValueObject\CustomerId;
use App\ValueObject\Email;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class RegisterCustomer
{
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function execute(string $name, string $email) : void
    {
        $customer = new Customer();

        $event = new CustomerRegisteredEvent(
            CustomerId::generate(),
            $name,
            new Email($email)
        );

        $customer->recordThat($event);
        $this->eventDispatcher->dispatch($event::NAME, $event);
    }
}