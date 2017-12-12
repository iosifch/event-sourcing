<?php

namespace App\Subscriber;

use App\Event\CustomerRegisteredEvent;
use App\EventStore\CustomerEventStore;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class CustomerRegisteredSubscriber implements EventSubscriberInterface
{
    private $customerEventStore;

    public function __construct(CustomerEventStore $customerEventStore)
    {
        $this->customerEventStore = $customerEventStore;
    }

    public static function getSubscribedEvents() : array
    {
        return [
            CustomerRegisteredEvent::NAME => 'onCustomerRegistered'
        ];
    }

    public function onCustomerRegistered(CustomerRegisteredEvent $event) : void
    {
        $this->customerEventStore->insert($event);

        printf(
            "A new customer registered: %d %s %s" . PHP_EOL,
            $event->customerId()->id(),
            $event->name(),
            $event->email()->value()
        );
    }
}