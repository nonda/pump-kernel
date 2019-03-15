<?php
namespace Nonda\Redis;

use Nonda\Kernel\Kernel;
use Predis\Client;

class Manager
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var Kernel
     */
    protected $kernel;

    /**
     * @var Client
     */
    protected $client;

    public function __construct($config, Kernel $kernel)
    {
        $this->kernel = $kernel;
        $this->config = $config;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        if (!$this->client) {
            $this->client = $this->createClient($this->config);
        }

        return $this->client;
    }

    /**
     * @param array $config
     *
     * @return Client
     */
    public function createClient($config)
    {
        return new Client($config['dsn'], $config['options']);
    }
}
