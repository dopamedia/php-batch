<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 20.09.17
 */

namespace Dopamedia\PhpBatch\Job;

use Dopamedia\PhpBatch\BatchStatus;
use Dopamedia\PhpBatch\ExitStatus;
use Dopamedia\PhpBatch\JobExecutionException;
use Dopamedia\PhpBatch\JobExecutionInterface;
use Dopamedia\PhpBatch\JobInterface;
use Dopamedia\PhpBatch\JobInterruptedException;
use Dopamedia\PhpBatch\Launch\Support\ExitCodeMapperInterface;
use Dopamedia\PhpBatch\Repository\JobRepositoryInterface;
use Dopamedia\PhpBatch\Step\NoSuchStepException;
use Dopamedia\PhpBatch\StepExecutionInterface;
use Dopamedia\PhpBatch\StepInterface;

/**
 * Class AbstractJob
 * @package Dopamedia\PhpBatch\Job
 */
abstract class AbstractJob implements JobInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var JobRepositoryInterface
     */
    private $jobRepository;

    /**
     * AbstractJob constructor.
     * @param string $name
     * @param JobRepositoryInterface $jobRepository
     */
    public function __construct(
        string $name,
        JobRepositoryInterface $jobRepository
    )
    {
        $this->name = $name;
        $this->jobRepository = $jobRepository;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function execute(JobExecutionInterface $execution): void
    {
        // TODO::log

        try {
            // TODO::validate the job parameters

            if ($execution->getStatus()->getValue() !== BatchStatus::STOPPING) {
                $execution->setStartTime(new \DateTime());
                $this->updateStatus($execution, BatchStatus::STARTED());

                // TODO::attach event

                $this->doExecute($execution);
            } else {
                $execution->setStatus(BatchStatus::STOPPED());
                $execution->setExitStatus(new ExitStatus(ExitStatus::COMPLETED));
            }

        } catch (JobInterruptedException $e) {
            // TODO::log
            $execution->setExitStatus($this->getDefaultExitStatusForFailure($e));
            $execution->setStatus(BatchStatus::max(BatchStatus::STOPPED(), $e->getStatus()));
            $execution->addFailureException($e);
        } catch (\Throwable $t) {
            // TODO::log
            $execution->setExitStatus($this->getDefaultExitStatusForFailure($t));
            $execution->setStatus(BatchStatus::FAILED());
            $execution->addFailureException($t);
        } finally {
            if (
                $execution->getStatus()->isLessThanOrEqualTo(BatchStatus::STOPPED())
                && empty($execution->getStepExecutions())
            ) {
                $exitStatus = $execution->getExitStatus();
                $newExitStatus = new ExitStatus(ExitStatus::NOOP);
                $newExitStatus->addExitDescription('All steps already completed or no steps configured for this job.');
                $execution->setExitStatus($exitStatus->logicalAnd($newExitStatus));
            }

            $execution->setEndTime(new \DateTime());

            // TODO::attach event

            $this->jobRepository->updateJobExecution($execution);
        }
    }

    /**
     * @param JobExecutionInterface $execution
     * @param BatchStatus $status
     * @return void
     */
    private function updateStatus(JobExecutionInterface $execution, BatchStatus $status): void
    {
        $execution->setStatus($status);
        $this->jobRepository->updateJobExecution($execution);
    }

    /**
     * @param JobExecutionInterface $execution
     * @throws JobExecutionException
     */
    abstract protected function doExecute(JobExecutionInterface $execution): void;

    /**
     * @param StepInterface $step
     * @param JobExecutionInterface $execution
     * @return StepExecutionInterface
     * @throws JobInterruptedException
     */
    protected final function handleStep(StepInterface $step, JobExecutionInterface $execution): StepExecutionInterface
    {
        if ($execution->isStopping()) {
            throw new JobInterruptedException('JobExecution interrupted.');
        }

        $stepExecution = $execution->createStepExecution($step->getName());

        try {
            $step->execute($stepExecution);
        } catch (JobInterruptedException $e) {
            $this->updateStatus($execution, BatchStatus::STOPPING());
            throw $e;
        }

        if (
            $stepExecution->getStatus()->getValue() === BatchStatus::STOPPING
            || $stepExecution->getStatus()->getValue() === BatchStatus::STOPPED
        ) {
            $this->updateStatus($execution, BatchStatus::STOPPING());
            throw new JobInterruptedException('Job interrupted by step execution');
        }

        return $stepExecution;
    }

    /**
     * @param \Throwable $t
     * @return ExitStatus
     */
    protected function getDefaultExitStatusForFailure(\Throwable $t): ExitStatus
    {
        if ($t instanceof JobInterruptedException || $t->getPrevious() instanceof JobInterruptedException) {
            $exitStatus = new ExitStatus(ExitStatus::STOPPED);
            $exitStatus->addExitDescription(get_class($t));
        } elseif ($t instanceof NoSuchStepException || $t->getPrevious() instanceof NoSuchStepException) {
            $exitStatus = new ExitStatus(ExitCodeMapperInterface::NO_SUCH_JOB);
            $exitStatus->addExitDescription(get_class($t));
        } else {
            $exitStatus = new ExitStatus(ExitStatus::FAILED);
            $exitStatus->addExitDescription($t);
        }

        return $exitStatus;
    }

}