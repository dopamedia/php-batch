<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 20.09.17
 */

namespace Dopamedia\PhpBatch;

/**
 * Interface StepInterface
 * @package Dopamedia\PhpBatch
 */
interface StepInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param StepExecutionInterface $execution
     * @throws JobInterruptedException
     * @return void
     */
    public function execute(StepExecutionInterface $execution): void;
}