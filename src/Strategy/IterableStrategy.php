<?php

declare(strict_types=1);

namespace SnappyRenderer\Strategy;

use SnappyRenderer\Exception\RenderException;
use SnappyRenderer\Renderer;
use SnappyRenderer\Strategy;
use Throwable;

class IterableStrategy implements Strategy
{
    private Strategy $strategy;

    public function __construct(Strategy $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * @param mixed $view
     * @param Renderer $renderer
     * @return string
     * @throws RenderException
     */
    public function execute($view, Renderer $renderer): string
    {
        if (is_iterable($view)) {
            ob_start();
            try {
                foreach ($view as $item) {
                    echo $renderer->render($item);
                }
            } catch (Throwable $throwable) {
                throw RenderException::forThrowableInView($throwable, $view);
            } finally {
                $result = ob_get_clean();
            }
            return $result;
        }
        return $this->strategy->execute($view, $renderer);
    }
}