<?php

declare(strict_types=1);

namespace SnappyRenderer;

interface Renderable
{
    /**
     * @param Renderer $renderer
     * @return iterable<mixed>
     */
    public function render(Renderer $renderer): iterable;
}