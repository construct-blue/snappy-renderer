<?php

declare(strict_types=1);

namespace SnappyRenderer\Strategy;

use Closure;
use ReflectionException;
use ReflectionFunction;
use ReflectionNamedType;
use SnappyRenderer\AbstractStrategy;
use SnappyRenderer\Exception\RenderException;
use SnappyRenderer\Helper\Arguments;
use SnappyRenderer\Renderer;
use SnappyRenderer\Strategy;
use SplObjectStorage;

final class ClosureStrategy extends AbstractStrategy
{
    /** @var SplObjectStorage<Closure, ReflectionFunction> */
    private SplObjectStorage $storage;

    public function __construct(Strategy $strategy)
    {
        parent::__construct($strategy);
        $this->storage = new SplObjectStorage();
    }

    /**
     * @param Closure $view
     * @return ReflectionFunction
     * @throws ReflectionException
     */
    private function getReflection(Closure $view): ReflectionFunction
    {
        if (!$this->storage->contains($view)) {
            $this->storage[$view] = new ReflectionFunction($view);
        }

        return $this->storage[$view];
    }

    /**
     * @param mixed $view
     * @param Renderer $renderer
     * @param mixed|null $data
     * @return string
     * @throws RenderException
     */
    public function execute($view, Renderer $renderer, $data): string
    {
        if ($view instanceof Closure) {
            $args = [];
            try {
                foreach ($this->getReflection($view)->getParameters() as $parameter) {
                    $position = $parameter->getPosition();
                    $args[$position] = $data;
                    $type = $parameter->getType();
                    if ($type instanceof ReflectionNamedType && $type->getName() === Renderer::class) {
                        $args[$position] = $renderer;
                    } elseif ($data instanceof Arguments) {
                        $args[$position] = $data[$parameter->getName()] ?? null;
                    }
                }
                ksort($args, SORT_NUMERIC);
            } catch (ReflectionException $exception) {
                throw RenderException::forThrowableInView($exception, $view);
            }
            return (new StringStrategy($renderer))->execute($view(...$args), $renderer, $data);
        }
        return $this->next($view, $renderer, $data);
    }
}