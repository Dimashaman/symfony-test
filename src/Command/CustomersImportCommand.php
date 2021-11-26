<?php

namespace App\Command;

use App\Service\CustomerImporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:customers-import',
    description: 'Imports customers from 3rd party api',
)]
class CustomersImportCommand extends Command
{
    private CustomerImporter $customerImporter;

    public function __construct(CustomerImporter $customerImporter)
    {
        parent::__construct();
        $this->customerImporter = $customerImporter;
    }


    protected function configure(): void
    {
        $this->addOption('limit', null, InputOption::VALUE_REQUIRED, 'User password', '150');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $limit = $input->getOption('limit');
        $stats = $this->customerImporter->import($limit);

        $io = new SymfonyStyle($input, $output);
        if ($stats->invalid == 0) {
            $io->success($stats->new . " items have been added.");
        } else {
            $io->error($stats->invalid . " invalid customers were rejected, ". $stats->new . " items have been added.");
        }

        return Command::SUCCESS;
    }
}
