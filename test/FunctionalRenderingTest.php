<?php

declare(strict_types=1);

namespace SnappyRendererTest;

use PHPUnit\Framework\TestCase;
use SnappyRenderer\Renderable\RenderableClosure;
use SnappyRenderer\Renderer;
use SnappyRenderer\Strategy\Pipeline\Pipe;

class FunctionalRenderingTest extends TestCase
{
    public function testShouldAllowRenderingOfRenderableFiles()
    {
        $renderer = new Renderer(new Pipe());
        $result = $renderer->render(new RenderableClosure(include 'functional/app.php'), (object)['greeting' => 'Hello World']);
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