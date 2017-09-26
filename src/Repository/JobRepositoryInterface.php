<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 20.09.17
 */

namespace Dopamedia\PhpBatch\Repository;

use Dopamedia\PhpBatch\JobExecutionInterface;
use Dopamedia\PhpBatch\JobInstanceInterface;
use Dopamedia\PhpBatch\JobParameters;
use Dopamedia\PhpBatch\StepExecutionInterface;

/**
 * Interface JobRepositoryInterface
 * @package Dopamedia\PhpBatch\Repository
 */
interface JobRepositoryInterface
{
    /**
     * @param JobInstanceInterface $job
     * @param JobParameters $jobParameters
     * @return JobExecutionInterface
     */
    public function createJobExecution(JobInstanceInterface $job, JobParameters $jobParameters): JobExecutionInterface;

    /**
     * @param JobExecutionInterface $jobExecution
     * @return void
     */
    public function updateJobExecution(JobExecutionInterface $jobExecution): void;

    /**
     * @param StepExecutionInterface $stepExecution
     * @return void
     */
    public function updateStepExecution(StepExecutionInterface $stepExecution): void;

    /**
     * @param JobInstanceInterface $jobInstance
     * @param string $stepName
     * @return StepExecutionInterface
     */
    public function getLastStepExecution(JobInstanceInterface $jobInstance, string $stepName): StepExecutionInterface;

    /**
     * @param StepExecutionInterface $stepExecution
     * @param string $reason
     * @param array $reasonParameters
     * @param array $item
     * @return void
     */
    public function createWarning(
        StepExecutionInterface $stepExecution,
        string $reason,
        array $reasonParameters = [],
        array $item = []
    ): void;
}