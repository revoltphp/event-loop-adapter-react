<?php

use React\EventLoop\Loop;
use Revolt\EventLoop\Adapter\React\RevoltLoop;

/**
 * @psalm-suppress InternalMethod
 */
Loop::set(RevoltLoop::get());
