<?php

declare(strict_types=1);

namespace SnappyRendererTest\Integration\Components;

use SnappyRenderer\Exception\RenderException;
use SnappyRenderer\Renderable;
use SnappyRenderer\Renderer;

class Layout implements Renderable
{
    /** @var mixed  */
    private $body;

    /**
     * @param mixed $body
     */
    public function __construct($body)
    {
        $this->body = $body;
    }

    /**
     * @param Renderer $renderer
     * @param mixed|null $data
     * @return iterable<mixed>
     * @throws RenderException
     */
    public function render(Renderer $renderer, $data = null): iterable
    {
        yield <<<HTML
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>Document</title>
</head>
<body>
{$renderer->render($this->body, $data)}
</body>
</html>
HTML;
    }
}