<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 04.10.17
 */

namespace Dopamedia\PhpBatch\Job\JobParameters;

use Dopamedia\PhpBatch\JobInterface;
use Symfony\Component\Validator\Constraints\Collection;

/**
 * Class EmptyConstraintCollectionProvider
 * @package Dopamedia\PhpBatch\Job\JobParameters
 */
class EmptyConstraintCollectionProvider implements ConstraintCollectionProviderInterface
{
    /**
     * @var array
     */
    private $supportedJobNames;

    /**
     * EmptyConstraintCollectionProvider constructor.
     * @param array $supportedJobNames
     */
    public function __construct(array $supportedJobNames)
    {
        $this->supportedJobNames = $supportedJobNames;
    }

    /**
     * @inheritDoc
     */
    public function getConstraintCollection(): Collection
    {
        return new Collection(['fields' => []]);
    }

    /**
     * @inheritDoc
     */
    public function supports(JobInterface $job): bool
    {
        return in_array($job->getName(), $this->supportedJobNames);
    }

}