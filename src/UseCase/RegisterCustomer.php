<?php

namespace App\UseCase;

use App\Entity\Customer;
use App\Event\CustomerRegisteredEvent;
use App\Repository\CustomerCustomRepository;
use App\UseCase\EmailAlreadyExistsException;
use App\ValueObject\CustomerId;
use App\ValueObject\Email;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class RegisterCustomer
{
    private $eventDispatcher;
    private $customerCustomRepo;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        CustomerCustomRepository $customerCustomRepo
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->customerCustomRepo = $customerCustomRepo;
    }

    /**
     * @throws \App\UseCase\EmailAlreadyExistsException
     */
    public function execute(string $name, string $email) : void
    {
        $email = new Email($email);

        if ($this->customerCustomRepo->emailExists($email)) {
            throw new EmailAlreadyExistsException(sprintf(
                'Email `%s` already exists',
                $email->value()
            ));
        }

        $customer = new Customer();

        $event = new CustomerRegisteredEvent(
            CustomerId::generate(),
            $name,
            $email
        );

        $customer->recordThat($event);
        $this->eventDispatcher->dispatch($event::NAME, $event);
    }
}