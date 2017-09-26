<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 26.09.17
 */

namespace Dopamedia\PhpBatch\Item;

/**
 * Interface ItemWriterInterface
 * @package Dopamedia\PhpBatch\Item
 */
interface ItemWriterInterface
{
    /**
     * @param array $items
     * @return mixed
     * @throws InvalidItemException
     */
    public function write(array $items);
}