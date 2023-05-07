<?php

declare(strict_types=1);

namespace SnappyRendererTest\Helper;

use PHPUnit\Framework\TestCase;
use SnappyRenderer\Helper\Arguments;
use SnappyRenderer\Helper\ArgumentsList;

class ArgumentsListTest extends TestCase
{
    public function testShouldWrapIterableOfArraysInArgumentsObjects(): void
    {
        $presidents = [
            [
                'id' => 1,
                'name' => 'Barack Obama',
            ],
            [
                'id' => 2,
                'name' => 'Donald Trump',
            ],
            [
                'id' => 3,
                'name' => 'Joe Biden',
            ],
        ];

        $argumentsList = new ArgumentsList($presidents);

        self::assertContainsOnlyInstancesOf(Arguments::class, $argumentsList);
        self::assertEquals(
            $presidents,
            array_map(fn(Arguments $arguments) => $arguments->getArrayCopy(), iterator_to_array($argumentsList))
        );
    }
}
