<?php

declare(strict_types=1);

namespace SnappyRenderer\Model;

use SnappyRenderer\Stringable;

/**
 * @internal
 */
class StringableModel implements Stringable
{
    private string $content;

    /**
     * @param string $content
     */
    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function __toString()
    {
        return $this->content;
    }
}