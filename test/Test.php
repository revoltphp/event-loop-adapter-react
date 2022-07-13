<?php

namespace Amp\ReactAdapter\Test;

use Amp\ReactAdapter\Internal\Timer;
use Amp\ReactAdapter\ReactAdapter;
use React\EventLoop\LoopInterface;
use React\Tests\EventLoop\AbstractLoopTest;
use Revolt\EventLoop;
use Revolt\EventLoop\Driver;
use Revolt\EventLoop\Driver\StreamSelectDriver;
use Revolt\EventLoop\UnsupportedFeatureException;

class Test extends AbstractLoopTest
{
    public function createLoop(): LoopInterface
    {
        EventLoop::setDriver(new StreamSelectDriver());
        return ReactAdapter::get();
    }

    public function testIgnoreRemovedCallback()
    {
        // We don't have the order guarantee, so we recreate this test
        // and accept that one handler is called, but not the other.

        [$stream1, $stream2] = $this->createSocketPair();

        $stream1called = false;
        $stream2called = false;

        $this->loop->addReadStream($stream1, function () use (&$stream1called, $stream1, $stream2) {
            $stream1called = true;

            $this->loop->removeReadStream($stream1);
            $this->loop->removeReadStream($stream2);
        });

        $this->loop->addReadStream($stream1, function () use (&$stream2called, $stream1, $stream2) {
            $stream2called = true;

            $this->loop->removeReadStream($stream1);
            $this->loop->removeReadStream($stream2);
        });

        \fwrite($stream1, "foo\n");
        \fwrite($stream2, "foo\n");

        $this->loop->run();

        $this->assertTrue((bool) ($stream1called ^ $stream2called));
    }

    public function testCancelTimerReturnsIfNotSet()
    {
        $timer = new Timer(0.01, function () {
        });

        $driver = $this->createMock(EventLoop\Driver::class);
        $driver->expects($this->never())->method($this->anything());

        $loop = new ReactAdapter($driver);
        $loop->cancelTimer($timer);
    }

    public function testAddSignalUnsupportedFeatureExceptionIsCast()
    {
        $this->expectException(\BadMethodCallException::class);

        $signal = \SIGTERM;
        $listener = function () {
        };
        $exception = new UnsupportedFeatureException('phpunit test');

        $driver = $this->createMock(Driver::class);
        $driver->method('onSignal')->with($signal, $listener)->willThrowException($exception);

        $loop = new ReactAdapter($driver);
        $loop->addSignal($signal, $listener);
    }
}
