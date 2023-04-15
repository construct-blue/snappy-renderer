<?php

declare(strict_types=1);

namespace SnappyRenderer\Strategy;

use SnappyRenderer\Capture;
use SnappyRenderer\Exception\RenderException;
use SnappyRenderer\Renderer;
use SnappyRenderer\Strategy;

class CaptureStrategy implements Strategy
{
    /** @var Capture[] */
    private array $captures = [];
    private Strategy $strategy;

    public function __construct(Strategy $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * @param mixed $view
     * @param Renderer $renderer
     * @return string
     * @throws RenderException
     */
    public function execute($view, Renderer $renderer): string
    {
        if ($renderer->getLevel() === 1) {
            $this->captures = [];
        }

        if ($view instanceof Capture) {
            $this->captures[] = $view;
            return '';
        }

        $result = $this->strategy->execute($view, $renderer);

        if ($renderer->getLevel() === 1) {
            foreach ($this->captures as $capture) {
                $result = str_replace(
                    $capture->getCode(),
                    $renderer->render($capture->getView()),
                    $result
                );
            }
        }
        return $result;
    }
}