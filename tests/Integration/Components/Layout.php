<?php

declare(strict_types=1);

namespace SnappyRendererTest\Integration\Components;

use SnappyRenderer\Renderable;
use SnappyRenderer\Renderer;

class Layout implements Renderable
{
    private $body;

    /**
     * @param $body
     */
    public function __construct($body)
    {
        $this->body = $body;
    }


    public function render(Renderer $renderer): iterable
    {
        yield <<<HTML
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>Document</title>
<link rel="stylesheet" href="">
</head>
<body>
{$renderer->render($this->body)}
<script src=""></script>
</body>
</html>
HTML;
    }
}