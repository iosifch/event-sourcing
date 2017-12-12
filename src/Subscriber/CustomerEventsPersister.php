<?php

namespace App\Subscriber;

use App\Event\CustomerProfileUpdatedEvent;
use App\Event\CustomerRegisteredEvent;
use App\EventStore\CustomerEventStore;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class CustomerEventsPersister implements EventSubscriberInterface
{
    private $customerEventStore;

    public function __construct(CustomerEventStore $customerEventStore)
    {
        $this->customerEventStore = $customerEventStore;
    }

    public static function getSubscribedEvents() : array
    {
        return [
            CustomerRegisteredEvent::NAME => 'onCustomerRegistered',
            CustomerProfileUpdatedEvent::NAME => 'onCustomerProfileUpdated'
        ];
    }

    public function onCustomerRegistered(CustomerRegisteredEvent $event) : void
    {
        $this->customerEventStore->insert($event);

        printf(
            "A new customer registered: %d %s %s" . PHP_EOL,
            $event->customerId()->value(),
            $event->name(),
            $event->email()->value()
        );
    }

    public function onCustomerProfileUpdated(CustomerProfileUpdatedEvent $event) : void
    {
        $this->customerEventStore->insert($event);

        printf(
            "Customer profile updated %d %s %s" . PHP_EOL,
            $event->customerId()->value(),
            $event->field(),
            $event->value()
        );
    }
}