<?php

declare(strict_types=1);

namespace SnappyRenderer;

interface StrategyFactory
{
    public function create(Strategy $next): Strategy;
}