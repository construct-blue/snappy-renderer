<?php

declare(strict_types=1);

use SnappyRenderer\Renderer;

return fn(Renderer $renderer) => <<<HTML
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
{$renderer->render('<p>Hello world!</p>')}
HTML;
