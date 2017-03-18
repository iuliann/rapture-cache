<?php

namespace Rapture\Cache\Adapter;

use Rapture\Cache\Definition\CacheInterface;
use Rapture\Cache\Definition\CacheTrait;

/**
 * In memory cache adapter
 * Can be used as a fallback when developing
 * Only caches data per request - TTL's are not taken in consideration.
 *
 * @package Rapture\Cache\Adapter
 * @author Iulian N. <rapture@iuliann.ro>
 * @license LICENSE MIT
 */
class Memory implements CacheInterface
{
    use CacheTrait;

    /**
     * @var array $cache Cached data
     */
    protected $cache = [];

    /**
     * Set a cache key
     *
     * @param string $key Cache key
     * @param mixed $data Cache data
     * @param int $ttl Time-to-live
     *
     * @return bool
     */
    public function set($key, $data = null, $ttl = null)
    {
        $this->cache[$this->key($key)] = $data;
    }

    /**
     * Get a cache key data
     *
     * @param string $key Cache key
     * @param mixed $default Default value to return if cache key doesn't exist
     * @param int $ttl Time-to-live (only for File driver)
     *
     * @return mixed
     */
    public function get($key, $default = null, $ttl = null)
    {
        $key = $this->key($key);

        return isset($this->cache[$key])
            ? $this->cache[$key]
            : $default;
    }

    /**
     * Checks whether a cache key exists
     *
     * @param string $key Cache key
     * @param null $ttl Time-to-live (only for File driver)
     *
     * @return bool
     */
    public function exists($key, $ttl = null)
    {
        return isset($this->cache[$this->key($key)]);
    }

    /**
     * Delete a key from cache
     *
     * @param string $key Cache key
     *
     * @return bool
     */
    public function delete($key)
    {
        unset($this->cache[$this->key($key)]);

        return true;
    }

    /**
     * Increment a cache key with $step
     *
     * @param string $key Cache key
     * @param int $step Increment step
     *
     * @return int Value after incrementation
     */
    public function inc($key, $step = 1)
    {
        $key = $this->key($key);

        $this->cache[$key] = isset($this->cache[$key])
            ? $this->cache[$key] + $step
            : $step;

        return $this->cache[$key];
    }

    /**
     * Decrement a cache key with $step
     *
     * @param string $key Cache key
     * @param int $step Increment step
     *
     * @return int Value after decrement
     */
    public function dec($key, $step = 1)
    {
        return $this->inc($key, -1 * $step);
    }

    /**
     * Clears the cache
     *
     * @return bool
     */
    public function clear()
    {
        $this->cache = [];

        return true;
    }

    /**
     * all
     *
     * @return array
     */
    public function all()
    {
        return $this->cache;
    }
}
