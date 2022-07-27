<?php

namespace Revolt\EventLoop\Adapter\React;

use React\EventLoop\LoopInterface;
use Revolt\EventLoop;

class EvTimerTest extends TimerTest
{
    public function createLoop(): LoopInterface
    {
        if (!EventLoop\Driver\EvDriver::isSupported()) {
            $this->markTestSkipped("ext-ev required");
        }

        EventLoop::setDriver(new EventLoop\Driver\EvDriver);
        return RevoltLoop::get();
    }
}
