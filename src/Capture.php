<?php

declare(strict_types=1);

namespace SnappyRenderer;

class Capture
{
    private Placeholder $placeholder;
    /** @var mixed */
    private $view;

    /**
     * @param Placeholder $placeholder
     * @param mixed $view
     */
    public function __construct(Placeholder $placeholder, $view)
    {
        $this->placeholder = $placeholder;
        $this->view = $view;
    }

    public function getPlaceholder(): Placeholder
    {
        return $this->placeholder;
    }

    /**
     * @return mixed
     */
    public function getView()
    {
        return $this->view;
    }
}