<?php

declare(strict_types=1);

namespace SnappyRendererTest\Integration\Components;

use SnappyRenderer\Renderable;
use SnappyRenderer\Renderer;

class Body implements Renderable
{
    /**
     * @param Renderer $renderer
     * @return iterable<mixed>
     */
    public function render(Renderer $renderer): iterable
    {
        yield include 'heading.php';

    }
}