<?php

namespace Revolt\EventLoop\React;

use React\EventLoop\LoopInterface;
use Revolt\EventLoop;
use Revolt\EventLoop\React\Internal\EventLoopAdapter;

class EventTimerTest extends TimerTest
{
    public function createLoop(): LoopInterface
    {
        if (!EventLoop\Driver\EventDriver::isSupported()) {
            $this->markTestSkipped("ext-event required");
        }

        EventLoop::setDriver(new EventLoop\Driver\EventDriver());
        return EventLoopAdapter::get();
    }
}
