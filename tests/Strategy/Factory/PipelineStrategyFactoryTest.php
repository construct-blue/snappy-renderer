<?php

declare(strict_types=1);

namespace BlueTest\Snappy\Renderer\Strategy\Factory;

use Blue\Snappy\Renderer\Exception\StrategyFactoryException;
use Blue\Snappy\Renderer\Strategy\Factory\PipelineStrategyFactory;
use PHPUnit\Framework\TestCase;
use stdClass;

class PipelineStrategyFactoryTest extends TestCase
{
    public function testShouldThrowExceptionForInvalidClass(): void
    {
        self::expectException(StrategyFactoryException::class);
        // @phpstan-ignore-next-line
        new PipelineStrategyFactory(stdClass::class);
    }
}
