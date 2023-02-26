<?php

declare(strict_types=1);

namespace SnappyRenderer;

use SnappyRenderer\Strategy\Pipeline\NextLoop;
use SplQueue;

/**
 * @phpstan-import-type element from Renderable
 */
final class RenderPipeline implements Strategy
{
    /**
     * @var SplQueue<Strategy>
     */
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

    public function add(Strategy $strategy): self
    {
        $this->queue->enqueue($strategy);
        return $this;
    }

    public function clear(): self
    {
        $this->queue = new SplQueue();
        return $this;
    }

    /**
     * @param element $element
     * @param object $model
     * @param Renderer $renderer
     * @param NextStrategy $next
     * @return string
     * @throws Exception\RenderException
     */
    public function render($element, object $model, Renderer $renderer, NextStrategy $next): string
    {
        return (new NextLoop($this->queue, $next))->continue($element, $model, $renderer);
    }
}