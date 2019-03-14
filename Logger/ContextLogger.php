<?php

namespace Nonda\Logger;

use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Nonda\Kernel\Kernel;

class ContextLogger
{
    const TYPE_EVENT = 'event';

    const TYPE_DB_QUERY = 'db_query';

    /**
     * @var Kernel
     */
    protected $kernel;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var HandlerInterface
     */
    protected $handler;

    /**
     * @var FormatterInterface
     */
    protected $formatter;

    /**
     * @var boolean
     */
    protected $enabled;

    /**
     * @var string
     */
    protected $enabledConfigKey;

    public function __construct(Kernel $kernel, FormatterInterface $formatter)
    {
        $this->kernel = $kernel;
        $this->formatter = $formatter;

        if (!$this->enabledConfigKey) {
            $this->enabledConfigKey = 'default_context_enabled';
        }
    }

    /**
     * 是否开启此log
     *
     * @return boolean
     */
    public function isEnabled()
    {
        // 暂时直接返回true
        return true;
    }

    public function getLogPath($logPath = '')
    {
        if (!$logPath) {
            $logPath = $this->kernel->getParameter('logger.path')
                . DIRECTORY_SEPARATOR . 'context-'.date('Y-m-d').'.log';
        }

        return $logPath;
    }

    public function getHandler($logPath = '')
    {
        if (!$this->handler) {
            $this->handler = new StreamHandler($this->getLogPath($logPath), Logger::ERROR);
            $this->handler->setFormatter($this->formatter);
        }

        return $this->handler;
    }

    public function getLogger()
    {
        if (!$this->logger) {
            $this->logger = new Logger('pump_context', [$this->getHandler()]);
        }

        return $this->logger;
    }

    public function log($type, $data)
    {
        if (!$this->isEnabled()) {
            return true;
        }

        if (is_array($data)) {
            $data = implode(' ', $data);
        }

        $this->getLogger()
            ->error(($this->kernel->getEnv() ?: 'local') . " {$this->kernel->getContextUuid()} {$type} {$data}");

        return true;
    }
}
