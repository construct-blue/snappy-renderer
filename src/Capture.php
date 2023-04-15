<?php

declare(strict_types=1);

namespace SnappyRenderer;

class Capture
{
    private string $code;
    /** @var mixed */
    private $view;

    /**
     * @param string $code
     * @param mixed $view
     */
    public function __construct(string $code, $view)
    {
        $this->code = $code;
        $this->view = $view;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getView()
    {
        return $this->view;
    }
}