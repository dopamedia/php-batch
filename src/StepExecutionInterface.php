<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 20.09.17
 */

namespace Dopamedia\PhpBatch;

use Dopamedia\PhpBatch\Item\ExecutionContext;

/**
 * Interface StepExecutionInterface
 * @package Dopamedia\PhpBatch
 */
interface StepExecutionInterface extends EntityInterface
{
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
     * @return \DateTime
     */
    public function getEndTime(): \DateTime;

    /**
     * @param \DateTime $endTime
     * @return void
     */
    public function setEndTime(\DateTime $endTime): void;

    /**
     * @return int
     */
    public function getReadCount(): int;

    /**
     * @param int $readCount
     * @return void
     */
    public function setReadCount(int $readCount): void;

    /**
     * @return int
     */
    public function getWriteCount(): int;

    /**
     * @param int $writeCount
     * @return void
     */
    public function setWriteCount(int $writeCount): void;

    /**
     * @return int
     */
    public function getFilterCount(): int;

    /**
     * @param int $filterCount
     * @return void
     */
    public function setFilterCount(int $filterCount): void;

    /**
     * @return \DateTime
     */
    public function getStartTime(): \DateTime;

    /**
     * @param \DateTime $startTime
     * @return void
     */
    public function setStartTime(\DateTime $startTime): void;

    /**
     * @return BatchStatus
     */
    public function getStatus(): BatchStatus;

    /**
     * @param BatchStatus $batchStatus
     * @return void
     */
    public function setStatus(BatchStatus $batchStatus): void;

    /**
     * @param BatchStatus $batchStatus
     * @return void
     */
    public function upgradeStatus(BatchStatus $batchStatus): void;

    /**
     * @return string
     */
    public function getStepName(): string;

    /**
     * @param ExitStatus $exitStatus
     * @return void
     */
    public function setExitStatus(ExitStatus $exitStatus): void;

    /**
     * @return ExitStatus
     */
    public function getExitStatus(): ExitStatus;

    /**
     * @return JobExecutionInterface
     */
    public function getJobExecution(): JobExecutionInterface;

    /**
     * @return int
     */
    public function isTerminateOnly(): int;

    /**
     * @return void
     */
    public function setTerminateOnly(): void;

    /**
     * @return int
     */
    public function getSkipCount(): int;

    /**
     * @return JobParameters
     */
    public function getJobParameters(): JobParameters;

    /**
     * @return int
     */
    public function getReadSkipCount(): int;

    /**
     * @param int $readSkipCount
     * @return void
     */
    public function setReadSkipCount(int $readSkipCount): void;

    /**
     * @return int
     */
    public function getWriteSkipCount(): int;

    /**
     * @param int $writeSkipCount
     * @return void
     */
    public function setWriteSkipCount(int $writeSkipCount): void;

    /**
     * @return int
     */
    public function getProcessSkipCount(): int;

    /**
     * @param int $processSkipCount
     * @return void
     */
    public function setProcessSkipCount(int $processSkipCount): void;

    /**
     * @return \DateTime
     */
    public function getLastUpdated(): \DateTime;

    /**
     * @param \DateTime $lastUpdated
     * @return void
     */
    public function setLastUpdated(\DateTime $lastUpdated): void;

    /**
     * @return array|\Throwable[]
     */
    public function getFailureExceptions(): array;

    /**
     * @param \Throwable $throwable
     * @return void
     */
    public function addFailureException(\Throwable $throwable): void;
}