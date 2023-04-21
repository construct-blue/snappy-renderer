<?php

declare(strict_types=1);

namespace SnappyRenderer;

interface Renderable
{
    /**
     * @param Renderer $renderer
     * @param mixed|null $data
     * @return iterable<mixed>
     */
    public function render(Renderer $renderer, $data = null): iterable;
}