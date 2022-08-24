<?php

use React\EventLoop\Loop;
use Revolt\EventLoop\React\Internal\EventLoopAdapter;

/**
 * @psalm-suppress InternalMethod
 */
Loop::set(EventLoopAdapter::get());
