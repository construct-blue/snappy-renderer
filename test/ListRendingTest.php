<?php

declare(strict_types=1);

namespace SnappyRendererTest;

use PHPUnit\Framework\TestCase;
use SnappyRenderer\Renderable\Functional;
use SnappyRenderer\Renderable\Enumeration;
use SnappyRenderer\Renderer;
use stdClass;

class ListRendingTest extends TestCase
{
    public function testShouldAllowRenderingOfRenderableWithAListOfModels()
    {
        $people = [];
        $person = new stdClass();
        $person->name = 'first person';
        $people[] = $person;

        $person = new stdClass();
        $person->name = 'second person';
        $people[] = $person;

        $personView = new Functional(fn($person) => [
            "[$person->name]",
        ]);

        $renderable = new Functional(fn() => [
            new Enumeration($people, $personView)
        ]);

        $renderer = new Renderer();

        $result = $renderer->render($renderable, (object)[]);

        $this->assertEquals('[first person][second person]', $result);

    }
}