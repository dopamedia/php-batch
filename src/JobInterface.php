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
     * @param string $name
     * @return mixed
     */
    public function getName(string $name);

    /**
     * @param JobExecutionInterface $execution
     */
    public function execute(JobExecutionInterface $execution): void;
}