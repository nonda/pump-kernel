<?php
namespace Nonda\Event;

use Nonda\Events;
use Nonda\Exception\BaseException;
use Nonda\Kernel\Kernel;
use Nonda\Logger\EventContextLogger;

/**
 * Class Dispatcher
 * @package Nonda\Event
 *
 * @author Rivsen
 *
 * Default dispatcher for Kernel's event dispatcher
 *
 * You can override this by writing yourself's dispatcher when construct Kernel
 */
class Dispatcher implements DispatcherInterface
{
    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getListenerTagName()
    {
        return 'kernel.event_listener';
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getSubscriberTagName()
    {
        return 'kernel.event_subscriber';
    }

    /**
     * @var Kernel
     */
    protected $kernel;

    public function setKernel(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

    public function getListeners($eventName)
    {
        $listeners = array_filter($this->kernel->getTag($this->getListenerTagName()), function ($tag) use ($eventName) {
            if ('service' == $tag['type'] AND !isset($tag['method'])) {
                throw new BaseException('Event listener service must give a method to call!', BaseException::EVENT_NO_CALLABLE_METHOD);
            }

            if ($eventName == $tag['event']) {
                return true;
            }

            return false;
        });

        foreach ($this->kernel->getTag($this->getSubscriberTagName()) as $tag) {
            if ('callback' == $tag['type']) {
                throw new BaseException('Event subscriber must be a class and has a public static method named getSubscribedEvents!', BaseException::EVENT_SUBSCRIBER_NO_STATIC_METHOD);
            }

            $callable = [$this->kernel->getServiceClass($tag['id']), 'getSubscribedEvents'];

            if (!is_callable($callable)) {
                throw new BaseException('Event subscriber must define a public static method named getSubscribedEvents!', BaseException::EVENT_SUBSCRIBER_NO_STATIC_METHOD);
            }

            $events = call_user_func($callable);

            if (!isset($events[$eventName])) {
                continue;
            }

            $events = $events[$eventName];

            if (!is_string($events) AND !is_array($events)) {
                throw new BaseException('Event subscriber\'s getSubscribedEvents method must return a valid array!', BaseException::EVENT_SUBSCRIBER_STATIC_METHOD_RETURN_INVALID);
            }

            /**
             * Event::SOME_EVENT => 'someMethod'
             */
            if (is_string($events)) {
                if (!is_callable([$callable[0], $events])) {
                    throw new BaseException("service's method is not callable: {$events}", BaseException::EVENT_NO_CALLABLE_METHOD);
                }

                $tag['method'] = $events;
                $listeners[] = $tag;

                continue;
            }

            foreach ($events as $subsEvent) {
                $listener = $tag;

                if (is_string($subsEvent)) {
                    $listener['method'] = $subsEvent;
                } elseif (is_array($subsEvent)) {
                    foreach ($subsEvent as $ekey => $eval) {
                        if (in_array($ekey, ['id', 'name', 'type'])) {
                            continue;
                        }

                        $listener[$ekey] = $eval;
                    }
                }

                $listeners[] = $listener;
            }
        }

        if (!empty($listeners)) {
            $listeners = $this->sortListeners($listeners);
        }

        return $listeners;
    }

    public function sortListeners(array $listeners)
    {
        if (empty($listeners)) {
            return $listeners;
        }

        usort($listeners, function($listener1, $listener2) {
            $priority1 = isset($listener1['priority']) ? $listener1['priority'] : 0;
            $priority2 = isset($listener2['priority']) ? $listener2['priority'] : 0;

            if ($priority1 > $priority2) {
                return -1;
            }

            if ($priority1 < $priority2) {
                return 1;
            }

            return 0;
        });

        return $listeners;
    }

    /**
     * Dispatch a event
     *
     * @param string         $name
     * @param EventInterface $event
     *
     * @return EventInterface
     * @throws BaseException
     */
    public function dispatch($name, EventInterface $event)
    {
        $printLog = $this->kernel->getService('event_context.logger')->isEnabled();
        $tagServices = $this->getListeners($name);

        if (count($tagServices) === 0) {
            return $event;
        }

        if ($printLog) {
            $prevStep = $start = (int)(microtime(true) * 1000);
            /** @var EventContextLogger $logger */
            $logger = $this->kernel->getService('event_context.logger');
            $logger->logListener($name, 'event-start', 0);
        }

        foreach ($tagServices as $tagService) {
            try {
                $event = $this->doDispatch($tagService, $name, $event);
            } catch (\Exception $e) {
                if ($printLog) {
                    $step = (int)(microtime(true) * 1000);

                    $logger->logListener(
                        $name,
                        $tagService['id'] . '::' . $tagService['method'],
                        $step - $prevStep,
                        '[' . $e->getCode() . ']' . $e->getMessage()
                    );

                    $logger->logListener($name, 'event-end', $step - $start);
                }

                throw $e;
            }

            if ($printLog) {
                $step = (int)(microtime(true) * 1000);

                if ($step !== $prevStep) {
                    $logger->logListener($name, $tagService['id'] . '::' . $tagService['method'], $step - $prevStep);
                }

                $prevStep = $step;
            }

            if ($event->isStopped()) {
                break;
            }
        }

        if ($printLog) {
            $step = (int)(microtime(true) * 1000);
            $logger->logListener($name, 'event-end', $step - $start);
        }

        return $event;
    }

    /**
     * @param array          $tagService
     * @param string         $eventName
     * @param EventInterface $event
     *
     * @return EventInterface
     * @throws BaseException
     */
    protected function doDispatch($tagService, $eventName, EventInterface $event)
    {
        switch ($tagService['type']) {
            case 'service':
                $service = $this->kernel->getService($tagService['id']);

                call_user_func([$service, $tagService['method']], $event, $eventName, $tagService);
                break;

            case 'callback':
                call_user_func($tagService['callback'], $event, $eventName, $tagService);
                break;

            default:
                throw new BaseException(sprintf('Unknown tag type: "%s".', $tagService['type']));
        }

        return $event;
    }

}
