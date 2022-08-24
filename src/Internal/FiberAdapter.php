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

        // Note: resume() is async in revolt, but sync in react
        $this->suspension->resume($value);
    }

    public function throw(\Throwable $throwable): void
    {
        if ($this->suspension === null) {
            throw new \Error('Must call suspend() before calling throw()');
        }

        // Note: throw() is async in revolt, but sync in react
        $this->suspension->throw($throwable);
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
