<?php

declare(strict_types=1);

namespace Blue\Core\View\Strategy\Pipeline;

use Blue\Core\View\NextStrategy;
use Blue\Core\View\Renderer;
use Blue\Core\View\Strategy;
use SplQueue;

class Pipe implements Strategy
{
    private SplQueue $queue;

    public function __construct()
    {
        $this->queue = new SplQueue();
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