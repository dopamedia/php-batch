<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 20.09.17
 */

namespace Dopamedia\PhpBatch;

use Dopamedia\PhpBatch\Item\ExecutionContext;

/**
 * Interface JobExecutionInterface
 * @package Dopamedia\PhpBatch
 */
interface JobExecutionInterface extends EntityInterface
{
    /**
     * @return JobParameters
     */
    public function getJobParameters(): JobParameters;

    /**
     * @param JobParameters $jobParameters
     * @return void
     */
    public function setJobParameters(JobParameters $jobParameters): void;

    /**
     * @return JobInstanceInterface
     */
    public function getJobInstance(): JobInstanceInterface;

    /**
     * @param JobInstanceInterface $jobInstance
     */
    public function setJobInstance(JobInstanceInterface $jobInstance): void;

    /**
     * @return StepExecutionInterface|array
     */
    public function getStepExecutions(): array;

    /**
     * @param string $name
     * @return StepExecutionInterface
     */
    public function createStepExecution(string $name): StepExecutionInterface;

    /**
     * @param StepExecutionInterface $stepExecution
     * @return void
     */
    public function addStepExecution(StepExecutionInterface $stepExecution): void;

    /**
     * @return BatchStatus
     */
    public function getStatus(): BatchStatus;

    /**
     * @param BatchStatus $status
     * @return void
     */
    public function setStatus(BatchStatus $status): void;

    /**
     * @param BatchStatus $status
     * @return void
     */
    public function upgradeStatus(BatchStatus $status): void;

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
     * @return \DateTime
     */
    public function getCreateTime(): \DateTime;

    /**
     * @param \DateTime $createTime
     * @return void
     */
    public function setCreateTime(\DateTime $createTime): void;

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
     * @return \DateTime
     */
    public function getLastUpdated(): \DateTime;

    /**
     * @param \DateTime $lastUpdated
     * @return void
     */
    public function setLastUpdated(\DateTime $lastUpdated): void;

    /**
     * @return ExitStatus
     */
    public function getExitStatus(): ExitStatus;

    /**
     * @param ExitStatus $exitStatus
     * @return void
     */
    public function setExitStatus(ExitStatus $exitStatus): void;

    /**
     * @return ExecutionContext
     */
    public function getExecutionContext(): ExecutionContext;

    /**
     * @param ExecutionContext $executionContext
     * @return void
     */
    public function setExecutionContext(ExecutionContext $executionContext): void;

    /**
     * @return array
     */
    public function getFailureExceptions(): array;

    /**
     * @param \Throwable $e
     * @return void
     */
    public function addFailureException(\Throwable $e): void;

    /**
     * @return array
     */
    public function getAllFailureExceptions(): array;

    /**
     * @return bool
     */
    public function isStopping(): bool;
}