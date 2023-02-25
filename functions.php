<?php

declare(strict_types=1);

use Blue\Core\View\Renderable\RenderableClosure;

function v_each(iterable $models, Closure $closure): iterable
{
    $renderable = new RenderableClosure($closure);
    foreach ($models as $model) {
        yield $renderable->render($model);
    }
}
