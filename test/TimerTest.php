<?php

namespace Amp\ReactAdapter\Test;

use Amp\ReactAdapter\ReactAdapter;
use React\EventLoop\LoopInterface;
use React\Tests\EventLoop\Timer\AbstractTimerTest;
use Revolt\EventLoop;

class TimerTest extends AbstractTimerTest
{
    public function createLoop(): LoopInterface
    {
        EventLoop::setDriver(new EventLoop\Driver\StreamSelectDriver());
        return ReactAdapter::get();
    }
}
