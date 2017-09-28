<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 28.09.17
 */

namespace Dopamedia\PhpBatch\Job;

use Dopamedia\PhpBatch\JobInterface;
use Dopamedia\PhpBatch\Job\UndefinedJobException;

/**
 * Interface JobRegistryInterface
 * @package Dopamedia\PhpBatch\Job
 */
interface JobRegistryInterface
{
    /**
     * @param string $jobName
     * @return JobInterface
     * @throws UndefinedJobException
     */
    public function getJob(string $jobName): JobInterface;

    /**
     * @return JobInterface[]|array
     */
    public function getJobs(): array;
}