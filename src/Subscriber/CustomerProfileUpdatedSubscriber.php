<?php

namespace App\Subscriber;

use App\Event\CustomerProfileUpdatedEvent;
use App\EventStore\CustomerEventStore;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CustomerProfileUpdatedSubscriber implements EventSubscriberInterface
{
    private $customerEventStore;

    public function __construct(CustomerEventStore $customerEventStore) {
        $this->customerEventStore = $customerEventStore;
    }

    public static function getSubscribedEvents() : array
    {
        return [
            CustomerProfileUpdatedEvent::NAME => 'onCustomerRegistered'
        ];
    }

    public function onCustomerRegistered(CustomerProfileUpdatedEvent $event) : void
    {
        $this->customerEventStore->insert($event);

        printf(
            "Customer profile updated %d %s %s" . PHP_EOL,
            $event->customerId()->id(),
            $event->field(),
            $event->value()
        );
    }
}