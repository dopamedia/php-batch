<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 26.09.17
 */

namespace Dopamedia\PhpBatch\Item;

/**
 * Interface ItemProcessorInterface
 * @package Dopamedia\PhpBatch\Item
 */
interface ItemProcessorInterface
{
    /**
     * @param mixed $item
     * @return mixed
     * @throws InvalidItemException
     */
    public function process($item);
}