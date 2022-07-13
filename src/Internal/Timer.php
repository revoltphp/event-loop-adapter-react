<?php

namespace Amp\ReactAdapter\Internal;

use React\EventLoop\TimerInterface;

/** @internal */
final class Timer implements TimerInterface
{
    /** @var float */
    private float $interval;

    /** @var callable */
    private $callback;

    /** @var bool */
    private bool $periodic;

    public function __construct(float $interval, callable $callback, bool $periodic = false)
    {
        if ($interval < 0.000001) {
            $interval = 0.000001;
        }

        $this->interval = $interval;
        $this->callback = $callback;
        $this->periodic = $periodic;
    }

    public function getInterval(): float
    {
        return $this->interval;
    }

    public function getCallback(): callable
    {
        return $this->callback;
    }

    public function isPeriodic(): bool
    {
        return $this->periodic;
    }
}
