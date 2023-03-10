<?php

declare(strict_types=1);

namespace SnappyRendererTest;

use PHPUnit\Framework\TestCase;
use SnappyRenderer\Exception\RenderException;
use SnappyRenderer\Renderable\Elements;
use SnappyRenderer\Renderable\Enumeration;
use SnappyRenderer\Renderable\Functional;
use SnappyRenderer\Renderable\StringEnumeration;
use SnappyRenderer\Renderer;
use stdClass;

class BenchmarkTest extends TestCase
{
    /**
     * @return void
     * @throws RenderException
     */
    public function test()
    {
        $links = [];
        $link = new stdClass();
        $link->text = 'Home';
        $link->href = '/';
        $links[] = $link;

        $link = new stdClass();
        $link->text = 'Imprint';
        $link->href = '/imprint';
        $links[] = $link;

        $link = new stdClass();
        $link->text = 'About';
        $link->href = '/about';
        $links[] = $link;

        $link = new stdClass();
        $link->text = 'People';
        $link->href = '/people';
        $links[] = $link;

        $people = [];
        for ($i = 0; $i < 500; $i++) {
            $person = new stdClass();
            $person->id = $i;
            $person->name = "name $i";
            $person->properties = [
                'prop 1',
                'prop 2',
                'prop 3'
            ];
            $people[] = $person;
        }
        $start = microtime(true);
        $renderable = new Elements([
            '<html lang="en">',
            '<head>',
            [
                '<title>Test Page</title>',
            ],
            '</head>',
            '<body>',
            [
                '<h1>Test Page</h1>',
                '<ul>',
                new Enumeration(
                    $links,
                    new Functional(fn($item) => [
                        '<li>',
                        sprintf('<a href="%s">', $item->href),
                        $item->text,
                        '</a>',
                        '</li>',
                    ])
                ),
                '</ul>',

                '<ul>',
                new Enumeration(
                    $people,
                    new Functional(fn($person) => [
                        '<li>',
                        [
                            '<table>',
                            [
                                '<tr>',
                                [
                                    '<th>',
                                    'ID',
                                    '</th>',
                                    '<td>',
                                    (string)$person->id,
                                    '</td>',
                                ],
                                '</tr>',
                                '<tr>',
                                [
                                    '<th>',
                                    'Name',
                                    '</th>',
                                    '<td>',
                                    $person->name,
                                    '</td>',
                                ],
                                '</tr>',
                                '<tr>',
                                [
                                    '<th>',
                                    'Properties',
                                    '</th>',
                                    '<td>',
                                    [
                                        '<ul>',
                                        new StringEnumeration(
                                            $person->properties,
                                            new Functional(fn($property) => [
                                                "<li>$property</li>"
                                            ])
                                        ),
                                        '</ul>',
                                    ],
                                    '</td>',
                                ],
                                '</tr>',
                            ],
                            '</table>',
                        ],
                        '</li>',
                    ])
                ),
                '</ul>',
            ],
            '</body>',
            '</html>'
        ]);

        $renderer = new Renderer();
        $result = $renderer->render($renderable, (object)[]);
        $time = microtime(true) - $start;
        self::assertNotEmpty($result);

        if ($time > 0.05) {
            self::markTestIncomplete('Slow rendering: ' . $time);
        }
    }
}