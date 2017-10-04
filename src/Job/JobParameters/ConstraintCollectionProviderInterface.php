<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 04.10.17
 */

namespace Dopamedia\PhpBatch\Job\JobParameters;

use Dopamedia\PhpBatch\JobInterface;
use Symfony\Component\Validator\Constraints\Collection;

/**
 * Interface ConstraintCollectionProviderInterface
 * @package Dopamedia\PhpBatch\Job\JobParameters
 */
interface ConstraintCollectionProviderInterface
{
    /**
     * @return Collection
     */
    public function getConstraintCollection(): Collection;

    /**
     * @param JobInterface $job
     * @return bool
     */
    public function supports(JobInterface $job): bool;

}