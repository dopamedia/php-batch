<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 26.09.17
 */

namespace Dopamedia\PhpBatch\Event;

use Dopamedia\PhpBatch\JobExecutionInterface;

/**
 * Class JobExecutionEvent
 * @package Dopamedia\PhpBatch\Event
 */
class JobExecutionEvent implements EventInterface
{
    /**
     * @var JobExecutionInterface
     */
    private $jobExecution;

    /**
     * JobExecutionEvent constructor.
     * @param JobExecutionInterface $jobExecution
     */
    public function __construct(JobExecutionInterface $jobExecution)
    {
        $this->jobExecution = $jobExecution;
    }

    /**
     * @return JobExecutionInterface
     */
    public function getJobExecution(): JobExecutionInterface
    {
        return $this->jobExecution;
    }
}