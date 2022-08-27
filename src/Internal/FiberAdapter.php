<?php

namespace Revolt\EventLoop\React\Internal;

use React\Async\FiberInterface;
use Revolt\EventLoop;

/** @internal */
final class FiberAdapter implements FiberInterface
{
    private ?EventLoop\Suspension $suspension = null;

    public function resume(mixed $value): void
    {
        if ($this->suspension === null) {
            throw new \Error('Must call suspend() before calling resume()');
        }

        $this->suspension->resume($value);

        // Note: resume() above is async in revolt, but sync in react,
        // so let's suspend here until the queued resumption above is executed.
        $suspension = EventLoop::getSuspension();
        EventLoop::queue($suspension->resume(...));
        $suspension->suspend();
    }

    public function throw(\Throwable $throwable): void
    {
        if ($this->suspension === null) {
            throw new \Error('Must call suspend() before calling throw()');
        }

        $this->suspension->throw($throwable);

        // Note: throw() above is async in revolt, but sync in react,
        // so let's suspend here until the queued throwing above is executed.
        $suspension = EventLoop::getSuspension();
        EventLoop::queue($suspension->resume(...));
        $suspension->suspend();
    }

    public function suspend(): mixed
    {
        if ($this->suspension !== null) {
            throw new \Error('Must call resume() or throw() before calling suspend() again');
        }

        $this->suspension = EventLoop::getSuspension();

        return $this->suspension->suspend();
    }
}
