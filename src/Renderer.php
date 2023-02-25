<?php

declare(strict_types=1);

namespace Blue\Core\View;

use Blue\Core\View\Exception\RenderException;
use Blue\Core\View\Strategy\Pipeline\Last;

class Renderer
{
    private Strategy $strategy;

    /**
     * @param Strategy $strategy
     */
    public function __construct(Strategy $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * @param Renderable $renderable
     * @param object $model
     * @return string
     * @throws RenderException
     */
    public function render(Renderable $renderable, object $model): string
    {
        ob_start();
        try {
            foreach ($renderable->render($model) as $element) {
                if ($element instanceof Renderable) {
                    echo $this->render($element, $model);
                } else {
                    echo $this->strategy->render($element, $model, $this, new Last());
                }
            }
        } finally {
            $result = ob_get_clean();
        }
        return $result;
    }
}