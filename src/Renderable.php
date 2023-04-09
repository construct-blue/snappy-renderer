<?php

declare(strict_types=1);

namespace SnappyRenderer;

interface Renderable
{
    public function render(Renderer $renderer): iterable;
}