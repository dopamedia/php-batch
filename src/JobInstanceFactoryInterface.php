<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 08.10.17
 */

namespace Dopamedia\PhpBatch;

/**
 * Interface JobInstanceFactoryInterface
 * @package Dopamedia\PhpBatch
 */
interface JobInstanceFactoryInterface
{
    /**
     * @return JobInstanceInterface
     */
    public function create(): JobInstanceInterface;
}