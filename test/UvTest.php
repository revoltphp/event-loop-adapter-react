<?php

namespace Amp\ReactAdapter\Test;

use Amp\ReactAdapter\ReactAdapter;
use React\EventLoop\LoopInterface;
use Revolt\EventLoop;

class UvTest extends Test
{
    public function createLoop(): LoopInterface
    {
        if (!EventLoop\Driver\UvDriver::isSupported()) {
            $this->markTestSkipped("ext-uv required");
        }

        EventLoop::setDriver(new EventLoop\Driver\UvDriver);
        return ReactAdapter::get();
    }
}
