<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 04.10.17
 */

namespace Dopamedia\PhpBatch\Job\JobParameters;

use Dopamedia\PhpBatch\Job\JobParameters;
use Dopamedia\PhpBatch\JobInterface;

/**
 * Class EmptyValidatorProvider
 * @package Dopamedia\PhpBatch\Job\JobParameters
 */
class EmptyValidatorProvider implements ValidatorProviderInterface
{
    /**
     * @var array
     */
    private $supportedJobNames;

    /**
     * EmptyValidatorProvider constructor.
     * @param array $supportedJobNames
     */
    public function __construct(array $supportedJobNames)
    {
        $this->supportedJobNames = $supportedJobNames;
    }

    /**
     * @inheritDoc
     */
    public function validate(JobParameters $jobParameters): ValidatorProviderResult
    {
        return new ValidatorProviderResult();
    }

    /**
     * @inheritDoc
     */
    public function supports(JobInterface $job): bool
    {
        return in_array($job->getName(), $this->supportedJobNames);
    }

}