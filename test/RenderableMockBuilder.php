<?php

declare(strict_types=1);

namespace BlueTest\Core\View;

use Blue\Core\View\Renderable;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RenderableMockBuilder
{
    private TestCase $testCase;
    private iterable $iterable;

    /**
     * @param TestCase $testCase
     */
    public function __construct(TestCase $testCase)
    {
        $this->testCase = $testCase;
    }

    public function setIterable(iterable $iterable): self
    {
        $this->iterable = $iterable;
        return $this;
    }

    public function getMock(): Renderable
    {
        $builder = new MockBuilder($this->testCase, Renderable::class);
        $builder->onlyMethods(['render']);
        /** @var Renderable&MockObject $renderable */
        $renderable = $builder->getMockForAbstractClass();
        $renderable->method('render')->willReturn($this->iterable);
        return $renderable;
    }
}