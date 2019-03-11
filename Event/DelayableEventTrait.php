<?php
namespace Nonda\Event;

trait DelayableEventTrait
{
    /**
     * @var int
     */
    protected $runAt;

    public function delaySec($seconds)
    {
        $this->runAt = time() + $seconds;

        return $this;
    }

    public function delayedTo($time)
    {
        $this->runAt = $time;

        return $this;
    }

    public function runAt()
    {
        if (!$this->runAt) {
            return 0;
        }

        if ($this->runAt <= time()) {
            return 0;
        }

        return $this->runAt;
    }
}
