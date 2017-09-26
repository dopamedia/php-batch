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
     * @return string
     */
    public function getCode(): string;

    /**
     * @param string $code
     * @return void
     */
    public function setCode(string $code): void;

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
     * @return JobExecutionInterface
     */
    public function getJobExecution(): JobExecutionInterface;

    /**
     * @param JobExecutionInterface $jobExecution
     * @return void
     */
    public function addJobExecution(JobExecutionInterface $jobExecution): void;

    /**
     * @param JobExecutionInterface $jobExecution
     * @return void
     */
    public function removeJobExecution(JobExecutionInterface $jobExecution): void;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name): void;
}