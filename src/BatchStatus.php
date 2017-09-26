<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 20.09.17
 */

namespace Dopamedia\PhpBatch;

use MyCLabs\Enum\Enum;

/**
 * Class BatchStatus
 * @package Dopamedia\PhpBatch
 *
 * @method static BatchStatus COMPLETED()
 * @method static BatchStatus STARTING()
 * @method static BatchStatus STARTED()
 * @method static BatchStatus STOPPING()
 * @method static BatchStatus STOPPED()
 * @method static BatchStatus FAILED()
 * @method static BatchStatus ABANDONED()
 * @method static BatchStatus UNKNOWN()
 */
class BatchStatus extends Enum
{
    public const COMPLETED = 1;
    public const STARTING = 2;
    public const STARTED = 3;
    public const STOPPING = 4;
    public const STOPPED = 5;
    public const FAILED = 6;
    public const ABANDONED = 7;
    public const UNKNOWN = 8;

    /**
     * @var string[]
     */
    private static $statusLabels = [
        self::COMPLETED => 'COMPLETED',
        self::STARTING  => 'STARTING',
        self::STARTED   => 'STARTED',
        self::STOPPING  => 'STOPPING',
        self::STOPPED   => 'STOPPED',
        self::FAILED    => 'FAILED',
        self::ABANDONED => 'ABANDONED',
        self::UNKNOWN   => 'UNKNOWN'
    ];

    /**
     * BatchStatus constructor.
     * @param int $value
     */
    public function __construct(int $value = self::UNKNOWN)
    {
        parent::__construct($value);
    }

    /**
     * @return string[]
     */
    public static function getAllLabels(): array
    {
        return self::$statusLabels;
    }

    /**
     * @return bool
     */
    public function isRunning(): bool
    {
        return $this->value === self::STARTING || $this->value === self::STARTED;
    }

    /**
     * @return bool
     */
    public function isUnsuccessful(): bool
    {
        return $this->value === self::FAILED || $this->isGreaterThan(self::FAILED());
    }

    /**
     * @param BatchStatus $other
     * @return bool
     */
    public function isGreaterThan(BatchStatus $other): bool
    {
        return $other->getValue() < $this->value;
    }

    /**
     * @param BatchStatus $other
     * @return BatchStatus
     */
    public function upgradeTo(BatchStatus $other): BatchStatus
    {
        if ($this->isGreaterThan(self::STARTED()) || $other->isGreaterThan(self::STARTED())) {
            return self::max($this, $other);
        }

        if ($this->value === self::COMPLETED || $other->getValue() === self::COMPLETED) {
            return self::COMPLETED();
        }

        return self::max($this, $other);
    }

    /**
     * @param BatchStatus $status1
     * @param BatchStatus $status2
     * @return BatchStatus
     */
    public static function max(BatchStatus $status1, BatchStatus $status2): BatchStatus
    {
        return $status1->isGreaterThan($status2) ? $status1 : $status2;
    }

    /**
     * @param BatchStatus $other
     * @return bool
     */
    public function isLessThan(BatchStatus $other): bool
    {
        return $other->getValue() > $this->value;
    }

    /**
     * @param BatchStatus $other
     * @return bool
     */
    public function isLessThanOrEqualTo(BatchStatus $other): bool
    {
        return $other->getValue() >= $this->value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return self::$statusLabels[$this->value];
    }
}