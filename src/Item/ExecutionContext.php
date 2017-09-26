<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 20.09.17
 */

namespace Dopamedia\PhpBatch\Item;

/**
 * Class ExecutionContext
 * @package Dopamedia\PhpBatch\Item
 */
class ExecutionContext
{
    /**
     * @var bool
     */
    private $dirty = false;

    /**
     * @var array
     */
    private $map = [];

    /**
     * @return bool
     */
    public function isDirty(): bool
    {
        return $this->dirty;
    }

    /**
     * @return void
     */
    public function clearDirtyFlag(): void
    {
        $this->dirty = false;
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function get(string $key)
    {
        $value = null;

        if (isset($this->map[$key])) {
            $value = $this->map[$key];
        }

        return $value;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function put(string $key, $value): void
    {
        $this->map[$key] = $value;
        $this->dirty = true;
    }

    /**
     * @param string $key
     * @return void
     */
    public function remove(string $key): void
    {
        if (isset($this->map[$key])) {
            unset($this->map[$key]);
        }
    }

    /**
     * @return array|string[]
     */
    public function getKeys(): array
    {
        return array_keys($this->map);
    }
}