<?php

declare(strict_types=1);

namespace SnappyRendererTest;

use PHPUnit\Framework\TestCase;
use SnappyRenderer\Exception\RenderException;
use SnappyRenderer\Renderable\RenderableIterable;
use SnappyRenderer\RenderPipeline;
use SnappyRenderer\Renderer;

class HtmlRenderingTest extends TestCase
{
    /**
     * @return void
     * @throws RenderException
     */
    public function testShouldAllowRenderingOfHtmlDocuments()
    {
        $menu = [
            (object)[
                'href' => '/',
                'text' => 'Home'
            ],
            (object)[
                'href' => '/my-account',
                'text' => 'My Account'
            ],
            (object)[
                'href' => '/about',
                'text' => 'About'
            ],
        ];

        $document = new RenderableIterable([
            '<html lang="en">',
            '<head>', [
                '<title>Test Page</title>',
            ],
            '</head>',
            '<body>', [
                '<h1>Test Page</h1>',
                '<ul>',
                v_each($menu, fn($item) => [
                    '<li>',
                    sprintf('<a href="%s">', $item->href),
                    $item->text,
                    '</a>',
                    '</li>',
                ]),
                '</ul>',
            ],
            '</body>',
            '</html>'
        ]);

        $renderer = new Renderer(new RenderPipeline());
        $result = $renderer->render($document, (object)[]);
        self::assertEquals(<<<HTML
<html lang="en"><head><title>Test Page</title></head><body><h1>Test Page</h1><ul><li><a href="/">Home</a></li><li><a href="/my-account">My Account</a></li><li><a href="/about">About</a></li></ul></body></html>
HTML,
            $result);
    }

    /**
     * @return void
     * @throws RenderException
     */
    public function testShouldAllowRenderingOfStringLists()
    {
        $properties = [
            'bright',
            'unbreakable',
            'fast',
        ];

        $renderable = new RenderableIterable([
            '<ul>',
            v_each_string($properties, fn($property) => [
                "<li>$property</li>"
            ]),
            '</ul>',
        ]);

        $renderer = new Renderer();

        $result = $renderer->render($renderable, (object)[]);
        $this->assertEquals('<ul><li>bright</li><li>unbreakable</li><li>fast</li></ul>', $result);
    }
}