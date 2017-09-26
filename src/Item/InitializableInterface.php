<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 26.09.17
 */

namespace Dopamedia\PhpBatch\Item;

/**
 * Interface InitializableInterface
 * @package Dopamedia\PhpBatch\Item
 */
interface InitializableInterface
{
    /**
     * @return void
     */
    public function initialize(): void;
}