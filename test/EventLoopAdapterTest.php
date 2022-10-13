<?php

declare(strict_types=1);

namespace Revolt\EventLoop\React;

use PHPUnit\Framework\TestCase;
use Revolt\EventLoop;
use Revolt\EventLoop\DriverFactory;
use Revolt\EventLoop\React\Internal\EventLoopAdapter;
use WeakReference;

final class EventLoopAdapterTest extends TestCase
{
    public function testConstructDoesNotLeakDriverReference(): void
    {
        $driver = (new DriverFactory())->create();
        $reference = WeakReference::create($driver);
        new EventLoopAdapter($driver);

        unset($driver);
        \gc_collect_cycles();
        $this->assertNull($reference->get());
    }

    public function testGetDoesNotLeakDriverReference(): void
    {
        $driver = (new DriverFactory())->create();
        $reference = WeakReference::create($driver);

        $originalDriver = EventLoop::getDriver();
        try {
            EventLoop::setDriver($driver);
            EventLoopAdapter::get();
        } finally {
            EventLoop::setDriver($originalDriver);
        }

        unset($driver);
        \gc_collect_cycles();
        $this->assertNull($reference->get());
    }

    public function testReturnsSameInstanceForSameDriver(): void
    {
        $this->assertSame(EventLoopAdapter::get(), EventLoopAdapter::get());
    }
}
