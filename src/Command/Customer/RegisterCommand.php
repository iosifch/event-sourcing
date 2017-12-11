<?php

namespace App\Command\Customer;

use App\UseCase\RegisterCustomer as RegisterCustomerUseCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RegisterCommand extends Command
{
    private $registerCustomerUseCase;

    public function __construct(
        string $name = null,
        RegisterCustomerUseCase $registerCustomerUseCase
    ) {
        parent::__construct($name);
        $this->registerCustomerUseCase = $registerCustomerUseCase;
    }

    protected function configure()
    {
        $this->setName('customer:register')
            ->addArgument('name', InputArgument::REQUIRED)
            ->addArgument('email', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->registerCustomerUseCase->execute(
            $input->getArgument('name'),
            $input->getArgument('email')
        );
    }
}