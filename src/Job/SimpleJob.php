<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 25.09.17
 */

namespace Dopamedia\PhpBatch\Job;

use Dopamedia\PhpBatch\BatchStatus;
use Dopamedia\PhpBatch\JobExecutionInterface;
use Dopamedia\PhpBatch\Repository\JobRepositoryInterface;
use Dopamedia\PhpBatch\StepInterface;

/**
 * Class SimpleJob
 * @package Dopamedia\PhpBatch\Job
 */
class SimpleJob extends AbstractJob
{
    /**
     * @var StepInterface[]
     */
    private $steps;

    /**
     * SimpleJob constructor.
     * @param string $name
     * @param JobRepositoryInterface $jobRepository
     * @param StepInterface[] $steps
     */
    public function __construct(
        string $name,
        JobRepositoryInterface $jobRepository,
        array $steps = []
    ) {
        parent::__construct($name, $jobRepository);
        $this->steps = $steps;
    }

    /**
     * @inheritDoc
     */
    public function getStepNames(): array
    {
        $names = [];

        /** @var StepInterface $step */
        foreach ($this->steps as $step) {
            $names[] = $step->getName();
        }

        return $names;
    }

    /**
     * @inheritDoc
     */
    public function getStep(string $stepName): ?StepInterface
    {
        /** @var StepInterface $step */
        foreach ($this->steps as $step) {
            if ($step->getName() === $stepName) {
                return $step;
            }
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    protected function doExecute(JobExecutionInterface $execution): void
    {
        $stepExecution = null;

        /** @var StepInterface $step */
        foreach ($this->steps as $step) {
            $stepExecution = $this->handleStep($step, $execution);

            if ($stepExecution->getStatus()->getValue() !== BatchStatus::COMPLETED) {
                // Terminate the job if a step fails
                break;
            }
        }

        // update the job status to be the same as the last step
        if ($stepExecution !== null) {
            // TODO::log
            $execution->upgradeStatus($stepExecution->getStatus());
            $execution->setExitStatus($stepExecution->getExitStatus());
        }
    }
}