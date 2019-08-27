<?php
namespace Nonda\Kernel;

use Nonda\Event\Dispatcher;
use Nonda\Event\DispatcherInterface;
use Nonda\Event\Event;
use Nonda\Events;
use Nonda\Exception\BaseException;
use Nonda\Log\BaseLog;
use Ramsey\Uuid\Uuid;

/**
 * Class Kernel
 * @package Nonda\Kernel
 *
 * @author Rivsen
 *
 * Nonda PHP micro framework kernel
 */
class Kernel
{
    /**
     * The kernel can have three possible behaviors when a service does not exist:

     * EXCEPTION_ON_INVALID_SERVICE_REFERENCE: Throws an exception (the default)
     * NULL_ON_INVALID_SERVICE_REFERENCE:      Returns null
     * IGNORE_ON_INVALID_SERVIC_REFERENCE:    Ignores the wrapping command asking for the reference
     *
     */
    const EXCEPTION_ON_INVALID_SERVICE_REFERENCE = 1;
    const NULL_ON_INVALID_SERVICE_REFERENCE = 2;
    const IGNORE_ON_INVALID_SERVICE_REFERENCE = 3;

    // local env, for docker
    const ENV_LOCAL = 'local';

    // prod cron env
    const ENV_CRON = 'cron';

    // prod env
    const ENV_PROD = 'production';

    // test env
    const ENV_TEST = 'test';

    // farm env
    const ENV_FARM = 'farm';

    // dev env
    const ENV_DEV = 'dev';

    /**
     * prod 环境包括的具体服务运行环境列表
     *
     * @var array
     */
    public static $prodEnv = [
        self::ENV_PROD,
        self::ENV_CRON,
        self::ENV_FARM,
    ];

    /**
     * Kernel's name
     *
     * @var string
     */
    protected $name;

    /**
     * Environment
     *
     * @var string
     */
    protected $env;

    /**
     * Services container
     *
     * @var array
     */
    protected $services;

    /**
     * Lazy load raw service definitions
     *
     * @var array
     */
    protected $lazyServiceDefinitions;

    /**
     * All registered tags
     *
     * @var array
     */
    protected $tags;

    /**
     * All Services' tags
     *
     * @var array
     */
    protected $serviceTags;

    /**
     * @var array
     */
    protected $config = [];

    /**
     * 运行周期唯一标识
     *
     * @var string
     */
    protected $contextUuid;

    /**
     * 事件列表
     *
     * eventName => eventDesc
     *
     * @var array
     */
    protected $events = [];

    /**
     * 事件分组列表
     *
     * groupName => groupDesc
     *
     * @var array
     */
    protected $eventGroups = [];

    /**
     * 事件分组映射
     *
     * eventName => eventGroup
     *
     * @var array
     */
    protected $eventGroupMappings = [];

    /**
     * Kernel constructor.
     *
     * @param string $appName           Kernel app's name
     * @param string $env               environment
     * @param array  $config            Kernel's Configuration
     * @param array  $services          init with existing services
     *
     * @throws BaseException
     */
    public function __construct($appName = 'Nonda', $env = 'dev', $config = [], $services = [])
    {
        $this->name = $appName;
        $this->env = $env;
        $this->config = $config;
        $this->lazyServiceDefinitions = [];
        $this->services = $services;
        $this->tags = [];
        $this->serviceTags = [];
        /** @var array $lazyServiceDefinitions   lazy init services */
        $lazyServiceDefinitions = isset($config['services']) ? $config['services'] : [];

        if (!$this->getParameter('logger.path')) {
            $this->setParameter('logger.path', sys_get_temp_dir());
        }

        // 把kernel默认的事件加到列表中
        $this->addEvents(Events::$events, Events::$groups, Events::$eventGroups);

        $this->lazyInitServices($lazyServiceDefinitions);

        if (!$this->hasService('kernel.event_dispatcher')) {
            $this->initService('kernel.event_dispatcher', Dispatcher::class);
        }

        /** @var DispatcherInterface $dispatcher */
        $dispatcher = $this->getService('kernel.event_dispatcher');
        $dispatcher->setKernel($this);

        /** 初始化默认的logger */
        if (!$this->hasService('logger')) {
            $this->initService('logger', BaseLog::class);
        }
    }

    public function getContextUuid()
    {
        if (!$this->contextUuid) {
            $this->contextUuid = (string)Uuid::uuid4();
        }

        return $this->contextUuid;
    }

    public function setContextUuid($contextUuid)
    {
        $this->contextUuid = $contextUuid;

        return $this;
    }

    public function getConfig($key = null)
    {
        if ($key) {
            return $this->config[$key] ?? null;
        }

        return $this->config;
    }

    public function getParameters()
    {
        return isset($this->config['parameters']) ? $this->config['parameters'] : [];
    }

    public function getParameter($name)
    {
        $parameters = $this->getParameters();

        return isset($parameters[$name]) ? $parameters[$name] : null;
    }

    public function setParameter($name, $value)
    {
        if (!isset($this->config['parameters'])) {
            $this->config['parameters'] = [];
        }

        $this->config['parameters'][$name] = $value;

        return $this;
    }

    /**
     * Get Kernel's name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Checks if debug mode is enabled
     *
     * @return bool
     */
    public function isDebug()
    {
        return !in_array($this->env, self::$prodEnv);
    }

    public function getEnv()
    {
        return $this->env;
    }

    /**
     * 检查当前env是否在prod环境
     *
     * @return bool
     */
    public function isProdEnv() {
        return in_array($this->getEnv(), self::$prodEnv);
    }

    /**
     * Boot Kernel
     */
    public function boot()
    {
        $this->getService('kernel.event_dispatcher')->dispatch(Events::KERNEL_BOOT, new Event($this));

        return $this;
    }

    /**
     * Terminate Kernel
     *
     * @return Event
     */
    public function terminate()
    {
        $dispatcher = $this->getService('kernel.event_dispatcher');
        $event = new Event($this);

        $dispatcher->dispatch(Events::KERNEL_TERMINATE, $event);

        return $event;
    }

    /**
     * Add a tag for exist service
     *
     * @param string $tag
     * @param string $id
     * @param array  $extraParams
     *
     * @return Kernel
     * @throws BaseException
     */
    public function addServiceTag($tag, $id, $extraParams = [])
    {
        if (!$this->hasService($id)) {
            throw new BaseException(sprintf('You have requested a non-existent service "%s".', $id));
        }

        $tag = (string)$tag;

        if (!isset($this->tags[$tag])) {
            $this->tags[$tag] = [];
        }

        if (!isset($extraParams['priority'])) {
            $extraParams['priority'] = 0;
        }

        $this->tags[$tag][] = array_merge($extraParams, [
            'type' => 'service',
            'id' => $id,
        ]);

        $this->serviceTags[$id][] = array_merge($extraParams, [
            'name' => $tag,
        ]);

        end($this->tags[$tag]);
        $tagIndex = key($this->tags[$tag]);

        end($this->serviceTags[$id]);
        $serviceTagIndex = key($this->serviceTags[$id]);

        $this->tags[$tag][$tagIndex]['serviceTagIndex'] = $serviceTagIndex;
        $this->serviceTags[$id][$serviceTagIndex]['tagIndex'] = $tagIndex;

        return $this;
    }

    /**
     * 对tag中的service按照优先级排序
     *
     * @param array $tag
     *
     * @return array
     */
    protected function sortTags(array $tag)
    {
        if (empty($tag)) {
            return $tag;
        }

        $newTag = [];
        $itemPriority = [];

        foreach ($tag as $idx => &$item) {
            if (!isset($item['priority'])) {
                $item['priority'] = 0;
            }

            if ($item['priority'] > 255) {
                $item['priority'] = 255;
            }

            if ($item['priority'] < -255) {
                $item['priority'] = -255;
            }

            $itemPriority[$idx] = $item['priority'];
        }

        unset($item);

        arsort($itemPriority);
        $i = 0;

        foreach ($itemPriority as $idx => $priority) {
            $newTag[$i] = $tag[$idx];

            if (isset($tag[$idx]['id'], $tag[$idx]['serviceTagIndex']) && $tag[$idx]['serviceTagIndex'] > 0) {
                $this->serviceTags[$tag[$idx]['id']][$tag[$idx]['serviceTagIndex']]['tagIndex'] = $i;
            }

            $i++;
        }

        return $newTag;
    }

    /**
     * Remove a service's tag from the service tag list
     *
     * @param string  $id
     * @param integer $serviceTagIndex
     *
     * @return bool
     */
    public function removeServiceTag($id, $serviceTagIndex)
    {
        if (isset($this->serviceTags[$id])) {
            if (isset($this->serviceTags[$id][$serviceTagIndex])) {
                $tag = $this->serviceTags[$id][$serviceTagIndex];

                unset($this->tags[$tag['name']][$tag['tagIndex']]);
                unset($this->serviceTags[$id][$serviceTagIndex]);
            }
        }

        return true;
    }

    /**
     * Add a callback for a tag
     *
     * If $id is not given, then this callback will only known in tags record
     *
     * @param string   $tag
     * @param \Closure $callback
     * @param array    $extraParams
     * @param string   $id
     *
     * @return Kernel
     * @throws BaseException
     */
    public function addCallbackTag($tag, \Closure $callback, $extraParams = [], $id = null)
    {
        if ($id AND !$this->hasService($id)) {
            throw new BaseException(sprintf('You have requested a non-existent service "%s".', $id));
        }

        if (!isset($this->tags[$tag])) {
            $this->tags[$tag] = [];
        }

        if (!isset($extraParams['priority'])) {
            $extraParams['priority'] = 0;
        }

        $this->tags[$tag][] = array_merge($extraParams, [
            'type' => 'callback',
            'callback' => $callback,
        ]);

        end($this->tags[$tag]);
        $tagIndex = key($this->tags[$tag]);
        $serviceTagIndex = -1;

        if ($id) {
            $this->serviceTags[$id][] = array_merge($extraParams, [
                'name' => $tag,
            ]);

            end($this->tags[$tag]);
            $tagIndex = key($this->tags[$tag]);

            end($this->serviceTags[$id]);
            $serviceTagIndex = key($this->serviceTags[$id]);

            $this->serviceTags[$id][$serviceTagIndex]['tagIndex'] = $tagIndex;
        }

        /**
         * Set service tag index to -1 if callback is not a service
         */
        $this->tags[$tag][$tagIndex]['serviceTagIndex'] = $serviceTagIndex;

        return $this;
    }

    /**
     * Get a tag record
     *
     * @param string $tag
     *
     * @return array
     */
    public function getTag($tag)
    {
        if (!isset($this->tags[$tag])) {
            $this->tags[$tag] = [];
        }

        return $this->tags[$tag];
    }

    public function getServiceClass($id)
    {
        if (!$this->hasService($id)) {
            throw new BaseException(sprintf('You have requested a non-existent service "%s".', $id));
        }

        $id = strtolower($id);

        if ('kernel' == $id) {
            return self::class;
        }

        if (isset($this->lazyServiceDefinitions[$id])) {
            $def = $this->lazyServiceDefinitions[$id];

            return $def['class'];
        }

        if (isset($this->services[$id])) {
            return get_class($this->services[$id]);
        }

        throw new BaseException(sprintf('You have requested a non-existent service "%s".', $id));
    }

    /**
     * Set a Service
     *
     * Setting a service to null resets the service: hasService() returns false
     * and getService() behaves in the same way as if the service was never created
     *
     * @param string          $id      the service id
     * @param object|\Closure $service the service instance
     * @param array           $tags    the service tags
     *
     * @return Kernel
     * @throws BaseException
     */
    public function setService($id, $service, $tags = [])
    {
        $id = strtolower($id);

        if ('kernel' == $id) {
            throw new BaseException('You cannot set service "kernel".', BaseException::INVALID_ARGUMENT);
        }

        if(null === $service) {
            $this->removeService($id);

            return $this;
        }

        if (isset($this->services[$id])) {
            $this->removeService($id);
        }

        $needSetTags = true;

        /**
         * Tag is already set in lazy initialize
         */
        if ($this->hasService($id)) {
            //throw new BaseException(sprintf('You cannot override uninitialized service "%s" tags.', $id));
            $needSetTags = false;
        }

        $this->services[$id] = $service;

        if ($needSetTags) {
            $this->serviceTags[$id] = [];

            if ($service instanceof \Closure) {
                foreach ($tags as $tag) {
                    $this->addCallbackTag($tag['name'], $service, $tag);
                }
            } else {
                foreach ($tags as $tag) {
                    $this->addServiceTag($tag['name'], $id, $tag);
                }
            }
        }

        return $this;
    }

    /**
     * Remove one service from all associations
     *
     * @param string $id
     *
     * @return bool
     */
    public function removeService($id)
    {
        if (isset($this->serviceTags[$id])) {
            foreach ($this->serviceTags[$id] as $serviceTagIndex => $serviceTag) {
                $this->removeServiceTag($id, $serviceTagIndex);
            }
        }

        if (isset($this->lazyServiceDefinitions[$id])) {
            unset($this->lazyServiceDefinitions[$id]);
        }

        if (isset($this->services[$id])) {
            unset($this->services[$id]);
        }

        return true;
    }

    /**
     * Remove service instance
     *
     * @param string $id
     *
     * @return bool
     */
    public function removeServiceInstance($id)
    {
        if (isset($this->services[$id])) {
            unset($this->services[$id]);
        }

        return true;
    }

    /**
     * Return true if the given service is defined
     *
     * @param string $id
     *
     * @return bool
     */
    public function hasService($id)
    {
        $id = strtolower($id);

        if ('kernel' == $id OR isset($this->services[$id])) {
            return true;
        }

        if (isset($this->lazyServiceDefinitions[$id])) {
            return true;
        }

        return false;
    }

    /**
     * Get a service
     *
     * @param string $id
     * @param int    $invalidBehavior
     *
     * @return mixed
     * @throws BaseException
     */
    public function getService($id, $invalidBehavior = self::EXCEPTION_ON_INVALID_SERVICE_REFERENCE)
    {
        $id = strtolower($id);

        if ('kernel' == $id) {
            return $this;
        }

        if (!isset($this->services[$id])) {
            if (!isset($this->lazyServiceDefinitions[$id])) {
                if (self::EXCEPTION_ON_INVALID_SERVICE_REFERENCE == $invalidBehavior) {
                    throw new BaseException(sprintf('You have requested a non-existent service "%s".', $id));
                }

                if (self::NULL_ON_INVALID_SERVICE_REFERENCE == $invalidBehavior) {
                    return null;
                }

                return;
            }

            $this->initService(
                $id,
                $this->lazyServiceDefinitions[$id]['class'],
                @$this->lazyServiceDefinitions[$id]['arguments'],
                @$this->lazyServiceDefinitions[$id]['tags'],
                @$this->lazyServiceDefinitions[$id]['calls'],
                $this->lazyServiceDefinitions
            );
        }

        return $this->services[$id];
    }

    /**
     * clear all defined services
     *
     * @return Kernel
     */
    public function resetServices()
    {
        $this->services = [];
        $this->lazyServiceDefinitions = [];
        $this->serviceTags = [];
        $this->tags = [];

        return $this;
    }

    /**
     * get all service ids
     *
     * @return array
     */
    public function getServiceIds()
    {
        return array_unique(array_merge(
            ['kernel'],
            array_keys($this->services),
            array_keys($this->lazyServiceDefinitions)
        ));
    }

    /**
     * lazy init the given services
     *
     * eg:
     * lazyInitServices([
     *     'foo' => [
     *         'class' => Some\Foo::class,
     *         'arguments' => [
     *             $firstArgument,
     *             $secondArgument,
     *         ],
     *         'tags' => [
     *             ['name' => 'kernel.event_listener', 'method' => 'getName'],
     *         ],
     *     ],
     *     'bar' => [
     *         'class' => Some\Bar::class,
     *         'arguments' => [
     *             $firstArgument,
     *             '@foo',
     *         ],
     *     ],
     * ])
     *
     * $kernel->getService('foo')
     *     => SomeFoo($firstArgument, $secondArgument)
     *
     * $kernel->getService('bar')
     *     => SomeBar($firstArgument, SomeFoo($firstArgument, $secondArgument))
     *
     * @param array $definitions
     *
     * @return Kernel
     */
    public function lazyInitServices($definitions = [])
    {
        $this->lazyServiceDefinitions = array_merge($this->lazyServiceDefinitions, $definitions);

        foreach ($definitions as $id => $definition) {
            if (isset($definition['tags'])) {
                foreach ($definition['tags'] as $tag) {
                    $this->addServiceTag($tag['name'], $id, $tag);
                }
            }
        }

        return $this;
    }

    /**
     * @param array $definitions
     *
     * @return Kernel
     */
    public function initServices($definitions = [])
    {
        $this->lazyServiceDefinitions = array_merge($this->lazyServiceDefinitions, $definitions);

        foreach ($definitions as $definition => $params) {
            $class = $params['class'];

            $this->initService(
                $definition,
                $class,
                @$params['arguments'],
                @$params['tags'],
                @$params['calls'],
                $this->lazyServiceDefinitions
            );
        }

        return $this;
    }

    /**
     * Init a service
     *
     * @param string $id
     * @param string $class
     * @param array  $arguments
     * @param array  $tags
     * @param array  $calls
     * @param array  $definitions
     *
     * @return object
     * @throws BaseException
     */
    public function initService($id, $class, $arguments = [], $tags = [], $calls = [], $definitions = [])
    {
        /**
         * Get Service's Reflection
         */
        try {
            $reflection = new \ReflectionClass($class);
        } catch (\ReflectionException $e) {
            $class = '\\'.$class;
            $reflection = new \ReflectionClass($class);
        }

        /**
         * Get Service's Arguments
         */
        $tags = is_array($tags) ? $tags : [];
        $arguments = $this->parseServiceArguments($id, $arguments, $definitions);
        $calls = is_array($calls) ? $calls : [];

        $service = $reflection->newInstanceArgs($arguments);

        /**
         * Set Service
         */
        $this->setService($id, $service, $tags);

        /**
         * Call Service's configuration calls
         */
        foreach ($calls as $call) {
            $method = isset($call[0]) ? $call[0] : (isset($call['method']) ? $call['method'] : null);

            if (!is_callable([$service, $method])) {
                throw new BaseException(sprintf('Call to undefined method on service: "%s"::"%s".', $id, $method));
            }

            $callArguments = isset($call[1]) ? $call[1] : (isset($call['arguments']) ? $call['arguments'] : null);
            $callArguments = $this->parseServiceArguments($id, $callArguments, $definitions);

            call_user_func_array([$service, $method], $callArguments);
        }

        return $service;
    }

    protected function parseServiceArguments($id, $arguments, $definitions = [])
    {
        $arguments = $arguments ?: [];
        $arguments = is_array($arguments) ? $arguments : [];

        foreach ($arguments as &$argument) {
            if (is_string($argument) AND substr($argument, 0, 1) == '@') {
                $relatedServiceId = substr($argument, 1);

                $relatedService = $this->hasService($relatedServiceId) ? $this->getService($relatedServiceId) : null;

                if (!$relatedService AND !isset($definitions[$relatedServiceId])) {
                    throw new BaseException(
                        sprintf('The service "%s" has a dependency on a non-existent service "%s".', $id, $relatedServiceId),
                        BaseException::INVALID_ARGUMENT
                    );
                }

                if (!$relatedService) {
                    $relatedServiceDef = $definitions[$relatedServiceId];
                    $relatedService = $this->initService(
                        $relatedServiceId,
                        $relatedServiceDef['class'],
                        @$relatedServiceDef['arguments'],
                        @$relatedServiceDef['tags'],
                        @$relatedServiceDef['calls'],
                        $definitions
                    );
                }

                $argument = $relatedService;
            }
        }

        reset($arguments);

        return $arguments;
    }

    public function addEvent($eventName, $eventGroup = '', $eventDesc = '', $eventGroupDesc = '')
    {
        if (!$eventName) {
            throw new BaseException('event name is empty', BaseException::INVALID_ARGUMENT);
        }

        if (!$eventGroup) {
            $eventGroup = Events::GROUP_DEFAULT;
        }

        $this->events[$eventName] = $eventDesc;
        $this->eventGroups[$eventGroup] = $eventGroupDesc;
        $this->eventGroupMappings[$eventName] = $eventGroup;

        return $this;
    }

    public function addEvents($events, $groups = [], $eventGroupMappings = [])
    {
        foreach ($events as $event => $eventDesc) {
            $group = isset($eventGroupMappings[$event]) ? $eventGroupMappings[$event] : Events::GROUP_DEFAULT;
            $groupDesc = '';

            if (isset($groups[$group])) {
                $groupDesc = $groups[$group];
            }

            if (!$groupDesc) {
                if (isset($this->eventGroups[$group])) {
                    $groupDesc = $this->eventGroups[$group];
                } else {
                    $groupDesc = '';
                }
            }

            $this->addEvent($event, $group, $eventDesc, $groupDesc);
        }

        return $this;
    }
}
