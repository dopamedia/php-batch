<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 20.09.17
 */

namespace Dopamedia\PhpBatch;

/**
 * Interface JobInterface
 * @package Dopamedia\PhpBatch
 */
interface JobInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param JobExecutionInterface $execution
     */
    public function execute(JobExecutionInterface $execution): void;
}