<?php

declare(strict_types=1);

namespace SnappyRenderer;

use SnappyRenderer\Strategy\Pipeline\Last;
use SnappyRenderer\Strategy\Pipeline\Pipe;

class Renderer
{
    private Strategy $strategy;

    /**
     * @param Strategy|null $strategy
     */
    public function __construct(Strategy $strategy = null)
    {
        $this->strategy = $strategy ?? new Pipe();
    }

    /**
     * @param Renderable $renderable
     * @param object $model
     * @return string
     */
    public function render(Renderable $renderable, object $model): string
    {
        ob_start();
        try {
            foreach ($renderable->render($model) as $element) {
                echo $this->strategy->render($element, $model, $this, new Last());
            }
        } finally {
            $result = ob_get_clean();
        }
        return $result;
    }
}