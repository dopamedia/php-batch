<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 03.10.17
 */

namespace Dopamedia\PhpBatch\Console\Command;

use Dopamedia\PhpBatch\Console\Cli;
use Dopamedia\PhpBatch\JobInstanceInterface;
use Dopamedia\PhpBatch\Repository\JobRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableFactory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class JobInstanceListCommand
 * @package Dopamedia\PhpBatch\Console\Command
 */
class JobInstanceListCommand extends Command
{
    /**
     * @var JobRepositoryInterface
     */
    private $jobRepository;

    /**
     * @var TableFactory
     */
    private $tableFactory;

    /**
     * JobInstanceListCommand constructor.
     * @param JobRepositoryInterface $jobRepository
     * @param TableFactory $tableFactory
     */
    public function __construct(
        JobRepositoryInterface $jobRepository,
        TableFactory $tableFactory
    )
    {
        $this->jobRepository = $jobRepository;
        $this->tableFactory = $tableFactory;
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('batch:job-instance:list')
            ->setDescription('List the existing job instances');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (count($this->jobRepository->getJobInstances()) === 0) {
            $output->writeln('<comment>No JobInstances defined</comment>');
            return Cli::RETURN_FAILURE;
        } else {
            $this->buildTable($output)->render();
            return Cli::RETURN_SUCCESS;
        }
    }

    /**
     * @param OutputInterface $output
     * @return Table
     */
    private function buildTable(OutputInterface $output): Table
    {
        $table = $this->tableFactory->create(['output' => $output]);

        $table->setHeaders(['id', 'code', 'job name']);

        /** @var JobInstanceInterface $jobInstance */
        foreach ($this->jobRepository->getJobInstances() as $jobInstance) {
            $table->addRow([
                $jobInstance->getId(),
                $jobInstance->getCode(),
                $jobInstance->getJobName()
            ]);
        }

        return $table;
    }
}