<?php

declare(strict_types=1);

namespace SnappyRenderer\Helper;

use SnappyRenderer\Renderable;
use SnappyRenderer\Renderer;

/**
 * @internal
 */
class Loop implements Renderable
{
    /** @var mixed */
    private $view;

    /**
     * @param mixed $view
     */
    public function __construct($view)
    {
        $this->view = $view;
    }

    public function render(Renderer $renderer, $data = null): iterable
    {
        foreach ($data as $datum) {
            yield $renderer->render($this->view, $datum);
        }
    }
}