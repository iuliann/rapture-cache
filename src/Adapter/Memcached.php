<?php

namespace Rapture\Cache\Adapter;

use Rapture\Cache\Definition\CacheInterface;
use Rapture\Cache\Definition\CacheTrait;

/**
 * Memcached cache adapter
 * Requires Memcached module to be installed
 *
 * @package Rapture\Cache
 * @author  Iulian N. <rapture@iuliann.ro>
 * @license LICENSE MIT
 */
class Memcached implements CacheInterface
{
    use CacheTrait;

    /**
     * @var \Memcached
     */
    protected $memcached;

    protected function init()
    {
        $this->memcached = new \Memcached($this->getNamespace());

        // Add server if no connections listed.
        if (!count($this->memcached->getServerList())) {
            foreach ($this->config['servers'] as $serverConfig) {
                $this->memcached->addServer($serverConfig[0], $serverConfig[1], $serverConfig[2]);
            }
        }
    }

    /**
     * getAdapter
     *
     * @return \Memcached
     */
    public function getAdapter()
    {
        return $this->memcached;
    }

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
        return $this->memcached->set($this->key($key), $data, $this->getTtl($ttl));
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
        $value = $this->memcached->get($this->key($key));
        $resultCode = $this->memcached->getResultCode();

        return $resultCode === \Memcached::RES_NOTFOUND
            ? $default
            : $value;
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
        return $this->memcached->get($this->key($key))
        || $this->memcached->getResultCode() !== \Memcached::RES_NOTFOUND;
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
        return $this->memcached->delete($this->key($key));
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
        return $this->memcached->increment($this->key($key), $step);
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
        return $this->memcached->decrement($this->key($key), $step);
    }

    /**
     * Clears the cache
     *
     * @return bool
     */
    public function clear()
    {
        return $this->memcached->flush();
    }
}
