# event-loop-adapter-react

![Stable](https://img.shields.io/badge/stability-stable-green.svg?style=flat-square)
![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)

`revolt/event-loop-adapter-react` makes any [ReactPHP](https://reactphp.org/) library run on top of
the [Revolt event loop](https://revolt.run).

## Installation

```bash
composer require revolt/event-loop-adapter-react
```

## Usage

Everywhere where a ReactPHP library requires an instance of `LoopInterface`, you just pass `Loop::get()` as normal.
We automatically set up everything to run the ReactPHP library on the [Revolt event loop](https://revolt.run/).

```php
<?php

require 'vendor/autoload.php';

use React\EventLoop\Loop;
use Revolt\EventLoop;

$app = function ($request, $response) {
    $response->writeHead(200, array('Content-Type' => 'text/plain'));
    $response->end("Hello World\n");
};

$socket = new React\Socket\Server(Loop::get());
$http = new React\Http\Server($socket, Loop::get());

$http->on('request', $app);
echo "Server running at http://127.0.0.1:1337\n";

$socket->listen(1337);

EventLoop::run();
```
