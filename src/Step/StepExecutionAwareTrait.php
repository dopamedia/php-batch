<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 01.10.17
 */

namespace Dopamedia\PhpBatch\Step;

use Dopamedia\PhpBatch\StepExecutionInterface;

/**
 * Trait StepExecutionAwareTrait
 * @package Dopamedia\PhpBatch\Step
 */
trait StepExecutionAwareTrait
{
    /**
     * @var null|StepExecutionInterface
     */
    protected $stepExecution;

    /**
     * @param StepExecutionInterface $stepExecution
     * @return void
     */
    public function setStepExecution(StepExecutionInterface $stepExecution): void
    {
        $this->stepExecution = $stepExecution;
    }

    /**
     * @return StepExecutionInterface|null
     */
    public function getStepExecution(): ?StepExecutionInterface
    {
        return $this->stepExecution;
    }
}