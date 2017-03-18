<?php

namespace Rapture\Cache\Adapter;

use Rapture\Cache\Definition\CacheInterface;
use Rapture\Cache\Definition\CacheTrait;

/**
 * APC cache adapter
 * Requires APCu v5.1 module to be installed
 *
 * @package Rapture\Cache
 * @author  Iulian N. <Rapture@iuliann.ro>
 * @license LICENSE MIT
 */
class APCu implements CacheInterface
{
    use CacheTrait;

    /**
     * Set a cache key
     *
     * @param string $key  Cache key
     * @param mixed  $data Cache data
     * @param int    $ttl  Time-to-live
     *
     * @return bool
     */
    public function set($key, $data = null, $ttl = null)
    {
        return apcu_store($this->key($key), $data, $this->getTtl($ttl));
    }

    /**
     * Get a cache key data
     *
     * @param string $key     Cache key
     * @param mixed  $default Default value to return if cache key doesn't exist
     * @param int    $ttl     Time-to-live (only for File driver)
     *
     * @return mixed
     */
    public function get($key, $default = null, $ttl = null)
    {
        return apcu_exists($this->key($key)) ? apcu_fetch($this->key($key)) : $default;
    }

    /**
     * Checks whether a cache key exists
     *
     * @param string $key Cache key
     * @param null   $ttl Time-to-live (only for File driver)
     *
     * @return bool
     */
    public function exists($key, $ttl = null)
    {
        return apcu_exists($this->key($key));
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
        return apcu_delete($this->key($key));
    }

    /**
     * Increment a cache key with $step
     *
     * @param string $key  Cache key
     * @param int    $step Increment step
     *
     * @return int Value after incrementation
     */
    public function inc($key, $step = 1)
    {
        return apcu_inc($this->key($key), $step);
    }

    /**
     * Decrement a cache key with $step
     *
     * @param string $key  Cache key
     * @param int    $step Increment step
     *
     * @return int Value after decrement
     */
    public function dec($key, $step = 1)
    {
        return apcu_dec($this->key($key), $step);
    }

    /**
     * Clears the cache
     *
     * @return bool
     */
    public function clear()
    {
        return apcu_clear_cache();
    }
}
