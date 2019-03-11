<?php
namespace Nonda\Event;

trait StopableEventTrait
{
    /**
     * @var boolean
     */
    protected $stop = false;

    public function stop()
    {
        return $this->stop = true;
    }

    public function isStopped()
    {
        return $this->stop;
    }
}
