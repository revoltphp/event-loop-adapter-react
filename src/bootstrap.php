<?php

use React\Async\FiberFactory;
use React\Async\FiberInterface;
use React\EventLoop\Loop;
use Revolt\EventLoop\React\Internal\EventLoopAdapter;
use Revolt\EventLoop\React\Internal\FiberAdapter;

/**
 * @psalm-suppress InternalMethod
 */
Loop::set(EventLoopAdapter::get());

/**
 * @psalm-suppress InternalMethod
 */
FiberFactory::factory(static fn (): FiberInterface => new FiberAdapter());
