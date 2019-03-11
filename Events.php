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

}
