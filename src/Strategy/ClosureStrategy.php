<?php

declare(strict_types=1);

namespace SnappyRenderer\Strategy;

use Closure;

use ReflectionException;
use ReflectionFunction;
use ReflectionNamedType;
use SnappyRenderer\Exception\RenderException;
use SnappyRenderer\Renderer;
use SnappyRenderer\Strategy;
use SplObjectStorage;

class ClosureStrategy implements Strategy
{
    /** @var SplObjectStorage<Closure, ReflectionFunction> */
    private SplObjectStorage $storage;
    private Strategy $strategy;

    public function __construct(Strategy $strategy)
    {
        $this->strategy = $strategy;
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
    public function execute($view, Renderer $renderer, $data = null): string
    {
        if ($view instanceof Closure) {
            $params = [];
            try {
                foreach ($this->getReflection($view)->getParameters() as $parameter) {
                    $type = $parameter->getType();
                    if ($type instanceof ReflectionNamedType && $type->getName() === Renderer::class) {
                        $params[] = $renderer;
                    } elseif (is_array($data) && isset($data[$parameter->getName()])) {
                        $params[] = $data[$parameter->getName()];
                    } else {
                        $params[] = $data;
                    }
                }
            } catch (ReflectionException $exception) {
                RenderException::forThrowableInView($exception, $view);
            }

            return $renderer->render($view(...$params), $data);
        }
        return $this->strategy->execute($view, $renderer, $data);
    }
}