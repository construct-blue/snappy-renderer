<?php

declare(strict_types=1);

namespace Blue\Core\View\Strategy\Pipeline;

use Blue\Core\View\NextStrategy;
use Blue\Core\View\Renderer;
use Blue\Core\View\Strategy;
use SplQueue;

class Next implements NextStrategy
{
    private SplQueue $queue;
    private NextStrategy $next;

    public function __construct(SplQueue $queue, NextStrategy $next)
    {
        $this->queue = clone $queue;
        $this->next = $next;
    }

    public function continue(mixed $element, object $model, Renderer $renderer): string
    {
        if ($this->queue->isEmpty()) {
            return $this->next->continue($element, $model, $renderer);
        }
        /** @var Strategy $strategy */
        $strategy = $this->queue->dequeue();
        return $strategy->render($element, $model, $renderer, $this);
    }
}