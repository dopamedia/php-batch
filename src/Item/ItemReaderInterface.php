<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 26.09.17
 */

namespace Dopamedia\PhpBatch\Item;

/**
 * Interface ItemReaderInterface
 * @package Dopamedia\PhpBatch\Item
 */
interface ItemReaderInterface
{
    /**
     * @return mixed
     * @throws InvalidItemException
     */
    public function read();
}