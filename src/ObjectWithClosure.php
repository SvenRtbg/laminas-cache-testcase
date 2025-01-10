<?php
declare(strict_types = 1);

namespace Svenrtbg\LaminasTestcase;

use function random_int;

class ObjectWithClosure
{
    private \Closure $closure;

    public function __construct()
    {
        $this->closure = function () { return "closure";};
    }

    public function cachedMethod(): int
    {
        return 9999;
    }
}

