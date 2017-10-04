<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 04.10.17
 */

namespace Dopamedia\PhpBatch\Job\JobParameters;

/**
 * Class ValidatorProviderResult
 * @package Dopamedia\PhpBatch\Job\JobParameters
 */
class ValidatorProviderResult
{
    /**
     * @var array|string
     */
    private $messages = [];

    /**
     * @param string $message
     * @return void
     */
    public function addMessage(string $message): void
    {
        $this->messages[] = $message;
    }

    /**
     * @return bool
     */
    public function hasMessages(): bool
    {
        return count($this->messages) > 0;
    }

    /**
     * @return array|string[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $validatorMessages = '';

        foreach ($this->messages as $message) {
            $validatorMessages .= sprintf("\n  - %s", $message);
        }

        return $validatorMessages;
    }
}