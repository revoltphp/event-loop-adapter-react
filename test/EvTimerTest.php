<?php

namespace Revolt\EventLoop\Adapter\React;

use React\EventLoop\LoopInterface;
use Revolt\EventLoop;
use Revolt\EventLoop\Adapter\React\Internal\EventLoopAdapter;

class EvTimerTest extends TimerTest
{
    public function createLoop(): LoopInterface
    {
        if (!EventLoop\Driver\EvDriver::isSupported()) {
            $this->markTestSkipped("ext-ev required");
        }

        EventLoop::setDriver(new EventLoop\Driver\EvDriver());
        return EventLoopAdapter::get();
    }
}
