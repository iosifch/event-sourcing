<?php

namespace App\Entity;


use App\Event\CustomerEventInterface;
use App\Event\CustomerProfileUpdatedEvent;
use App\Event\CustomerRegisteredEvent;
use App\ValueObject\CustomerId;
use App\ValueObject\Email;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Customer
{
    /** @var  string */
    private $name;
    /** @var  Email */
    private $email;
    /** @var CustomerEventInterface[] */
    private $events = [];
    /** @var  CustomerId */
    private $id;

    public function name() : string
    {
        return $this->name;
    }

    public function email() : Email
    {
        return $this->email;
    }

    public function id() : CustomerId
    {
        return $this->id;
    }

    public function recordThat($event) : void
    {
        $this->events[] = $event;
        $this->apply($event);
    }

    /** @return CustomerEventInterface[] */
    public function getEvents() : array
    {
        return $this->events;
    }

    public static function regenerateFrom(array $events) : ?self
    {
        if (empty($events)) {
            return null;
        }

        $customer = new self();

        foreach ($events as $event) {
            $customer->recordThat($event);
        }

        return $customer;
    }

    private function apply($event) : void
    {
        $eventName = (new \ReflectionClass($event))->getShortName();
        $method = 'apply' . $eventName;

        $this->$method($event);
    }

    private function applyCustomerRegisteredEvent(CustomerRegisteredEvent $event) : void
    {
        $this->id = $event->customerId();
        $this->name = $event->name();
        $this->email = $event->email();
    }

    private function applyCustomerProfileUpdatedEvent(CustomerProfileUpdatedEvent $event) : void
    {
        $field        = $event->field();
        $this->$field = $event->value();
    }
}