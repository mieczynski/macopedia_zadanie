<?php

namespace App\Command;

use App\Service\UploadFileToDatabaseService;
use Doctrine\ORM\Exception\ORMException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\ParameterBag;

#[AsCommand(name: 'app:import-csv')]
class UploadFilesToDatabaseCommand extends Command
{


    public function __construct(
        private readonly UploadFileToDatabaseService $uploadFileToDatabaseService,
    )
    {
        parent::__construct();
    }


    /**
     * @throws ORMException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($count = count($this->uploadFileToDatabaseService->upload()))
            $output->writeln('Successfully uploaded ' . $count . ' files');
        else
            $output->writeln('There is nothing to upload');

        return Command::SUCCESS;
    }

}