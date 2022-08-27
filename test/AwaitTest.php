<?php

namespace Revolt\EventLoop\React;

use PHPUnit\Framework\TestResult;
use React;

class AwaitTest extends React\Tests\Async\AwaitTest
{
    public function testExecutionOrder()
    {
        self::expectOutputString('acbd');

        $deferred = new React\Promise\Deferred();

        $promises[] = React\Async\async(function () use (&$deferred) {
            print 'a';
            React\Async\await($deferred->promise());
            print 'b';
        })();

        $promises[] = React\Async\async(function () use ($deferred) {
            print 'c';
            $deferred->resolve();
            print 'd';
        })();

        React\Async\await(React\Promise\all($promises));
    }
}
