<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 20.09.17
 */

namespace Dopamedia\PhpBatch;

use Dopamedia\PhpBatch\Item\ExecutionContext;
use Dopamedia\PhpBatch\Job\JobParameters;

/**
 * Interface JobExecutionInterface
 * @package Dopamedia\PhpBatch
 */
interface JobExecutionInterface extends EntityInterface
{
    /**
     * @return JobParameters|null
     */
    public function getJobParameters(): ?JobParameters;

    /**
     * @param JobParameters $jobParameters
     * @return JobExecutionInterface
     */
    public function setJobParameters(JobParameters $jobParameters): JobExecutionInterface;

    /**
     * @return JobInstanceInterface|null
     */
    public function getJobInstance(): ?JobInstanceInterface;

    /**
     * @param JobInstanceInterface $jobInstance
     * @return JobExecutionInterface
     */
    public function setJobInstance(JobInstanceInterface $jobInstance): JobExecutionInterface;

    /**
     * @return StepExecutionInterface[]|array
     */
    public function getStepExecutions(): array;

    /**
     * @param string $name
     * @return StepExecutionInterface
     */
    public function createStepExecution(string $name): StepExecutionInterface;

    /**
     * @param StepExecutionInterface $stepExecution
     * @return StepExecutionInterface
     */
    public function addStepExecution(StepExecutionInterface $stepExecution): JobExecutionInterface;

    /**
     * @return BatchStatus
     */
    public function getStatus(): BatchStatus;

    /**
     * @param BatchStatus $status
     * @return JobExecutionInterface
     */
    public function setStatus(BatchStatus $status): JobExecutionInterface;

    /**
     * @param BatchStatus $status
     * @return JobExecutionInterface
     */
    public function upgradeStatus(BatchStatus $status): JobExecutionInterface;

    /**
     * @return \DateTime
     */
    public function getStartTime(): \DateTime;

    /**
     * @param \DateTime $startTime
     * @return JobExecutionInterface
     */
    public function setStartTime(\DateTime $startTime): JobExecutionInterface;

    /**
     * @return \DateTime
     */
    public function getCreateTime(): \DateTime;

    /**
     * @param \DateTime $createTime
     * @return JobExecutionInterface
     */
    public function setCreateTime(\DateTime $createTime): JobExecutionInterface;

    /**
     * @return \DateTime
     */
    public function getEndTime(): \DateTime;

    /**
     * @param \DateTime $endTime
     * @return JobExecutionInterface
     */
    public function setEndTime(\DateTime $endTime): JobExecutionInterface;

    /**
     * @return null|string
     */
    public function getExitCode(): ?string;

    /**
     * @return null|string
     */
    public function getExitDescription(): ?string;

    /**
     * @return ExitStatus
     */
    public function getExitStatus(): ExitStatus;

    /**
     * @param ExitStatus $exitStatus
     * @return JobExecutionInterface
     */
    public function setExitStatus(ExitStatus $exitStatus): JobExecutionInterface;

    /**
     * @return ExecutionContext
     */
    public function getExecutionContext(): ExecutionContext;

    /**
     * @param ExecutionContext $executionContext
     * @return JobExecutionInterface
     */
    public function setExecutionContext(ExecutionContext $executionContext): JobExecutionInterface;

    /**
     * @return array
     */
    public function getFailureExceptions(): array;

    /**
     * @param \Throwable $e
     * @return JobExecutionInterface
     */
    public function addFailureException(\Throwable $e): JobExecutionInterface;

    /**
     * @return array
     */
    public function getAllFailureExceptions(): array;

    /**
     * @return bool
     */
    public function isStopping(): bool;
}