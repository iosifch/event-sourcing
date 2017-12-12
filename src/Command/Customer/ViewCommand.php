<?php

namespace App\Command\Customer;

use App\Entity\Customer;
use App\ValueObject\CustomerId;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\EventStore\CustomerEventStore;

class ViewCommand extends Command
{
    private $em;
    private $customerEventStore;

    public function __construct(
        string $name = null,
        EntityManagerInterface $em,
        CustomerEventStore $customerEventStore
    ) {
        parent::__construct($name);

        $this->em = $em;
        $this->customerEventStore = $customerEventStore;
    }

    public function configure()
    {
        $this->setName('customer:view')
            ->addArgument('id', InputArgument::REQUIRED);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $customer = Customer::regenerateFrom(
            $this->customerEventStore->fetchAllById(
                CustomerId::createFromInteger($input->getArgument('id'))
            )
        );

        $output->writeln(sprintf(
            'Customer with id `%d`, name `%s`, email `%s`',
            $customer->id()->value(),
            $customer->name(),
            $customer->email()
        ));
    }
}