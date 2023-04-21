<?php

declare(strict_types=1);

use SnappyRenderer\Renderer;

return fn(Renderer $renderer, array $list) => <<<HTML
<ul>
{$renderer->loop(
    fn($r, $d) => "<li {$renderer->conditional('class="active"', fn($data) => $data == 'item 3', $d)}>{data}</li>",
    $list
)}
</ul>
HTML;
