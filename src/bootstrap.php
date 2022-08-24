<?php

use React\EventLoop\Loop;
use Revolt\EventLoop\Adapter\React\Internal\EventLoopAdapter;

/**
 * @psalm-suppress InternalMethod
 */
Loop::set(EventLoopAdapter::get());
