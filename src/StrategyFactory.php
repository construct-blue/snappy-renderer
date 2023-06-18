<?php

declare(strict_types=1);

namespace Blue\Snappy\Renderer;

interface StrategyFactory
{
    public function create(Strategy $next): Strategy;
}