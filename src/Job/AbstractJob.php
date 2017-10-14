<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 20.09.17
 */

namespace Dopamedia\PhpBatch\Job;

use Dopamedia\PhpBatch\Adapter\EventManagerAdapterInterface;
use Dopamedia\PhpBatch\BatchStatus;
use Dopamedia\PhpBatch\Event\EventInterface;
use Dopamedia\PhpBatch\Event\JobExecutionEvent;
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
     * @var EventManagerAdapterInterface
     */
    private $eventManagerAdapter;

    /**
     * @var JobRepositoryInterface
     */
    protected $jobRepository;

    /**
     * AbstractJob constructor.
     * @param string $name
     * @param EventManagerAdapterInterface $eventManagerAdapter
     * @param JobRepositoryInterface $jobRepository
     */
    public function __construct(
        string $name,
        EventManagerAdapterInterface $eventManagerAdapter,
        JobRepositoryInterface $jobRepository
    )
    {
        $this->name = $name;
        $this->eventManagerAdapter = $eventManagerAdapter;
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
        try {
            $this->attachJobExecutionEvent(EventInterface::BEFORE_JOB_EXECUTION, $execution);

            if ($execution->getStatus()->getValue() !== BatchStatus::STOPPING) {
                $execution->setStartTime(new \DateTime());
                $this->updateStatus($execution, BatchStatus::STARTED());
                $this->jobRepository->saveJobExecution($execution);
                $this->doExecute($execution);
            } else {
                $execution->setStatus(BatchStatus::STOPPED());
                $execution->setExitStatus(new ExitStatus(ExitStatus::COMPLETED));
                $this->jobRepository->saveJobExecution($execution);
                $this->attachJobExecutionEvent(EventInterface::JOB_EXECUTION_STOPPED, $execution);
            }

            if ($execution->getStatus()->isLessThanOrEqualTo(BatchStatus::STOPPED())
                && (count($execution->getStepExecutions()) === 0)
            ) {
                $exitStatus = $execution->getExitStatus();
                $noopExitStatus = new ExitStatus(ExitStatus::NOOP);
                $noopExitStatus->addExitDescription("All steps already completed or no steps configured for this job.");
                $execution->setExitStatus($exitStatus->logicalAnd($noopExitStatus));
                $this->jobRepository->saveJobExecution($execution);
            }

            $this->attachJobExecutionEvent(EventInterface::AFTER_JOB_EXECUTION, $execution);
            $execution->setEndTime(new \DateTime());
            $this->jobRepository->saveJobExecution($execution);
        } catch (JobInterruptedException $e) {
            $execution->setExitStatus($this->getDefaultExitStatusForFailure($e));
            $execution->setStatus(BatchStatus::max(BatchStatus::STOPPED(), $e->getStatus()));
            $execution->addFailureException($e);
            $this->jobRepository->saveJobExecution($execution);
        } catch (\Throwable $t) {
            $execution->setExitStatus($this->getDefaultExitStatusForFailure($t));
            $execution->setStatus(BatchStatus::FAILED());
            $execution->addFailureException($t);
            $this->jobRepository->saveJobExecution($execution);
            $this->attachJobExecutionEvent(EventInterface::JOB_EXECUTION_FATAL_ERROR, $execution);
        }
    }

    /**
     * @param JobExecutionInterface $execution
     * @param BatchStatus $status
     * @throws \Exception
     */
    private function updateStatus(JobExecutionInterface $execution, BatchStatus $status): void
    {
        $execution->setStatus($status);
        $this->jobRepository->saveJobExecution($execution);
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
     * @throws \Exception
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
            $this->jobRepository->saveStepExecution($stepExecution);
            throw $e;
        }

        if (
            $stepExecution->getStatus()->equals(BatchStatus::STOPPING())
            || $stepExecution->getStatus()->equals(BatchStatus::STOPPED())
        ) {
            $this->updateStatus($execution, BatchStatus::STOPPING());
            $this->jobRepository->saveStepExecution($stepExecution);
            throw new JobInterruptedException('Job interrupted by step execution');
        }

        return $stepExecution;
    }

    /**
     * @param string $eventName
     * @param JobExecutionInterface $jobExecution
     * @return void
     */
    protected function attachJobExecutionEvent(string $eventName, JobExecutionInterface $jobExecution): void
    {
        $event = new JobExecutionEvent($jobExecution);
        $this->eventManagerAdapter->attach($eventName, function() use ($event) {
            return $event;
        });
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