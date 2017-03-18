<?php

class CacheTest extends \PHPUnit_Framework_TestCase
{
    public function testMemory()
    {
        $cache = new \Rapture\Cache\Adapter\Memory(['namespace' => 'one']);

        $cache->set('name', 'John');

        $cache2 = new \Rapture\Cache\Adapter\Memory(['namespace' => 'two']);

        $this->assertEquals('John', $cache->get('name'));
        $this->assertEquals(null, $cache2->get('name'));
    }
}
