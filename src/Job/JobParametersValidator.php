<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 04.10.17
 */

namespace Dopamedia\PhpBatch\Job;

use Dopamedia\PhpBatch\Job\JobParameters\ValidatorProviderRegistryInterface;
use Dopamedia\PhpBatch\Job\JobParameters\ValidatorProviderResult;
use Dopamedia\PhpBatch\JobInterface;

/**
 * Class JobParametersValidator
 * @package Dopamedia\PhpBatch\Job
 */
class JobParametersValidator
{
    /**
     * @var ValidatorProviderRegistryInterface
     */
    private $registry;

    /**
     * JobParametersValidator constructor.
     * @param ValidatorProviderRegistryInterface $registry
     */
    public function __construct(
        ValidatorProviderRegistryInterface $registry
    )
    {
        $this->registry = $registry;
    }

    /**
     * @param JobInterface $job
     * @param JobParameters $jobParameters
     * @return ValidatorProviderResult
     */
    public function validate(JobInterface $job, JobParameters $jobParameters): ValidatorProviderResult
    {
        return $this->registry->get($job)->validate($jobParameters);
    }
}