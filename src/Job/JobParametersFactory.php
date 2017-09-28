<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 20.09.17
 */

namespace Dopamedia\PhpBatch\Job;

use Dopamedia\PhpBatch\JobInterface;
use Magento\Framework\ObjectManagerInterface;

/**
 * Class JobParametersFactory
 * @package Dopamedia\PhpBatch\Job
 */
class JobParametersFactory
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * JobParametersFactory constructor.
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param JobInterface $job
     * @param array $parameters
     * @return JobParameters
     */
    public function create(JobInterface $job, array $parameters = []): JobParameters
    {
        return $this->objectManager->create(JobParameters::class, ['parameters' => $parameters]);
    }
}