# pump-kernel
Nonda pump php kernel

## init
```bash
cd /path/to/project
./init.sh
```

## require

composer.json

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/nonda/pump-kernel"
        }
    ],
    "require": {
        "nonda/kernel": "dev-master"
    }
}
```

## usage

example

```php
<?php

// 定义一个Subscriber
class Subscriber {
    
    public function __construct(\Nonda\Kernel\Kernel $kernel)
    {
        
    }

    public function getSubscribedEvents()
    {
        return [
            'test' => [
                [
                    'method' => 'test',
                    // 异步应该扩展Dispatcher实现
                    'async' => true,
                ]
            ]
        ];
    }

    public function test(\Nonda\Event\EventInterface $event)
    {
        echo 'hello world';
    }
}

// 初始化kernel
$kernel = new Nonda\Kernel\Kernel(
    'test',
    'prod',
    [
        // 日志路径
        'logger.path' => '/Users/admin/Code/composer',
        'services' => [
            // 自定义Dispatcher，class实现Event\DispatcherInterface
            // 'kernel.event_dispatcher' => [
            //     ...
            // ],
            // 载入一个Subscriber
            'test.event_sub' => [
                'class' => Subscriber::class,
                'arguments' => ['@kernel'],
                'tags' => [
                    ['name' => 'kernel.event_subscriber', 'async' => true],
                ],
            ],
            // 定义日志处理
            'context.monolog_formatter' => [
                'class' => \Monolog\Formatter\LineFormatter::class,
                'arguments' => [
                    '[%datetime%] %message%'."\n",
                    'd/M/Y:H:i:s.u O',
                ],
            ],
            'context.logger' => [
                'class' => \Nonda\Logger\ContextLogger::class,
                'arguments' => [
                    '@kernel',
                    '@context.monolog_formatter',
                ],
            ],
            'event_context.logger' => [
                'class' => \Nonda\Logger\EventContextLogger::class,
                'arguments' => [
                    '@kernel',
                    '@context.monolog_formatter',
                ],
            ],
        ]
    ]
);

/** @var \Nonda\Event\Dispatcher $dispatcher */
$dispatcher = $kernel->getService('kernel.event_dispatcher');

$dispatcher->dispatch('test', new \Nonda\Event\Event($kernel));
```