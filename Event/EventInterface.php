<?php
namespace Nonda\Event;

/**
 * Interface EventInterface
 * @package Nonda\Event
 *
 * @author Rivsen
 *
 * You can implement this interface for some events
 */
interface EventInterface
{
    /**
     * Check is Event is stopped
     *
     * @return bool
     */
    public function isStopped();

    /**
     * Stop the event
     *
     * @return EventInterface
     */
    public function stop();

    /**
     * 延迟多少秒执行，延迟起点是调用此方法的时间
     *
     * @param int $seconds
     *
     * @return $this
     */
    public function delaySec($seconds);

    /**
     * 延迟到某个时间执行
     *
     * @param int $time
     *
     * @return $this
     */
    public function delayedTo($time);

    /**
     * 获取执行时间点，默认为0表示当前时间
     *
     * @return int
     */
    public function runAt();
}
