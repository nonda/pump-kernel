<?php
namespace Nonda\Event;

use Nonda\Kernel\Kernel;

/**
 * Class Event
 * @package Nonda\Event
 *
 * @author Rivsen
 *
 * Kernel's common event data object
 */
class Event implements EventInterface
{
    use DelayableEventTrait;

    /**
     * @var Kernel
     */
    protected $kernel;

    /**
     * @var bool
     */
    protected $stop;

    /**
     * Event constructor.
     *
     * @param Kernel $kernel
     */
    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
        $this->stop = false;
    }

    /**
     * Get Kernel object
     *
     * @return Kernel
     */
    public function getKernel()
    {
        return $this->kernel;
    }

    /**
     * {@inheritdoc}
     */
    public function isStopped()
    {
        return $this->stop;
    }

    /**
     * {@inheritdoc}
     */
    public function stop()
    {
        $this->stop = true;

        return $this;
    }
}
