<?php

namespace Revolt\EventLoop\React;

use React\EventLoop\LoopInterface;
use Revolt\EventLoop;
use Revolt\EventLoop\React\Internal\EventLoopAdapter;

class UvTimerTest extends TimerTest
{
    public function createLoop(): LoopInterface
    {
        if (!EventLoop\Driver\UvDriver::isSupported()) {
            $this->markTestSkipped("ext-uv required");
        }

        EventLoop::setDriver(new EventLoop\Driver\UvDriver());
        return EventLoopAdapter::get();
    }
}
