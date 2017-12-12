<?php

namespace App\Subscriber;

use App\Event\CustomerProfileUpdatedEvent;
use App\Event\CustomerRegisteredEvent;
use App\Repository\CustomerCustomRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CustomerCustomProjector implements EventSubscriberInterface
{
    private $repository;

    public function __construct(CustomerCustomRepository $repository)
    {
        $this->repository = $repository;
    }

    public static function getSubscribedEvents()
    {
        return [
            CustomerRegisteredEvent::NAME => 'onCustomerRegistered',
            CustomerProfileUpdatedEvent::NAME => 'onCustomerProfileUpdate',
        ];
    }

    public function onCustomerRegistered(CustomerRegisteredEvent $event) : void
    {
        $this->repository->create(
            $event->customerId(),
            $event->name(),
            $event->email()
        );
    }

    public function onCustomerProfileUpdate(CustomerProfileUpdatedEvent $event) : void
    {
        $this->repository->update(
            $event->customerId(),
            $event->field(),
            $event->value()
        );
    }
}