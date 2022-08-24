<?php

namespace Revolt\EventLoop\React;

use React\EventLoop\LoopInterface;
use React\Tests\EventLoop\Timer\AbstractTimerTest;
use Revolt\EventLoop;
use Revolt\EventLoop\React\Internal\EventLoopAdapter;

class TimerTest extends AbstractTimerTest
{
    public function createLoop(): LoopInterface
    {
        EventLoop::setDriver(new EventLoop\Driver\StreamSelectDriver());
        return EventLoopAdapter::get();
    }
}
