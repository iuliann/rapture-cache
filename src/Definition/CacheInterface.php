<?php

namespace Rapture\Cache\Definition;

/**
 * Cache driver interface class
 *
 * @package Rapture\Cache
 * @author  Iulian N. <rapture@iuliann.ro>
 * @license LICENSE MIT
 */
interface CacheInterface
{
    const ONE_MINUTE = 60;
    const ONE_HOUR   = 3600;    // 60 * 60
    const ONE_DAY    = 86400;   // 24 * 3600
    const ONE_WEEK   = 604800;  // 7 * 24 * 3600
    const TWO_WEEKS  = 1209600; // 14 * 24 * 3600
    const ONE_MONTH  = 2592000; // 30 * 24 * 3600
    const NO_LIMIT   = 0;

    /**
     * CacheInterface::set()
     *
     * @param string $key  Cache key
     * @param mixed  $data Cache data
     * @param int    $ttl  Time-to-live
     *
     * @return bool
     */
    public function set($key, $data = null, $ttl = null);

    /**
     * CacheInterface::get()
     *
     * @param string $key     Cache key
     * @param mixed  $default Default value to return if cache key doesn't exist
     * @param int    $ttl     Time-to-live
     *
     * @return mixed
     */
    public function get($key, $default = null, $ttl = null);

    /**
     * CacheInterface::exists()
     *
     * @param string $key Cache key
     * @param null   $ttl Time-to-live
     *
     * @return bool
     */
    public function exists($key, $ttl = null);

    /**
     * CacheInterface::delete()
     *
     * @param string $key Cache key
     *
     * @return bool
     */
    public function delete($key);

    /**
     * CacheInterface::inc()
     *
     * @param string $key  Cache key
     * @param int    $step Increment step
     *
     * @return int Value after incrementation
     */
    public function inc($key, $step = 1);

    /**
     * CacheInterface::dec()
     *
     * @param string $key  Cache key
     * @param int    $step Increment step
     *
     * @return int Value after decrement
     */
    public function dec($key, $step = 1);

    /**
     * CacheInterface::clear()
     *
     * @return bool
     */
    public function clear();
}
