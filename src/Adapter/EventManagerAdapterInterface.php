<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 26.09.17
 */

namespace Dopamedia\PhpBatch\Adapter;

/**
 * Interface EventManagerAdapterInterface
 * @package Dopamedia\PhpBatch\Adapter
 * @see https://github.com/php-fig/fig-standards/blob/master/proposed/event-manager.md
 */
interface EventManagerAdapterInterface
{
    /**
     * Attaches a listener to an event
     *
     * @param string $event the event to attach too
     * @param callable $callback a callable function
     * @param int $priority the priority at which the $callback executed
     * @return bool true on success false on failure
     */
    public function attach(string $event, callable $callback, int $priority = 0): bool;
}