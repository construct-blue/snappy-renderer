<?php

declare(strict_types=1);

namespace SnappyRenderer\Renderable;

use SnappyRenderer\Exception\RenderException;
use SnappyRenderer\Model\StringableModel;
use SnappyRenderer\Renderable;

/**
 * @implements Renderable<StringableModel>
 * @phpstan-import-type element from Renderable
 */
class StringEnumeration implements Renderable
{
    /**
     * @var iterable<string>
     */
    private iterable $strings;

    /**
     * @var Renderable<StringableModel>
     */
    private Renderable $renderable;

    /**
     * @param iterable<string> $strings
     * @param Renderable<StringableModel> $renderable
     */
    public function __construct(iterable $strings, Renderable $renderable)
    {
        $this->strings = $strings;
        $this->renderable = $renderable;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @param object $model
     * @return iterable<element>
     * @throws RenderException
     */
    public function render(object $model): iterable
    {
        foreach ($this->strings as $string) {
            yield $this->renderable->render(new StringableModel($string));
        }
    }
}