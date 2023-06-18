<?php

declare(strict_types=1);

namespace Blue\Snappy\Renderer\Helper;

use Blue\Snappy\Renderer\Renderable;
use Blue\Snappy\Renderer\Renderer;


/**
 * @internal
 */
final class Placeholder implements Renderable
{
    private string $code;

    /**
     * @var array<int, mixed>
     */
    private array $replacements = [];

    /**
     * @param string $code
     */
    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param mixed $view
     * @return $this
     */
    public function addReplacement($view): self
    {
        $this->replacements[] = $view;
        return $this;
    }

    /**
     * @return Capture[]
     */
    public function getReplacements(): array
    {
        return $this->replacements;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param Renderer $renderer
     * @param $data
     * @return iterable<mixed, mixed>
     */
    public function render(Renderer $renderer, $data = null): iterable
    {
        yield <<<HTML
<script type="text/html" id="$this->code"></script>
HTML;
    }
}