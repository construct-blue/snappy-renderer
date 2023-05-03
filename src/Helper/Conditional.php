<?php

declare(strict_types=1);

namespace SnappyRenderer\Helper;

use Closure;
use SnappyRenderer\Exception;
use SnappyRenderer\Renderable;
use SnappyRenderer\Renderer;

/**
 * @internal
 */
class Conditional implements Renderable
{
    /** @var mixed */
    private $view;
    private Closure $predicate;

    /**
     * @param mixed $view
     * @param Closure $predicate
     */
    public function __construct($view, Closure $predicate)
    {
        $this->view = $view;
        $this->predicate = $predicate;
    }

    /**
     * @param Renderer $renderer
     * @param mixed|null $data
     * @return iterable<mixed, mixed>
     * @throws Exception\RenderException
     */
    public function render(Renderer $renderer, $data = null): iterable
    {
        if (($this->predicate)($data)) {
            yield $renderer->render($this->view, $data);
        }
    }
}