<?php

declare(strict_types=1);

namespace SnappyRendererTest\Integration;

use PHPUnit\Framework\TestCase;
use SnappyRenderer\DefaultStrategy;
use SnappyRenderer\Renderer;
use SnappyRendererTest\Integration\Components\Body;
use SnappyRendererTest\Integration\Components\Layout;

class CompletePageLayoutTest extends TestCase
{
    public function testRenderPage(): void
    {
        $renderer = new Renderer(new DefaultStrategy());

        $layout = new Layout(new Body());

        $this->assertHtmlFileEqualsHtmlString(__DIR__ . '/expected.html', $renderer->render($layout));
    }

    public static function assertHtmlFileEqualsHtmlString(string $file, string $html, string $message = '')
    {
        $config = array(
            'indent' => false,
            'output-xml' => true,
            'wrap' => 0,
        );

        $fileContents = file_get_contents($file);
        $tidyFile = tidy_parse_string($fileContents, $config, 'utf8');
        $tidyHtml = tidy_parse_string($html, $config, 'utf8');

        $serializedFile = (string)$tidyFile;
        $serializedHtml = (string)$tidyHtml;

        $message = $message ?: "Failed asserting that the HTML file '{$file}' is equal to the HTML string.";
        self::assertSame($serializedHtml, $serializedFile, $message);
    }
}