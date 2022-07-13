<?php

namespace Amp\ReactAdapter\Test;

use Amp\ReactAdapter\ReactAdapter;
use React\EventLoop\LoopInterface;
use Revolt\EventLoop;

class EventTimerTest extends TimerTest
{
    public function createLoop(): LoopInterface
    {
        if (!EventLoop\Driver\EventDriver::isSupported()) {
            $this->markTestSkipped("ext-event required");
        }

        EventLoop::setDriver(new EventLoop\Driver\EventDriver);
        return ReactAdapter::get();
    }
}
