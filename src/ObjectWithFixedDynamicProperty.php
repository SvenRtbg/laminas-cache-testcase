<?php
declare(strict_types = 1);

namespace Svenrtbg\LaminasTestcase;

use function random_int;

class ObjectWithFixedDynamicProperty
{
    private float $time;

    public function __construct()
    {
        $this->time = microtime(true);
    }

    public function cachedMethod(): int
    {
        usleep(20);
        echo "Cache Miss";
        return random_int(1, 1000);
    }
}

