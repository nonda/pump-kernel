<?php

namespace Nonda\Logger;

use Monolog\Formatter\FormatterInterface;
use Nonda\Kernel\Kernel;

class EventContextLogger extends ContextLogger
{
    protected $type;

    public function __construct(Kernel $kernel, FormatterInterface $formatter, $logPath = null)
    {
        $this->type = ContextLogger::TYPE_EVENT;
        $this->enabledConfigKey = 'event_context_enabled';
        parent::__construct($kernel, $formatter, $logPath);
    }

    public function getLogPath($logPath = '')
    {
        if (!$logPath) {
            if ($this->logPath) {
                return $this->logPath;
            }

           return $this->kernel->getParameter('logger.path')
                . DIRECTORY_SEPARATOR . 'event-context-'.date('Y-m-d').'.log';
        }

        return parent::getLogPath($logPath);
    }

    public function formatLog($eventName, $action, $duration = 0, $message = '')
    {
        $log = sprintf('%s %s %d', $eventName, $action, $duration);

        if ($message) {
            $log .= ' ' . $message;
        }

        return $log;
    }

    public function logListener($eventName, $serviceId, $duration, $message = '')
    {
        if (!$this->isEnabled()) {
            return true;
        }

        return $this->log($this->type, $this->formatLog($eventName, $serviceId, $duration, $message));
    }
}
