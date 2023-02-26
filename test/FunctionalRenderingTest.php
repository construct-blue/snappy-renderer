<?php

declare(strict_types=1);

namespace SnappyRendererTest;

use PHPUnit\Framework\TestCase;
use SnappyRenderer\Exception\RenderException;
use SnappyRenderer\RenderPipeline;
use SnappyRenderer\Renderable\Functional;
use SnappyRenderer\Renderer;

class FunctionalRenderingTest extends TestCase
{
    /**
     * @return void
     * @throws RenderException
     */
    public function testShouldAllowRenderingOfRenderableFiles()
    {
        $renderer = new Renderer(new RenderPipeline());
        $result = $renderer->render(new Functional(include 'functional/app.php'), (object)['greeting' => 'Hello World']);
        self::assertEquals(
            <<<HTML
<html lang="en"><head>
<title>My App</title>
</head><body>
<h1>My App</h1>
<p>Hello World</p>
</body></html>
HTML,
            $result
        );
    }
}