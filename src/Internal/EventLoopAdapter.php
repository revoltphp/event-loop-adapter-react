<?php

namespace Revolt\EventLoop\React\Internal;

use React\EventLoop\LoopInterface;
use React\EventLoop\TimerInterface;
use Revolt\EventLoop;
use Revolt\EventLoop\Driver;

/** @internal */
final class EventLoopAdapter implements LoopInterface
{
    private static \WeakMap $instances;

    private Driver $driver;

    private array $readWatchers = [];
    private array $writeWatchers = [];
    private array $timers = [];
    private array $signals = [];

    public static function get(): LoopInterface
    {
        /** @psalm-suppress RedundantPropertyInitializationCheck */
        self::$instances ??= new \WeakMap();

        $driver = EventLoop::getDriver();

        return self::$instances[$driver] ??= new self($driver);
    }

    public function __construct(?Driver $driver = null)
    {
        $this->driver = $driver ?? EventLoop::getDriver();

        /** @psalm-suppress RedundantPropertyInitializationCheck */
        self::$instances ??= new \WeakMap();
        self::$instances[$this->driver] = $this;
    }

    public function addReadStream($stream, $listener): void
    {
        if (isset($this->readWatchers[(int) $stream])) {
            // Double watchers are silently ignored by ReactPHP
            return;
        }

        $watcher = $this->driver->onReadable($stream, static function () use ($stream, $listener) {
            $listener($stream);
        });

        $this->readWatchers[(int) $stream] = $watcher;
    }

    public function addWriteStream($stream, $listener): void
    {
        if (isset($this->writeWatchers[(int) $stream])) {
            // Double watchers are silently ignored by ReactPHP
            return;
        }

        $watcher = $this->driver->onWritable($stream, static function () use ($stream, $listener) {
            $listener($stream);
        });

        $this->writeWatchers[(int) $stream] = $watcher;
    }

    public function removeReadStream($stream): void
    {
        $key = (int) $stream;

        if (!isset($this->readWatchers[$key])) {
            return;
        }

        $this->driver->cancel($this->readWatchers[$key]);

        unset($this->readWatchers[$key]);
    }

    public function removeWriteStream($stream): void
    {
        $key = (int) $stream;

        if (!isset($this->writeWatchers[$key])) {
            return;
        }

        $this->driver->cancel($this->writeWatchers[$key]);

        unset($this->writeWatchers[$key]);
    }

    public function addTimer($interval, $callback): TimerInterface
    {
        $timer = new Timer($interval, $callback, false);

        $watcher = $this->driver->delay($timer->getInterval(), function () use ($timer, $callback) {
            $this->cancelTimer($timer);

            $callback($timer);
        });

        $this->deferEnabling($watcher);
        $this->timers[\spl_object_hash($timer)] = $watcher;

        return $timer;
    }

    public function addPeriodicTimer($interval, $callback): TimerInterface
    {
        $timer = new Timer($interval, $callback, true);

        $watcher = $this->driver->repeat($timer->getInterval(), function () use ($timer, $callback) {
            $callback($timer);
        });

        $this->deferEnabling($watcher);
        $this->timers[\spl_object_hash($timer)] = $watcher;

        return $timer;
    }

    public function cancelTimer(TimerInterface $timer): void
    {
        if (!isset($this->timers[\spl_object_hash($timer)])) {
            return;
        }

        $this->driver->cancel($this->timers[\spl_object_hash($timer)]);

        unset($this->timers[\spl_object_hash($timer)]);
    }

    public function futureTick($listener): void
    {
        $this->driver->defer(static function () use ($listener) {
            $listener();
        });
    }

    public function addSignal($signal, $listener): void
    {
        if (\in_array($listener, $this->signals[$signal] ?? [], true)) {
            return;
        }

        try {
            $watcherId = $this->driver->onSignal($signal, static function () use ($listener) {
                $listener();
            });

            $this->signals[$signal][$watcherId] = $listener;
        } catch (EventLoop\UnsupportedFeatureException) {
            throw new \BadMethodCallException("Signals aren't available in the current environment.");
        }
    }

    public function removeSignal($signal, $listener): void
    {
        if (!isset($this->signals[$signal])) {
            return;
        }

        $index = \array_search($listener, $this->signals[$signal], true);
        if ($index === false) {
            return;
        }

        $this->driver->cancel($index);

        unset($this->signals[$signal][$index]);
        if (empty($this->signals[$signal])) {
            unset($this->signals[$signal]);
        }
    }

    public function run(): void
    {
        $this->driver->run();
    }

    public function stop(): void
    {
        $this->driver->stop();
    }

    private function deferEnabling(string $watcherId): void
    {
        $this->driver->disable($watcherId);
        $this->driver->defer(function () use ($watcherId) {
            try {
                $this->driver->enable($watcherId);
            } catch (EventLoop\InvalidCallbackError) {
                // ignore
            }
        });
    }
}
