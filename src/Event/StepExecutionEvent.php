<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 26.09.17
 */

namespace Dopamedia\PhpBatch\Event;

use Dopamedia\PhpBatch\StepExecutionInterface;

/**
 * Class StepExecutionEvent
 * @package Dopamedia\PhpBatch\Event
 */
class StepExecutionEvent implements EventInterface
{
    /**
     * @var StepExecutionInterface
     */
    private $stepExecution;

    /**
     * StepExecutionEvent constructor.
     * @param StepExecutionInterface $stepExecution
     */
    public function __construct(StepExecutionInterface $stepExecution)
    {
        $this->stepExecution = $stepExecution;
    }

    /**
     * @return StepExecutionInterface
     */
    public function getStepExecution(): StepExecutionInterface
    {
        return $this->stepExecution;
    }
}