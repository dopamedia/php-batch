<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 26.09.17
 */

namespace Dopamedia\PhpBatch\Item;

/**
 * Interface InvalidItemInterface
 * @package Dopamedia\PhpBatch\Item
 */
interface InvalidItemInterface
{
    /**
     * @return mixed
     */
    public function getInvalidData();
}