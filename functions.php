<?php

declare(strict_types=1);

/**
 * @param iterable<object> $models
 * @param Closure $closure
 * @return iterable
 */
function v_each(iterable $models, Closure $closure): iterable
{
    $renderable = new SnappyRenderer\Renderable\RenderableClosure($closure);
    foreach ($models as $model) {
        yield $renderable->render($model);
    }
}

/**
 * @phpstan-import-type element from SnappyRenderer\Renderable
 * @param iterable<string> $strings
 * @return iterable<element>
 */
function v_each_string(iterable $strings, Closure $closure): iterable
{
    $renderable = new SnappyRenderer\Renderable\RenderableClosure($closure);
    foreach ($strings as $string) {
        yield $renderable->render(new SnappyRenderer\Model\StringableModel($string));
    }
}
