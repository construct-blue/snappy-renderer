<?php

declare(strict_types=1);

namespace Blue\Core\View;

interface NextStrategy
{
    public function continue(mixed $element, object $model, Renderer $renderer): string;
}