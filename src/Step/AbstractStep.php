<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 25.09.17
 */

namespace Dopamedia\PhpBatch\Step;

use Dopamedia\PhpBatch\Adapter\EventManagerAdapterInterface;
use Dopamedia\PhpBatch\BatchStatus;
use Dopamedia\PhpBatch\Event\EventInterface;
use Dopamedia\PhpBatch\Event\InvalidItemEvent;
use Dopamedia\PhpBatch\Event\StepExecutionEvent;
use Dopamedia\PhpBatch\ExitStatus;
use Dopamedia\PhpBatch\Item\InvalidItemInterface;
use Dopamedia\PhpBatch\JobInterruptedException;
use Dopamedia\PhpBatch\Launch\Support\ExitCodeMapperInterface;
use Dopamedia\PhpBatch\Repository\JobRepositoryInterface;
use Dopamedia\PhpBatch\StepExecutionInterface;
use Dopamedia\PhpBatch\StepInterface;

/**
 * Class AbstractStep
 * @package Dopamedia\PhpBatch\Step
 */
abstract class AbstractStep implements StepInterface
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
     * AbstractStep constructor.
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
     * @param StepExecutionInterface $stepExecution
     * @throws \Exception
     */
    abstract protected function doExecute(StepExecutionInterface $stepExecution): void;

    /**
     * @inheritDoc
     */
    public function execute(StepExecutionInterface $execution): void
    {
        $this->attachStepExecutionEvent(EventInterface::BEFORE_STEP_EXECUTION, $execution);
        $execution->setStartTime(new \DateTime());
        $execution->setStatus(BatchStatus::STARTED());
        $this->jobRepository->updateStepExecution($execution);

        // Start with a default value that will be trumped by anything
        $exitStatus = new ExitStatus(ExitStatus::EXECUTING);

        try {
            $this->doExecute($execution);
            $exitStatus = new ExitStatus(ExitStatus::COMPLETED);
            $exitStatus->logicalAnd($execution->getExitStatus());
            $this->jobRepository->updateStepExecution($execution);

            // Check if someone is trying to stop us
            if ($execution->isTerminateOnly()) {
                throw new JobInterruptedException('StepExecution interrupted');
            }

            // Need to upgrade here not set, in case the execution was stopped
            $execution->upgradeStatus(BatchStatus::COMPLETED());
            $this->attachStepExecutionEvent(EventInterface::STEP_EXECUTION_SUCCEEDED, $execution);
        } catch (\Throwable $t) {
            $execution->upgradeStatus($this->determineBatchStatus($t));
            $exitStatus = $exitStatus->logicalAnd($this->getDefaultExitStatusForFailure($t));
            $execution->addFailureException($t);
            $this->jobRepository->updateStepExecution($execution);

            if ($execution->getStatus()->getValue() === BatchStatus::STOPPED) {
                $this->attachStepExecutionEvent(EventInterface::STEP_EXECUTION_INTERRUPTED, $execution);
            } else {
                $this->attachStepExecutionEvent(EventInterface::STEP_EXECUTION_ERRORED, $execution);
            }
        }

        $this->attachStepExecutionEvent(EventInterface::STEP_EXECUTION_COMPLETED, $execution);

        $execution->setEndTime(new \DateTime());
        $execution->setExitStatus($exitStatus);
        $this->jobRepository->updateStepExecution($execution);
    }

    /**
     * @param \Throwable $t
     * @return BatchStatus
     */
    private static function determineBatchStatus(\Throwable $t): BatchStatus
    {
        if ($t instanceof JobInterruptedException || $t->getPrevious() instanceof JobInterruptedException) {
            return BatchStatus::STOPPED();
        } else {
            return BatchStatus::FAILED();
        }
    }

    /**
     * @param \Throwable $t
     * @return ExitStatus
     */
    private function getDefaultExitStatusForFailure(\Throwable $t): ExitStatus
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

    /**
     * @param string $eventName
     * @param StepExecutionInterface $stepExecution
     * @return void
     */
    protected function attachStepExecutionEvent(string $eventName, StepExecutionInterface $stepExecution): void
    {
        $event = new StepExecutionEvent($stepExecution);
        $this->eventManagerAdapter->attach($eventName, function() use ($event) {
            return $event;
        });
    }

    /**
     * @param string $class
     * @param string $reason
     * @param array $reasonParameters
     * @param InvalidItemInterface $item
     * @return void
     */
    protected function attachInvalidItemEvent(
        string $class,
        string $reason,
        array $reasonParameters,
        InvalidItemInterface $item
    ): void
    {
        $event = new InvalidItemEvent($item, $class, $reason, $reasonParameters);
        $this->eventManagerAdapter->attach(EventInterface::INVALID_ITEM, function() use ($event) {
            return $event;
        });
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%s: [name=%s]', get_class($this), $this->name);
    }
}