<?php

declare(strict_types=1);

namespace SnappyRenderer;

use Throwable;

interface Stringable
{
    /**
     * @return string
     * @throws Throwable
     */
    public function __toString();
}
