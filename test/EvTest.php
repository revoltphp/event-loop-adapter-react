<?php

namespace Amp\ReactAdapter\Test;

use Amp\ReactAdapter\ReactAdapter;
use React\EventLoop\LoopInterface;
use Revolt\EventLoop;

class EvTest extends Test
{
    public function createLoop(): LoopInterface
    {
        if (!EventLoop\Driver\EvDriver::isSupported()) {
            $this->markTestSkipped("ext-ev required");
        }

        EventLoop::setDriver(new EventLoop\Driver\EvDriver);
        return ReactAdapter::get();
    }
}
