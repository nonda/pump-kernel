<?php
namespace Nonda\Log;

class BaseLog extends AbstractLog
{
    public function debug($message, $context = [])
    {
        fwrite(STDERR, '[debug] ' . $message . "; context: " . json_encode($context) . "\n");
    }

    public function info($message, $context = [])
    {
        fwrite(STDERR, '[info] ' . $message . "; context: " . json_encode($context) . "\n");
    }

    public function warning($message, $context = [])
    {
        fwrite(STDERR, '[warning] ' . $message . "; context: " . json_encode($context) . "\n");
    }

    public function error($message, $context = [])
    {
        fwrite(STDERR, '[error] ' . $message . "; context: " . json_encode($context) . "\n");
    }
}
