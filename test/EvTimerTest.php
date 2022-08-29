<?php

namespace Revolt\EventLoop\React;

use React\EventLoop\LoopInterface;
use Revolt\EventLoop;
use Revolt\EventLoop\React\Internal\EventLoopAdapter;

class EvTimerTest extends TimerTest
{
    public function createLoop(): LoopInterface
    {
        if (!EventLoop\Driver\EvDriver::isSupported()) {
            $this->markTestSkipped("ext-ev required");
        }

        self::clearGlobalLoop();
        EventLoop::setDriver(new EventLoop\Driver\EvDriver());
        return EventLoopAdapter::get();
    }
}
