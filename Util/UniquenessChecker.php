<?php
namespace Nonda\Util;

use Nonda\Laravel\Proxy\Nonda;
use Predis\Client;

class UniquenessChecker
{
    protected $tableName;

    protected $lifeTime;

    public function __construct($tableName, $lifeTime)
    {
        $this->cache = Nonda::commonCache()->getClient();
        $this->tableName = $tableName;
        $this->lifeTime = $lifeTime;
    }

    public function set($code)
    {
        /** @var Client $cache */
        $cache = $this->cache;
        $time = time();
        $cache->zadd($this->tableName, [$code => $time]);
        $this->clean(0, ($time - $this->lifeTime));
    }

    public function check($code, $autoSet = false)
    {
        /** @var Client $cache */
        $cache = $this->cache;
        $time = time();
        $lifeTime = $this->lifeTime;
        $codeTime = $cache->zscore($this->tableName, $code);

        if ($codeTime && $codeTime > ($time - $lifeTime)) {
            return false;
        }

        if ($autoSet) {
            $this->set($code);
        }

        return true;
    }

    public function clean($min, $max)
    {
        $this->cache->zremrangebyscore($this->tableName, $min, $max);
    }
}
