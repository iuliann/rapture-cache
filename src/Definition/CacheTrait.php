<?php

namespace Rapture\Cache\Definition;

/**
 * Cache adapter common methods
 *
 * @package Rapture\Cache
 * @author Iulian N. <rapture@iuliann.ro>
 * @license LICENSE MIT
 */
trait CacheTrait
{
    protected $config;

    /**
     * Cache Abstract
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config + [
            'namespace' =>  'app',
            'ttl'       =>  CacheInterface::ONE_MINUTE
        ];

        $this->init();
    }

    protected function init()
    {
        // init extra
    }

    /**
     * Sanitize cache key.
     * Allow alpha-numeric plus underscore, dash, dot and slash characters.
     *
     * @param string $key Key to sanitize
     *
     * @return string
     */
    public function key($key)
    {
        return preg_replace('/[^0-9a-zA-Z_\-\:]/', '_', "{$this->getNamespace()}-{$key}");
    }

    /**
     * multiDelete
     *
     * @param array $keys Keys to delete
     *
     * @return bool
     */
    public function multiDelete(array $keys)
    {
        foreach ($keys as $key) {
            if (is_array($key)) {
                $this->multiDelete($key);
            }
            else {
                $this->delete($key);
            }
        }

        return true;
    }

    /**
     * Get with callback
     *
     * @param string $key Key to get
     * @param mixed $callback Callback to call
     * @param int $ttl Time to live
     *
     * @return mixed
     */
    public function getCallback($key, $callback, $ttl = 0)
    {
        if (!$this->exists($key)) {
            $value = is_callable($callback)
                ? call_user_func($callback)
                : $callback;

            $this->set($key, $value, $ttl);

            return $value;
        }

        return $this->get($key);
    }

    /**
     * In case TTL is not set it will use the default value found in config.
     *
     * @param int $ttl Time-to-live
     *
     * @return int
     */
    public function getTtl($ttl):int
    {
        return (int)$ttl ?: (int)$this->config['ttl'];
    }

    public function getNamespace():string
    {
        return $this->config['namespace'];
    }
}
