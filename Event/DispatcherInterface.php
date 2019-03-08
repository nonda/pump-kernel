<?php
namespace Nonda\Event;

use Nonda\Kernel\Kernel;

/**
 * Interface DispatcherInterface
 * @package Nonda\Event
 *
 * @author Rivsen
 *
 * You can implement this interface for event dispatcher, like AsyncDispatcher
 */
interface DispatcherInterface
{
    /**
     * 获取监听类tag名称
     *
     * @return string
     */
    public function getListenerTagName();

    /**
     * 获取订阅类tag名称
     *
     * @return string
     */
    public function getSubscriberTagName();

    /**
     * @param $name
     * @param EventInterface $event
     * @return mixed
     */
    public function dispatch($name, EventInterface $event);

    /**
     * @param Kernel $kernel
     * @return DispatcherInterface
     */
    public function setKernel(Kernel $kernel);

    /**
     * @param string $eventName
     * @return array
     */
    public function getListeners($eventName);
}
