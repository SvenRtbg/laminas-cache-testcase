<?php
declare(strict_types = 1);

namespace Svenrtbg\LaminasTestcase;

use function random_int;

class ObjectWithChangingProperty
{
    private float $time;


    public function cachedMethod(): int
    {
        $this->time = microtime(true);
        usleep(20);
        echo "Cache Miss\n";
        return random_int(1, 1000);
    }
}

