<?php

namespace Rapture\Cache\Adapter;

use Rapture\Cache\Definition\CacheInterface;
use Rapture\Cache\Definition\CacheTrait;

/**
 * File cache adapter class
 * - namespace as directory
 * - TTL on set is not working
 * - TTL counts on get only
 *
 * @package Rapture\Cache
 * @author  Iulian N. <rapture@iuliann.ro>
 * @license LICENSE MIT
 */
class File implements CacheInterface
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
        // create dir if necessary
        $dir = dirname("{$this->getNamespace()}/{$key}");
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        return file_put_contents("{$this->getNamespace()}/{$key}", serialize($data), LOCK_EX) !== false;
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
        $ttl = $this->getTtl($ttl);

        if (is_readable("{$this->getNamespace()}/{$key}") && (filemtime("{$this->getNamespace()}/{$key}") + $ttl > time())) {
            return unserialize(file_get_contents("{$this->getNamespace()}/{$key}"));
        }

        $this->delete($key);

        return $default;
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
        $ttl = $this->getTtl($ttl);

        if (is_readable("{$this->getNamespace()}/{$key}")) {
            if (filemtime("{$this->getNamespace()}/{$key}") + $ttl > time()) {
                return true;
            } else {
                $this->delete($key);
            }
        }

        return false;
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
        return is_readable("{$this->getNamespace()}/{$key}")
            ? unlink("{$this->getNamespace()}/{$key}")
            : true;
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
        $value = $this->get($key, null) + $step;
        $this->set($key, $value);

        return $value;
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
        return $this->inc($key, -1 * $step);
    }

    /**
     * Clears the cache
     *
     * @return bool
     */
    public function clear()
    {
        return `rm -Rf {$this->getNamespace()}/*`;
    }
}
