<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 04.10.17
 */

namespace Dopamedia\PhpBatch\Job;

use Dopamedia\PhpBatch\Job\JobParameters\ConstraintCollectionProviderRegistryInterface;
use Dopamedia\PhpBatch\JobInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class JobParametersValidator
 * @package Dopamedia\PhpBatch\Job
 */
class JobParametersValidator
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var ConstraintCollectionProviderRegistryInterface
     */
    private $registry;

    /**
     * JobParametersValidator constructor.
     * @param ValidatorInterface $validator
     * @param ConstraintCollectionProviderRegistryInterface $registry
     */
    public function __construct(
        ValidatorInterface $validator,
        ConstraintCollectionProviderRegistryInterface $registry
    )
    {
        $this->validator = $validator;
        $this->registry = $registry;
    }

    /**
     * @param JobInterface $job
     * @param JobParameters $jobParameters
     * @param array $groups
     * @return ConstraintViolationListInterface
     */
    public function validate(
        JobInterface $job,
        JobParameters $jobParameters,
        array $groups = []
    ): ConstraintViolationListInterface
    {
        $provider = $this->registry->get($job);
        $collection = $provider->getConstraintCollection();
        $parameters = $jobParameters->all();

        return $this->validator->validate($parameters, $collection, $groups);
    }
}