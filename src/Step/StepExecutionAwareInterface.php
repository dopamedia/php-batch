<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 26.09.17
 */

namespace Dopamedia\PhpBatch\Step;

use Dopamedia\PhpBatch\StepExecutionInterface;

/**
 * Interface StepExecutionAwareInterface
 * @package Dopamedia\PhpBatch\Step
 */
interface StepExecutionAwareInterface
{
    /**
     * @param StepExecutionInterface $stepExecution
     */
    public function setStepExecution(StepExecutionInterface $stepExecution): void;
}