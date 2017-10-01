<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 20.09.17
 */

namespace Dopamedia\PhpBatch;

use Dopamedia\PhpBatch\Item\ExecutionContext;
use Dopamedia\PhpBatch\Item\InvalidItemInterface;
use Dopamedia\PhpBatch\Job\JobParameters;

/**
 * Interface StepExecutionInterface
 * @package Dopamedia\PhpBatch
 */
interface StepExecutionInterface extends EntityInterface
{
    /**
     * @return null|string
     */
    public function getStepName(): ?string;

    /**
     * @param string $stepName
     * @return StepExecutionInterface
     */
    public function setStepName(string $stepName): StepExecutionInterface;

    /**
     * @return JobExecutionInterface|null
     */
    public function getJobExecution(): ?JobExecutionInterface;

    /**
     * @param JobExecutionInterface $jobExecution
     * @return StepExecutionInterface
     */
    public function setJobExecution(JobExecutionInterface $jobExecution): StepExecutionInterface;

    /**
     * @return ExecutionContext
     */
    public function getExecutionContext(): ExecutionContext;

    /**
     * @param ExecutionContext $executionContext
     * @return StepExecutionInterface
     */
    public function setExecutionContext(ExecutionContext $executionContext): StepExecutionInterface;

    /**
     * @return \DateTime|null
     */
    public function getEndTime(): ?\DateTime;

    /**
     * @param \DateTime $endTime
     * @return StepExecutionInterface
     */
    public function setEndTime(\DateTime $endTime): StepExecutionInterface;

    /**
     * @return int|null
     */
    public function getReadCount(): ?int;

    /**
     * @param int $readCount
     * @return StepExecutionInterface
     */
    public function setReadCount(int $readCount): StepExecutionInterface;

    /**
     * @return int|null
     */
    public function getWriteCount(): ?int;

    /**
     * @param int $writeCount
     * @return StepExecutionInterface
     */
    public function setWriteCount(int $writeCount): StepExecutionInterface;

    /**
     * @return int|null
     */
    public function getFilterCount(): ?int;

    /**
     * @param int $filterCount
     * @return StepExecutionInterface
     */
    public function setFilterCount(int $filterCount): StepExecutionInterface;

    /**
     * @return \DateTime|null
     */
    public function getStartTime(): ?\DateTime;

    /**
     * @param \DateTime $startTime
     * @return StepExecutionInterface
     */
    public function setStartTime(\DateTime $startTime): StepExecutionInterface;

    /**
     * @return BatchStatus
     */
    public function getStatus(): BatchStatus;

    /**
     * @param BatchStatus $batchStatus
     * @return StepExecutionInterface
     */
    public function setStatus(BatchStatus $batchStatus): StepExecutionInterface;

    /**
     * @param BatchStatus $batchStatus
     * @return StepExecutionInterface
     */
    public function upgradeStatus(BatchStatus $batchStatus): StepExecutionInterface;

    /**
     * @param ExitStatus $exitStatus
     * @return StepExecutionInterface
     */
    public function setExitStatus(ExitStatus $exitStatus): StepExecutionInterface;

    /**
     * @return ExitStatus
     */
    public function getExitStatus(): ExitStatus;

    /**
     * @return bool
     */
    public function isTerminateOnly(): bool;

    /**
     * @return StepExecutionInterface
     */
    public function setTerminateOnly(): StepExecutionInterface;

    /**
     * @return JobParameters
     */
    public function getJobParameters(): JobParameters;

    /**
     * @return array|\Throwable[]
     */
    public function getFailureExceptions(): array;

    /**
     * @param \Throwable $throwable
     * @return StepExecutionInterface
     */
    public function addFailureException(\Throwable $throwable): StepExecutionInterface;

    /**
     * @return array|string[]
     */
    public function getErrors(): array;

    /**
     * @param string $message
     * @return StepExecutionInterface
     */
    public function addError(string $message): StepExecutionInterface;

    /**
     * @param string $reason
     * @param array $reasonParameters
     * @param InvalidItemInterface $item
     * @return StepExecutionInterface
     */
    public function addWarning(
        string $reason,
        array $reasonParameters,
        InvalidItemInterface $item
    ): StepExecutionInterface;

    /**
     * @return array|WarningInterface
     */
    public function getWarnings(): array;

    /**
     * @return array
     */
    public function getSummary(): array;

    /**
     * @param array $summary
     * @return StepExecutionInterface
     */
    public function setSummary(array $summary): StepExecutionInterface;

    /**
     * @param string $key
     * @return mixed
     */
    public function getSummaryInfo(string $key);

    /**
     * @param string $key
     * @param mixed $info
     * @return StepExecutionInterface
     */
    public function addSummaryInfo(string $key, $info): StepExecutionInterface;

    /**
     * @param string $key
     * @param int $increment
     * @return StepExecutionInterface
     */
    public function incrementSummaryInfo(string $key, int $increment = 1): StepExecutionInterface;
}