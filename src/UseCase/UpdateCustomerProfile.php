<?php

namespace App\UseCase;

use App\Entity\Customer;
use App\Event\CustomerProfileUpdatedEvent;
use App\ValueObject\CustomerId;
use App\ValueObject\Email;
use App\EventStore\CustomerEventStore;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class UpdateCustomerProfile
{
    private $eventDispatcher;
    private $customerEventStore;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        CustomerEventStore $customerEventStore
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->customerEventStore = $customerEventStore;
    }

    public function execute(int $customerId, string $field, string $value) : void
    {
        $customer = Customer::regenerateFrom(
            $this->customerEventStore->fetchAllById(
                CustomerId::createFromInteger($customerId)
            )
        );

        if (!($customer instanceof Customer)) {
            throw new \Exception(sprintf(
                'No customer found for id %d',
                $customerId
            ));
        }

        if ('email' === $field) {
            $value = new Email($value);
        }

        $event = new CustomerProfileUpdatedEvent(
            $customer->id(),
            $field,
            $value
        );

        $customer->recordThat($event);

        $this->eventDispatcher->dispatch($event::NAME, $event);
    }
}