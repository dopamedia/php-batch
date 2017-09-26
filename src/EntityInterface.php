<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 20.09.17
 */

namespace Dopamedia\PhpBatch;

/**
 * Interface EntityInterface
 * @package Dopamedia\PhpBatch
 */
interface EntityInterface
{
    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @param int $id
     * @return EntityInterface
     */
    public function setId($id): EntityInterface;

}