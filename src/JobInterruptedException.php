<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 25.09.17
 */

namespace Dopamedia\PhpBatch;

use Throwable;

/**
 * Class JobInterruptedException
 * @package Dopamedia\PhpBatch
 */
class JobInterruptedException extends \Exception
{
    /**
     * @var BatchStatus
     */
    private $status;

    /**
     * JobInterruptedException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @param BatchStatus|null $status
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        Throwable $previous = null,
        BatchStatus $status = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->status = $status ?? BatchStatus::STOPPED();
    }

    /**
     * @return BatchStatus
     */
    public function getStatus(): BatchStatus
    {
        return $this->status;
    }
}