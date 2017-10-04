<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 04.10.17
 */

namespace Dopamedia\PhpBatch\Job\JobParameters;

use Dopamedia\PhpBatch\JobInterface;

/**
 * Interface DefaultValuesProviderInterface
 * @package Dopamedia\PhpBatch\Job\JobParameters
 */
interface DefaultValuesProviderInterface
{
    /**
     * @return array
     */
    public function getDefaultValues(): array;

    /**
     * @param JobInterface $job
     * @return bool
     */
    public function supports(JobInterface $job): bool;
}