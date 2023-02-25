<?php

declare(strict_types=1);

namespace SnappyRenderer\Strategy\Pipeline;

use SnappyRenderer\NextStrategy;
use SnappyRenderer\Renderer;
use SnappyRenderer\Strategy;
use SplQueue;

class Pipe implements Strategy
{
    private SplQueue $queue;

    public function __construct()
    {
        $this->queue = new SplQueue();
        $this->add(new Strategy\Renderable());
        $this->add(new Strategy\PlainString());
        $this->add(new Strategy\PlainIterable());
        $this->add(new Strategy\Stringable());
        $this->add(new Strategy\Closure());
    }

    public function add(Strategy $strategy)
    {
        $this->queue->enqueue($strategy);
    }

    public function render(mixed $element, object $model, Renderer $renderer, NextStrategy $next): string
    {
        return (new Next($this->queue, $next))->continue($element, $model, $renderer);
    }
}