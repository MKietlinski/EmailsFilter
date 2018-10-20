<?php

namespace App\Command;

use App\Service\FilterEmailsService;
use App\Service\FilterFile\FilterCsv;
use App\Service\GenerateFile\GenerateCsv;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FilterCsvEmailsCommand extends Command
{
    protected function configure()
    {
        $this->setName('filter:csv')
            ->setAliases(['f:csv'])
            ->setDescription('Filters email addresses from CSV file')
            ->setHelp('This command takes CSV file and makes 3 new files - with valid emails, invalid emails, and filtering summary')
            ->addArgument('filename', InputArgument::REQUIRED, 'CSV Filename');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $filterService = new FilterEmailsService(
                new FilterCsv($input->getArgument('filename')),
                new GenerateCsv()
            );
            $filterService->filter();

            $io->success('Addresses filtered');
        } catch (\Exception $exception) {
            $io->warning($exception->getMessage());
        }
    }
}