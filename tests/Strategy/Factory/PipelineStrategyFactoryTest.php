<?php

declare(strict_types=1);

namespace BlueTest\Snappy\Renderer\Strategy\Factory;

use PHPUnit\Framework\TestCase;
use SnappyRenderer\Exception\StrategyFactoryException;
use SnappyRenderer\Strategy\Factory\PipelineStrategyFactory;
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
