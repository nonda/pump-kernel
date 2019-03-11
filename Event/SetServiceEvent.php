<?php
namespace Nonda\Event;

use Nonda\Kernel\Kernel;

/**
 * Class SetServiceEvent
 * @package Nonda\Event
 *
 * @author Rivsen
 *
 * Event object for kernel's set service event
 */
class SetServiceEvent extends Event
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var object|\Closure
     */
    protected $service;

    /**
     * SetServiceEvent constructor.
     *
     * @param Kernel $kernel
     * @param string $id
     */
    public function __construct(Kernel $kernel, $id)
    {
        parent::__construct($kernel);

        $this->id = $id;
        $this->service = $kernel->getService($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getKernel()
    {
        return $this->kernel;
    }

    /**
     * Get the service's id
     *
     * @return string
     */
    public function getServiceId()
    {
        return $this->id;
    }

    /**
     * Get the service
     *
     * @return \Closure|object
     */
    public function getService()
    {
        return $this->service;
    }
}
