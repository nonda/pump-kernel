<?php
namespace Nonda\Log;

abstract class AbstractLog
{
    abstract public function info($message, $context = []);

    abstract public function debug($message, $context = []);

    abstract public function warning($message, $context = []);

    abstract public function error($message, $context = []);
}
