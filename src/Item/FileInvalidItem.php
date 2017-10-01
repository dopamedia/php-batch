<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 01.10.17
 */

namespace Dopamedia\PhpBatch\Item;

/**
 * Class FileInvalidItem
 * @package Dopamedia\PhpBatch\Item
 */
class FileInvalidItem implements InvalidItemInterface
{
    /**
     * @var array
     */
    private $invalidData;

    /**
     * @var int
     */
    private $itemPosition;

    /**
     * FileInvalidItem constructor.
     * @param array $invalidData
     * @param int $itemPosition
     */
    public function __construct(array $invalidData, int $itemPosition)
    {
        $this->invalidData = $invalidData;
        $this->itemPosition = $itemPosition;
    }

    /**
     * @inheritdoc
     */
    public function getInvalidData()
    {
        return $this->invalidData;
    }

    /**
     * @return int
     */
    public function getItemPosition(): int
    {
        return $this->itemPosition;
    }
}