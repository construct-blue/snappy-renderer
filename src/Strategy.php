<?php

declare(strict_types=1);

namespace SnappyRenderer;

interface Strategy
{
    public function execute($view, Renderer $renderer): string;
}