<?php

declare(strict_types=1);

namespace Blue\Snappy\Renderer\Helper;

use Blue\Snappy\Renderer\Exception\RenderException;
use Blue\Snappy\Renderer\Renderable;
use Blue\Snappy\Renderer\Renderer;

/**
 * @internal
 */
final class Loop implements Renderable
{
    /** @var mixed */
    private $view;

    /** @var iterable<int, mixed> */
    private iterable $items;

    /**
     * @param mixed $view
     * @param iterable<int, mixed> $items
     */
    public function __construct($view, iterable $items)
    {
        $this->view = $view;
        $this->items = $items;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param Renderer $renderer
     * @param $data
     * @return iterable<mixed, mixed>
     * @throws RenderException
     */
    public function render(Renderer $renderer, $data = null): iterable
    {
        foreach ($this->items as $item) {
            yield $renderer->render($this->view, $item);
        }
    }
}