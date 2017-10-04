<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 20.09.17
 */

namespace Dopamedia\PhpBatch\Job;

use Dopamedia\PhpBatch\Job\JobParameters\DefaultValuesProviderRegistryInterface;
use Dopamedia\PhpBatch\JobInterface;

/**
 * Class JobParametersFactory
 * @package Dopamedia\PhpBatch\Job
 */
class JobParametersFactory
{
    /**
     * @var DefaultValuesProviderRegistryInterface
     */
    private $defaultValuesProviderRegistry;

    /**
     * @var string
     */
    private $jobParametersClass;

    /**
     * JobParametersFactory constructor.
     * @param DefaultValuesProviderRegistryInterface $defaultValuesProviderRegistry
     * @param string $jobParametersClass
     */
    public function __construct(
        DefaultValuesProviderRegistryInterface $defaultValuesProviderRegistry,
        string $jobParametersClass = JobParameters::class
    )
    {
        $this->defaultValuesProviderRegistry = $defaultValuesProviderRegistry;
        $this->jobParametersClass = $jobParametersClass;
    }

    /**
     * @param JobInterface $job
     * @param array $parameters
     * @return JobParameters
     */
    public function create(JobInterface $job, array $parameters = []): JobParameters
    {
        $provider = $this->defaultValuesProviderRegistry->get($job);
        $parameters = array_merge($provider->getDefaultValues(), $parameters);

        return new $this->jobParametersClass($parameters);
    }
}