<?php

declare(strict_types=1);

namespace BlueTest\Core\View;

use Blue\Core\View\Renderable\RenderableClosure;
use Blue\Core\View\Renderer;
use Blue\Core\View\Strategy\Pipeline\Pipe;
use PHPUnit\Framework\TestCase;

class FunctionalRenderingTest extends TestCase
{
    public function testShouldAllowRenderingOfRenderableFiles()
    {
        $renderer = new Renderer(new Pipe());
        $result = $renderer->render(new RenderableClosure(include 'functional/app.php'), (object)['greeting' => 'Hello World']);
        $this->assertEquals(
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