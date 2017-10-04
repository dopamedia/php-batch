<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 04.10.17
 */

namespace Dopamedia\PhpBatch\Job\JobParameters;

use Dopamedia\PhpBatch\JobInterface;

/**
 * Class EmptyDefaultValuesProvider
 * @package Dopamedia\PhpBatch\Job\JobParameters
 */
class EmptyDefaultValuesProvider implements DefaultValuesProviderInterface
{
    /**
     * @var array
     */
    private $supportedJobNames;

    /**
     * EmptyDefaultValuesProvider constructor.
     * @param array $supportedJobNames
     */
    public function __construct(array $supportedJobNames)
    {
        $this->supportedJobNames = $supportedJobNames;
    }

    /**
     * @inheritDoc
     */
    public function getDefaultValues(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function supports(JobInterface $job): bool
    {
        return in_array($job->getName(), $this->supportedJobNames);
    }
}