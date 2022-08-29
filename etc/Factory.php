<?php

namespace React\EventLoop;

(static function () {
    $constName = 'REVOLT_ADAPTER_REACT_DISABLE_FACTORY_OVERRIDE';

    $env = \getenv($constName) ?: '0';
    $env = ($env !== '0' && $env !== 'false');
    $const = \defined($constName) && \constant($constName);

    if (!$const && !$env) {
        /**
         * Class used to overwrite React's loop factory with an implementation throwing an error.
         */
        final class Factory
        {
            public static function create(): LoopInterface
            {
                throw new \Error(
                    __METHOD__ . '() has been overridden by revolt/event-loop-adapter-react to prevent you from accidentally creating two event loop instances. ' .
                    'Use ' . Loop::class . '::get() instead of React\EventLoop\Factory::create() to ensure everything is running on the same event loop. ' .
                    'You may set a constant or environment variable named REVOLT_ADAPTER_REACT_DISABLE_FACTORY_OVERRIDE to disable this protection or open an issue at https://github.com/revoltphp/event-loop-adapter-react if you\'re unsure on the right way forward.'
                );
            }
        }
    }
})();
