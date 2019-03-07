<?php
namespace Nonda\Util;

use Nonda\Exception\BaseException;
use Predis\Client;

class ListUtil
{
    /**
     * @var Client
     */
    protected $cache;

    public function __construct($cache)
    {
        $this->cache = $cache;
    }

    public function keyExists($list, $key)
    {
        return $this->cache->hexists((string)$list, $key);
    }

    public function listExists($list)
    {
        return $this->cache->exists((string)$list);
    }

    public function getList($list)
    {
        return $this->cache->hgetall((string)$list) ?: [];
    }

    public function addList($list, $data = null)
    {
        $list = (string)$list;

        if ($this->listExists($list)) {
            throw new BaseException('List is already exists, you need check exists before add');
        }

        if (!$data) {
            return true;
        }

        return $this->cache->hmset($list, $data);
    }

    public function getKey($list, $key)
    {
        $list = (string)$list;

        return $this->cache->hget($list, $key);
    }

    public function setKey($list, $key, $val)
    {
        $list = (string)$list;

        return $this->cache->hset($list, $key, $val);
    }

    public function delKey($list, $key)
    {
        $list = (string)$list;
        $key = is_array($key) ? $key : [$key];

        return $this->cache->hdel($list, $key);
    }

    public function delList($list)
    {
        return $this->cache->del($list);
    }
}
