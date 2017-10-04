<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 20.09.17
 */

namespace Dopamedia\PhpBatch\Job;

/**
 * Class JobParameters
 * @package Dopamedia\PhpBatch\Job
 */
class JobParameters implements \IteratorAggregate, \Countable
{
    /**
     * @var array
     */
    private $parameters;

    /**
     * JobParameters constructor.
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @param $key
     * @return bool
     */
    public function has($key): bool
    {
        return array_key_exists($key, $this->parameters);
    }

    /**
     * @param string $key
     * @return mixed
     * @throws UndefinedJobParameterException
     */
    public function get(string $key)
    {
        if (!array_key_exists($key, $this->parameters)) {
            throw new UndefinedJobParameterException(sprintf('Parameter "%s" is undefined', $key));
        }

        return $this->parameters[$key];
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->parameters);
    }
}