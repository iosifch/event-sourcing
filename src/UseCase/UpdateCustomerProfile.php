<?php

namespace App\UseCase;

use App\Entity\Customer;
use App\Event\CustomerProfileUpdatedEvent;
use App\Repository\CustomerCustomRepository;
use App\ValueObject\CustomerId;
use App\ValueObject\Email;
use App\EventStore\CustomerEventStore;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class UpdateCustomerProfile
{
    private $eventDispatcher;
    private $customerEventStore;
    private $customerCustomRepo;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        CustomerEventStore $customerEventStore,
        CustomerCustomRepository $customerCustomRepo
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->customerEventStore = $customerEventStore;
        $this->customerCustomRepo = $customerCustomRepo;
    }

    /**
     * @throws EmailAlreadyExistsException
     * @throws CustomerNotFoundException
     * @throws \App\ValueObject\InvalidEmailException
     */
    public function execute(int $customerId, string $field, string $value) : void
    {
        if ('email' === $field) {
            $value = new Email($value);

            $this->throwExceptionIfEmailExists($value);
        }

        $customer = Customer::regenerateFrom(
            $this->customerEventStore->fetchAllById(
                CustomerId::createFromInteger($customerId)
            )
        );

        if (!($customer instanceof Customer)) {
            throw new CustomerNotFoundException(sprintf(
                'No customer found for id %d',
                $customerId
            ));
        }

        $event = new CustomerProfileUpdatedEvent(
            $customer->id(),
            $field,
            $value
        );

        $customer->recordThat($event);

        $this->eventDispatcher->dispatch($event::NAME, $event);
    }

    /**
     * @throws \App\UseCase\EmailAlreadyExistsException
     */
    private function throwExceptionIfEmailExists(Email $email) : void
    {
        if ($this->customerCustomRepo->emailExists($email)) {
            throw new EmailAlreadyExistsException(sprintf(
                'Email `%s` is already in use by another customer',
                $email
            ));
        }
    }
}