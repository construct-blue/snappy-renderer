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
        if ($view instanceof Capture) {
            $this->captures[] = $view;
            return '';
        }

        if ($renderer->getLevel() === 1) {
            $this->captures = [];
        }

        $result = $this->strategy->execute($view, $renderer);

        if ($renderer->getLevel() === 1) {
            return $this->replacePlaceholders($result, $renderer);
        }
        return $result;
    }

    /**
     * @param string $html
     * @param Renderer $renderer
     * @return string
     * @throws RenderException
     */
    private function replacePlaceholders(string $html, Renderer $renderer): string
    {
        $placeholders = [];
        $replacements = [];
        foreach ($this->captures as $capture) {
            $placeholders[] = $capture->getCode();
            $replacements[] = $renderer->render($capture->getView());
        }
        return str_replace($placeholders, $replacements, $html);
    }
}