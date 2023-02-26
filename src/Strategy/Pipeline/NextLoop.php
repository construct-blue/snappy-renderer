<?php

declare(strict_types=1);

namespace SnappyRenderer\Strategy\Pipeline;

use SnappyRenderer\Exception\RenderException;
use SnappyRenderer\NextStrategy;
use SnappyRenderer\Renderable;
use SnappyRenderer\Renderer;
use SnappyRenderer\Strategy;
use SplQueue;

/**
 * @phpstan-import-type element from Renderable
 * @internal
 */
final class NextLoop implements NextStrategy
{
    /**
     * @var SplQueue<Strategy>
     */
    private SplQueue $queue;
    private NextStrategy $next;

    /**
     * @param SplQueue<Strategy> $queue
     * @param NextStrategy $next
     */
    public function __construct(SplQueue $queue, NextStrategy $next)
    {
        $this->queue = clone $queue;
        $this->next = $next;
    }

    /**
     * @param element $element
     * @param object $model
     * @param Renderer $renderer
     * @return string
     * @throws RenderException
     */
    public function continue($element, object $model, Renderer $renderer): string
    {
        if ($this->queue->isEmpty()) {
            return $this->next->continue($element, $model, $renderer);
        }
        $strategy = $this->queue->dequeue();
        return $strategy->render($element, $model, $renderer, $this);
    }
}