<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 04.10.17
 */

namespace Dopamedia\PhpBatch\Job\JobParameters;

use Dopamedia\PhpBatch\JobInterface;

/**
 * Interface DefaultValuesProviderRegistryInterface
 * @package Dopamedia\PhpBatch\Job\JobParameters
 */
interface DefaultValuesProviderRegistryInterface
{
    /**
     * @param JobInterface $job
     * @return DefaultValuesProviderInterface
     * @throws NonExistingDefaultValuesProviderException
     */
    public function get(JobInterface $job): DefaultValuesProviderInterface;
}