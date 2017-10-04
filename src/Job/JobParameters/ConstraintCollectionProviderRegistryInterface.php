<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 04.10.17
 */

namespace Dopamedia\PhpBatch\Job\JobParameters;

use Dopamedia\PhpBatch\JobInterface;

/**
 * Interface ConstraintCollectionProviderRegistryInterface
 * @package Dopamedia\PhpBatch\Job\JobParameters
 */
interface ConstraintCollectionProviderRegistryInterface
{
    /**
     * @param JobInterface $job
     * @return ConstraintCollectionProviderInterface
     * @throws NonExistingConstraintCollectionProviderException
     */
    public function get(JobInterface $job): ConstraintCollectionProviderInterface;
}