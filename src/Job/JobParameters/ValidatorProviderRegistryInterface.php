<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 04.10.17
 */

namespace Dopamedia\PhpBatch\Job\JobParameters;

use Dopamedia\PhpBatch\JobInterface;

/**
 * Interface ValidatorProviderRegistryInterface
 * @package Dopamedia\PhpBatch\Job\JobParameters
 */
interface ValidatorProviderRegistryInterface
{
    /**
     * @param JobInterface $job
     * @return ValidatorProviderInterface
     * @throws NonExistingValidatorProviderException
     */
    public function get(JobInterface $job): ValidatorProviderInterface;
}