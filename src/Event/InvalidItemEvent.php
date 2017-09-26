<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 26.09.17
 */

namespace Dopamedia\PhpBatch\Event;

use Dopamedia\PhpBatch\Item\InvalidItemInterface;

/**
 * Class InvalidItemEvent
 * @package Dopamedia\PhpBatch\Event
 */
class InvalidItemEvent implements EventInterface
{
    /**
     * @var InvalidItemInterface
     */
    private $item;

    /**
     * @var string
     */
    private $class;

    /**
     * @var string
     */
    private $reason;

    /**
     * @var array
     */
    private $reasonParameters;

    /**
     * InvalidItemEvent constructor.
     * @param InvalidItemInterface $item
     * @param string $class
     * @param string $reason
     * @param array $reasonParameters
     */
    public function __construct(
        InvalidItemInterface $item,
        string $class,
        string $reason,
        array $reasonParameters
    ) {
        $this->item = $item;
        $this->class = $class;
        $this->reason = $reason;
        $this->reasonParameters = $reasonParameters;
    }

    /**
     * @return InvalidItemInterface
     */
    public function getItem(): InvalidItemInterface
    {
        return $this->item;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }

    /**
     * @return array
     */
    public function getReasonParameters(): array
    {
        return $this->reasonParameters;
    }
}