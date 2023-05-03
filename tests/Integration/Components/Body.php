<?php

declare(strict_types=1);

namespace SnappyRendererTest\Integration\Components;

use SnappyRenderer\Renderable;
use SnappyRenderer\Renderer;

class Body implements Renderable
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param Renderer $renderer
     * @param mixed|null $data
     * @return iterable<mixed, mixed>
     */
    public function render(Renderer $renderer, $data = null): iterable
    {
        yield include 'heading.php';
        yield include 'paragraph.php';
        yield ['item 1', 'item 2', 'item 3'] => include 'list.php';
    }
}