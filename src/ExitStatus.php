<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 20.09.17
 */

namespace Dopamedia\PhpBatch;

/**
 * Class ExitStatus
 * @package Dopamedia\PhpBatch
 */
class ExitStatus
{
    public const EXECUTING = 'EXECUTING';
    public const COMPLETED = 'COMPLETED';
    public const NOOP = 'NOOP';
    public const FAILED = 'FAILED';
    public const STOPPED = 'STOPPED';
    public const UNKNOWN = 'UNKNOWN';

    private const MAX_SEVERITY = 7;

    /**
     * @var string[]
     */
    protected static $statusSeverity = [
        self::EXECUTING => 1,
        self::COMPLETED => 2,
        self::NOOP      => 3,
        self::STOPPED   => 4,
        self::FAILED    => 5,
        self::UNKNOWN   => 6
    ];

    /**
     * @var string
     */
    private $exitCode;
    /**
     * @var string
     */
    private $exitDescription;

    /**
     * ExitStatus constructor.
     * @param string $exitCode
     * @param string $exitDescription
     */
    public function __construct(string $exitCode, string $exitDescription = '')
    {
        $this->exitCode = $exitCode;
        $this->exitDescription = $exitDescription;
    }

    /**
     * @param ExitStatus|null $other
     * @return ExitStatus
     */
    public function logicalAnd(ExitStatus $other = null): ExitStatus
    {
        if ($other === null) {
            return $this;
        }

        $status = $this->addExitDescription($other->getExitDescription());

        if ($this->compareTo($other) < 0) {
            $status = $status->replaceExitCode($other->getExitCode());
        }

        return $status;
    }

    /**
     * @param $description
     * @return ExitStatus
     */
    public function addExitDescription($description): ExitStatus
    {
        if ($description instanceof \Throwable) {
            $description = $description->getTraceAsString();
        }

        if ((string)$description !== '' && (string)$description !== $this->exitDescription) {
            if ($this->exitDescription !== '') {
                $this->exitDescription .= '; ';
            }

            $this->exitDescription .= $description;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getExitDescription(): string
    {
        return $this->exitDescription;
    }

    /**
     * @param ExitStatus $status
     * @return int
     */
    public function compareTo(ExitStatus $status): int
    {
        return $status->severity() <=> $this->severity();
    }

    /**
     * @return int
     */
    private function severity(): int
    {
        $severity = self::MAX_SEVERITY;

        if (isset(self::$statusSeverity[$this->exitCode])) {
            $severity = self::$statusSeverity[$this->exitCode];
        }

        return $severity;
    }

    /**
     * @param string $code
     * @return ExitStatus
     */
    public function replaceExitCode(string $code): ExitStatus
    {
        return new self($code, $this->exitDescription);
    }

    /**
     * @return string
     */
    public function getExitCode(): string
    {
        return $this->exitCode;
    }

    /**
     * @return bool
     */
    public function isRunning(): bool
    {
        return $this->exitCode === 'EXECUTING' || $this->exitCode === 'UNKNOWN';
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return sprintf('[%s] %s', $this->exitCode, $this->exitDescription);
    }


}