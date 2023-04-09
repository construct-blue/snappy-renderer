<?php

declare(strict_types=1);

namespace SnappyRenderer;

class Capture
{
    private string $code;
    private $view;

    public function __construct(string $code, $view)
    {
        $this->code = $code;
        $this->view = $view;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getView()
    {
        return $this->view;
    }
}