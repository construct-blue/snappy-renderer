<?php

declare(strict_types=1);

namespace Blue\Snappy\Renderer\Strategy;

use Blue\Snappy\Renderer\Exception\RenderException;
use Blue\Snappy\Renderer\Helper\Capture;
use Blue\Snappy\Renderer\Helper\Placeholder;
use Blue\Snappy\Renderer\Renderer;
use Blue\Snappy\Renderer\Strategy\Base\PipelineStrategy;

final class CaptureStrategy extends PipelineStrategy
{
    /** @var Placeholder[] */
    private array $placeholders = [];

    /**
     * @var Capture[]
     */
    private array $captures = [];

    /**
     * @param mixed $view
     * @param Renderer $renderer
     * @param mixed|null $data
     * @return string
     * @throws RenderException
     */
    public function execute($view, Renderer $renderer, $data): string
    {
        if ($renderer->getLevel() === 1) {
            $this->placeholders = [];
            $this->captures = [];
        }

        if ($view instanceof Placeholder) {
            if (isset($this->placeholders[$view->getCode()])) {
                throw new RenderException(sprintf('Placeholder "%s" already in use.', $view->getCode()));
            }
            $this->placeholders[$view->getCode()] = $view;
        }

        if ($view instanceof Capture) {
            $this->captures[] = $view;
            return '';
        }

        $result = $this->next($view, $renderer, $data);

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
        foreach ($this->captures as $capture) {
            if (!isset($this->placeholders[$capture->getPlaceholder()->getCode()])) {
                throw new RenderException(
                    sprintf('Placeholder "%s" not found.', $capture->getPlaceholder()->getCode())
                );
            }
            $this->placeholders[$capture->getPlaceholder()->getCode()]->addReplacement($capture->getView());
        }

        $placeholders = [];
        $replacements = [];
        foreach ($this->placeholders as $placeholder) {
            $placeholders[] = $renderer->render($placeholder->render($renderer));
            $replacements[] = $renderer->render($placeholder->getReplacements());
        }
        return str_replace($placeholders, $replacements, $html);
    }
}