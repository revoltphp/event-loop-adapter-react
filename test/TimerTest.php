<?php

namespace Revolt\EventLoop\React;

use React\EventLoop\LoopInterface;
use React\Tests\EventLoop\Timer\AbstractTimerTest;
use Revolt\EventLoop;
use Revolt\EventLoop\React\Internal\EventLoopAdapter;

class TimerTest extends AbstractTimerTest
{
    /** @noinspection PhpUndefinedFieldInspection */
    protected static function clearGlobalLoop(): void
    {
        (static fn () => self::$driver = new EventLoop\Driver\StreamSelectDriver())
            ->bindTo(null, EventLoop::class)();
    }

    public function createLoop(): LoopInterface
    {
        self::clearGlobalLoop();
        EventLoop::setDriver(new EventLoop\Driver\StreamSelectDriver());
        return EventLoopAdapter::get();
    }
}
