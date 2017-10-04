<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 04.10.17
 */

namespace Dopamedia\PhpBatch\Job\JobParameters;

use Dopamedia\PhpBatch\Job\JobParameters;
use Dopamedia\PhpBatch\JobInterface;

/**
 * Interface ValidatorProviderInterface
 * @package Dopamedia\PhpBatch\Job\JobParameters
 */
interface ValidatorProviderInterface
{
    /**
     * @param JobParameters $jobParameters
     * @return ValidatorProviderResult
     */
    public function validate(JobParameters $jobParameters): ValidatorProviderResult;

    /**
     * @param JobInterface $job
     * @return bool
     */
    public function supports(JobInterface $job): bool;
}