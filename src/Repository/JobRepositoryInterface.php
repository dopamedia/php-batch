<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 20.09.17
 */

namespace Dopamedia\PhpBatch\Repository;

use Dopamedia\PhpBatch\JobExecutionInterface;
use Dopamedia\PhpBatch\JobInstanceInterface;
use Dopamedia\PhpBatch\Job\JobParameters;
use Dopamedia\PhpBatch\StepExecutionInterface;
use Dopamedia\PhpBatch\WarningInterface;

/**
 * Interface JobRepositoryInterface
 * @package Dopamedia\PhpBatch\Repository
 */
interface JobRepositoryInterface
{
    /**
     * @param int $id
     * @return JobExecutionInterface
     * @throws \Exception
     */
    public function getJobExecutionById(int $id): JobExecutionInterface;

    /**
     * @param JobInstanceInterface $jobInstance
     * @param JobParameters $jobParameters
     * @return JobExecutionInterface
     */
    public function createJobExecution(JobInstanceInterface $jobInstance, JobParameters $jobParameters): JobExecutionInterface;

    /**
     * @param JobExecutionInterface $jobExecution
     * @return JobExecutionInterface
     * @throws \Exception
     */
    public function saveJobExecution(JobExecutionInterface $jobExecution): JobExecutionInterface;

    /**
     * @param int $id
     * @return JobInstanceInterface
     * @throws \Exception
     */
    public function getJobInstanceById(int $id): JobInstanceInterface;

    /**
     * @param string $code
     * @return JobInstanceInterface
     * @throws \Exception
     */
    public function getJobInstanceByCode(string $code): JobInstanceInterface;

    /**
     * @return array|JobInstanceInterface[]
     */
    public function getJobInstances(): array;

    /**
     * @param JobInstanceInterface $jobInstance
     * @return JobInstanceInterface
     * @throws \Exception
     */
    public function saveJobInstance(JobInstanceInterface $jobInstance): JobInstanceInterface;

    /**
     * @param int $id
     * @return StepExecutionInterface
     * @throws \Exception
     */
    public function getStepExecutionById(int $id): StepExecutionInterface;

    /**
     * @param StepExecutionInterface $stepExecution
     * @return StepExecutionInterface
     * @throws \Exception
     */
    public function saveStepExecution(StepExecutionInterface $stepExecution): StepExecutionInterface;

    /**
     * @param int $id
     * @return WarningInterface
     * @throws \Exception
     */
    public function getWarningById(int $id): WarningInterface;

    /**
     * @param StepExecutionInterface $stepExecution
     * @param string $reason
     * @param array $reasonParameters
     * @param array $item
     * @return WarningInterface
     */
    public function createWarning(
        StepExecutionInterface $stepExecution,
        string $reason,
        array $reasonParameters = [],
        array $item = []
    ): WarningInterface;

    /**
     * @param WarningInterface $warning
     * @return WarningInterface
     * @throws \Exception
     */
    public function saveWarning(WarningInterface $warning): WarningInterface;
}