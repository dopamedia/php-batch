<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 26.09.17
 */

namespace Dopamedia\PhpBatch\Item;

/**
 * Interface FlushableInterface
 * @package Dopamedia\PhpBatch\Item
 */
interface FlushableInterface
{
    /**
     * @return void
     */
    public function flush(): void;
}