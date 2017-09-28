<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 27.09.17
 */

namespace Dopamedia\PhpBatch\Job;

/**
 * Class RuntimeErrorException
 * @package Dopamedia\PhpBatch\Job
 */
class RuntimeErrorException extends \RuntimeException
{
    /**
     * @var array
     */
    private $messageParameters;

    /**
     * RuntimeErrorException constructor.
     * @param string $message
     * @param array $messageParameters
     */
    public function __construct(
        string $message,
        array $messageParameters = []
    )
    {
        parent::__construct($message);

        $this->messageParameters = $messageParameters;
    }

    /**
     * @return array
     */
    public function getMessageParameters(): array
    {
        return $this->messageParameters;
    }
}