<?php

namespace Revolt\EventLoop\React;

use React\EventLoop\LoopInterface;
use Revolt\EventLoop;

class EventTest extends Test
{
    public function createLoop(): LoopInterface
    {
        if (!EventLoop\Driver\EventDriver::isSupported()) {
            $this->markTestSkipped("ext-event required");
        }

        EventLoop::setDriver(new EventLoop\Driver\EventDriver());
        return Internal\EventLoopAdapter::get();
    }
}
