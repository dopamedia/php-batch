<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 26.09.17
 */

namespace Dopamedia\PhpBatch\Item;

use Throwable;

/**
 * Class InvalidItemException
 * @package Dopamedia\PhpBatch\Item
 */
class InvalidItemException extends \Exception
{
    /**
     * @var InvalidItemInterface
     */
    protected $item;

    /**
     * @var array
     */
    protected $messageParameters = [];

    /**
     * InvalidItemException constructor.
     * @param string $message
     * @param InvalidItemInterface $item
     * @param array $messageParameters
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        string $message = '',
        InvalidItemInterface $item,
        array $messageParameters = [],
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);

        $this->item = $item;
        $this->messageParameters = $messageParameters;
    }

    /**
     * @return InvalidItemInterface
     */
    public function getItem(): InvalidItemInterface
    {
        return $this->item;
    }

    /**
     * @return array
     */
    public function getMessageParameters(): array
    {
        return $this->messageParameters;
    }
}