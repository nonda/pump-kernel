<?php
namespace Nonda;

final class Events
{
    /**
     * =====================
     * === Kernel Events ===
     * =====================
     */
    const KERNEL_CONSTRUCT = 'kernel.construct';

    const KERNEL_BOOT = 'kernel.boot';

    const KERNEL_TERMINATE = 'kernel.terminate';

    public static $events = [
        // kernel
        self::KERNEL_CONSTRUCT => 'Nonda kernel construct',
        self::KERNEL_BOOT => 'Nonda kernel boot',
    ];

    const GROUP_DEFAULT = 'default';

//    const GROUP_KERNEL = 'kernel';

    public static $groups = [
        self::GROUP_DEFAULT => 'default event group',
//        self::GROUP_KERNEL => 'nonda kernel event group',
    ];

    public static $eventGroups = [
        self::KERNEL_BOOT => self::GROUP_DEFAULT,
        self::KERNEL_CONSTRUCT => self::GROUP_DEFAULT,
        self::KERNEL_TERMINATE => self::GROUP_DEFAULT,
    ];
}
