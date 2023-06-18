<?php

declare(strict_types=1);

namespace Blue\Snappy\Renderer\Strategy;

use Blue\Snappy\Renderer\Exception\RenderException;
use Blue\Snappy\Renderer\Renderer;
use Blue\Snappy\Renderer\Strategy\Base\PipelineStrategy;
use Throwable;

final class IterableStrategy extends PipelineStrategy
{
    /**
     * @param mixed $view
     * @param Renderer $renderer
     * @param mixed|null $data
     * @return string
     * @throws RenderException
     */
    public function execute($view, Renderer $renderer, $data): string
    {
        if (is_iterable($view)) {
            ob_start();
            try {
                foreach ($view as $itemData => $item) {
                    echo $renderer->render($item, $itemData);
                }
            } catch (Throwable $throwable) {
                throw RenderException::forThrowableInView($throwable, $view);
            } finally {
                $result = ob_get_clean();
            }
            return $result;
        }
        return $this->next($view, $renderer, $data);
    }
}