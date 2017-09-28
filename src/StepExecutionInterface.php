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
     * @return null|string
     */
    public function getStepName(): ?string;

    /**
     * @param string $name
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
     * @return int
     */
    public function isTerminateOnly(): int;

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
}