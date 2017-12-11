<?php

namespace App\Command\Customer;

use App\UseCase\UpdateCustomerProfile as UpdateCustomerProfileUseCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateProfileCommand extends Command
{
    private $updateCustomerProfileUseCase;

    public function __construct(
        string $name = null,
        UpdateCustomerProfileUseCase $updateCustomerProfileUseCase
    ) {
        parent::__construct($name);
        $this->updateCustomerProfileUseCase = $updateCustomerProfileUseCase;
    }

    protected function configure()
    {
        $this->setName('customer:update-profile')
            ->addArgument('id', InputArgument::REQUIRED)
            ->addArgument('field', InputArgument::REQUIRED)
            ->addArgument('value', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->updateCustomerProfileUseCase->execute(
            (int)$input->getArgument('id'),
            $input->getArgument('field'),
            $input->getArgument('value')
        );
    }
}