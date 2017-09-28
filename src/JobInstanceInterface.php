<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 20.09.17
 */

namespace Dopamedia\PhpBatch;

/**
 * Interface JobInstanceInterface
 * @package Dopamedia\PhpBatch
 */
interface JobInstanceInterface extends EntityInterface
{
    /**
     * @return null|string
     */
    public function getCode(): ?string;

    /**
     * @param string $code
     * @return JobInstanceInterface
     */
    public function setCode(string $code): JobInstanceInterface;

    /**
     * @return null|string
     */
    public function getJobName(): ?string;

    /**
     * @param string $jobName
     * @return JobInstanceInterface
     */
    public function setJobName(string $jobName): JobInstanceInterface;

    /**
     * @return array
     */
    public function getRawParameters(): array;

    /**
     * @param array $rawParameters
     * @return JobInstanceInterface
     */
    public function setRawParameters(array $rawParameters): JobInstanceInterface;

    /**
     * @return JobExecutionInterface[]|array
     */
    public function getJobExecutions(): array;

    /**
     * @param JobExecutionInterface $jobExecution
     * @return JobInstanceInterface
     */
    public function addJobExecution(JobExecutionInterface $jobExecution): JobInstanceInterface;

    /**
     * @param JobExecutionInterface $jobExecution
     * @return JobInstanceInterface
     */
    public function removeJobExecution(JobExecutionInterface $jobExecution): JobInstanceInterface;
}