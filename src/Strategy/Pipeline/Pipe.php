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
        $this->add(new Strategy\RenderRenderable());
        $this->add(new Strategy\RenderString());
        $this->add(new Strategy\RenderIterable());
        $this->add(new Strategy\RenderStringable());
        $this->add(new Strategy\RenderClosure());
    }

    public function add(Strategy $strategy)
    {
        $this->queue->enqueue($strategy);
    }

    public function render($element, object $model, Renderer $renderer, NextStrategy $next): string
    {
        return (new Next($this->queue, $next))->continue($element, $model, $renderer);
    }
}