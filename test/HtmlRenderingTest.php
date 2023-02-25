<?php

declare(strict_types=1);

namespace SnappyRendererTest;

use PHPUnit\Framework\TestCase;
use SnappyRenderer\Renderer;
use SnappyRenderer\Strategy\Pipeline\Pipe;

class HtmlRenderingTest extends TestCase
{
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

        $builder = new RenderableMockBuilder($this);
        $builder->setIterable([
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
        $document = $builder->getMock();

        $renderer = new Renderer(new Pipe());
        $result = $renderer->render($document, (object)[]);
        $this->assertEquals(<<<HTML
<html lang="en"><head><title>Test Page</title></head><body><h1>Test Page</h1><ul><li><a href="/">Home</a></li><li><a href="/my-account">My Account</a></li><li><a href="/about">About</a></li></ul></body></html>
HTML,
            $result);
    }
}